<?php

/**
 * @RoutePrefix("/api")
 */

class ApiformController extends ControllerBase
{
	/**
	 * @Get("/forms")
	 */
	
	public function getformsAction()
	{
		
		$phql = "	SELECT f.* 
					FROM form AS f 
						JOIN dbase AS d ON (f.idDbase = d.idDbase) 
						JOIN account AS a ON (d.idAccount = a.idAccount) 
					WHERE a.idAccount = {$this->user->idAccount}";
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$forms = $modelsManager->executeQuery($phql);
		
		if ($forms) {
			try {
				$wrapper = new FormWrapper();
				foreach ($forms as $form) {
					$formlist[] = $wrapper->fromPObjectToJObject($form);
				}
				
				$lists = $wrapper->getAccountListsInJson($this->user->account);
			}
			catch (\Exception $e) {
				$this->logger->log('Exception: [' . $e . ']');
				return $this->setJsonResponse(array('status' => 'error'), 400, $e);	
			}
			return $this->setJsonResponse(array('forms' => $formlist, 'lists' => $lists,), 201, 'Success');
		}
		return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
	}
	
	
	/**
	 * 
	 * @Get("/forms/{idForm:[0-9]+}")
	 */
	public function getforminformationAction($idForm)
	{
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1',
			'bind' => array(1 => $idForm)
		));
		
		if (!$form) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el formulario');
		}
		
		try {
			$wrapper = new FormWrapper();
			$formjson = $wrapper->fromPObjectToJObject($form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, $e);	
		}
		
		return $this->setJsonResponse(array('form' => $formjson), 201, 'Success');	
	}
	
	/**
	 * 
	 * @Post("/forms")
	 */
	public function createforminformationAction()
	{
		$contentsraw = $this->getRequestContent();
		$contentsT = json_decode($contentsraw);
		
		$contactlist = Contactlist::findFirst(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $contentsT->form->listselected)
		));
		
		if (!$contactlist) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la lista de contactos');
		}
		
		try {
			$wrapper = new FormWrapper();
			$wrapper->setDbase($contactlist->dbase);
			$form = $wrapper->saveInformation($contentsT->form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, $e);	
		}
		
		return $this->setJsonResponse(array('form' => $form), 201, 'Success');	
	}
	
	/**
	 * 
	 * @Put("/forms/{idForm:[0-9]+}")
	 */
	public function createformcontentAction($idForm)
	{
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1',
			'bind' => array(1 => $idForm)
		));
		
		if (!$form) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el formulario');
		}
		
		$contentsraw = $this->getRequestContent();
		$contentsT = json_decode($contentsraw);
		
		try {
			$wrapper = new FormWrapper();
			$wrapper->setDbase($form->dbase);
			$result = $wrapper->updateFormContent($form, $contentsT->form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, $e);	
		}
		
		return $this->setJsonResponse(array('form' => $result), 201, 'Success');	
	}
	
	/**
	 * 
	 * @Route("/forms/{idForm:[0-9]+}", methods="DELETE")
	 */
	
	public function deleteformAction($idForm)
	{
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1 AND idDbase = ?2',
			'bind' => array(1 => $idForm)
		));
		
		if (!$form) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el formulario');
		}
		
		$response = $form->delete();
		
		return $this->setJsonResponse(array('form' => null), 202, 'form deleted success');
	}
}

?>
