<?php
/**
 * @RoutePrefix("/api/dbase")
 */
class DbaseapiController extends ControllerBase
{
	/**
	 * @Get("/{idDbase:[0-9]+}/contacts")
	 */
	public function searchcontactsAction($idDbase)
	{
		$search = $this->request->getQuery('searchCriteria', null, null);
		$filter = $this->request->getQuery('filter', null, null);
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
                
		$pager = new PaginationDecorator();
		if ($limit) {
				$pager->setRowsPerPage($limit);
		}
		if ($page) {
				$pager->setCurrentPage($page);
		}
                
		$account = $this->user->account;
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase,
							2 => $account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la base de datos');
		}
		
		$contactlists = Contactlist::findByIdDbase($dbase->idDbase);
//		$contactlists = $dbase->contactlist;
		
		$mailhistory = new \EmailMarketing\General\ModelAccess\ContactMailHistory();
		
		try { 
			if ($filter != null) {
				if (!empty($search)) {
					$searchCriteria = new \EmailMarketing\General\ModelAccess\ContactSearchCriteria($search);
				}
				$searchFilter = new \EmailMarketing\General\ModelAccess\ContactSearchFilter($filter);
				
				$contactset = new \EmailMarketing\General\ModelAccess\ContactSet();
				$contactset->setSearchCriteria($searchCriteria);
				$contactset->setSearchFilter($searchFilter);
				$contactset->setContactMailHistory($mailhistory);
				$contactset->setAccount($account);
				$contactset->setDbase($dbase);
				$contactset->setPaginator($pager);
				$contactset->load();
				
				$contactlistSet = new \EmailMarketing\General\ModelAccess\ContactlistSet();
				$contactlistSet->setContactlist($contactlists);
				$contactset->setPaginator($pager);
				$contactlistSet->load();
				$rest = new \EmailMarketing\General\Ember\RESTResponse();
				
				$rest->addDataSource($contactlistSet);
				$rest->addDataSource($contactset);
			
				return $this->setJsonResponse($rest->getRecords());
			}	
			else {
				$wrapper = new ContactWrapper();
				$wrapper->setAccount($account);
				$wrapper->setIdDbase($dbase->idDbase);
				$wrapper->setPager($pager);
				$wrapper->setContactMailHistory($mailhistory);
				$result = $wrapper->findContacts($dbase);
				
				return $this->setJsonResponse($result);	
			}
		}
		catch (Exception $e) {
			$this->logger->log('Exception: ' . $e);
			return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Invalid Argument Exception: ' . $e);
			 return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
		}
	}
	
	/**
	 * @Get("/{idDbase:[0-9]+}/forms")
	 */
	public function getformsAction($idDbase)
	{
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase,
							2 => $this->user->account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la base de datos');
		}
		
		$formlist = array();
		
		$forms = Form::findByIdDbase($dbase->idDbase);
		
		if ($forms) {
			$wrapper = new FormWrapper();
			foreach ($forms as $form) {
				$formlist[] = $wrapper->fromPObjectToJObject($form);
			}
			return $this->setJsonResponse(array('forms' => $formlist), 201, 'Success');
		}
		return $this->setJsonResponse(array('status' => 'failed'), 500, 'error');
	}
	
	/**
	 * 
	 * @Get("/{idDbase:[0-9]+}/forms/{idForm:[0-9]+}")
	 */
	public function getforminformationAction($idDbase, $idForm)
	{
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase,
							2 => $this->user->account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la base de datos');
		}
		
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1 AND idDbase = ?2',
			'bind' => array(1 => $idForm,
							2 => $idDbase)
		));
		
		if (!$form) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el formulario');
		}
		
		try {
			$wrapper = new FormWrapper();
			$wrapper->setDbase($dbase);
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
	 * @Post("/{idDbase:[0-9]+}/forms")
	 */
	public function createforminformationAction($idDbase)
	{
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase,
							2 => $this->user->account->idAccount)
		));
		
		if (!$dbase) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró la base de datos');
		}
		
		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
//		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		try {
			$wrapper = new FormWrapper();
			$wrapper->setDbase($dbase);
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
	 * @Put("/{idDbase:[0-9]+}/forms/{idForm:[0-9]+}")
	 */
	public function createformcontentAction($idDbase, $idForm)
	{
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idDbase,
							2 => $this->user->account->idAccount)
		));
		
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1 AND idDbase = ?2',
			'bind' => array(1 => $idForm,
							2 => $idDbase)
		));
		
		if (!$dbase && !$form) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el formulario');
		}
		
		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
//		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		
		try {
			$wrapper = new FormWrapper();
			$wrapper->setDbase($dbase);
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
	 * @Route("/{idDbase:[0-9]+}/forms/{idForm:[0-9]+}", methods="DELETE")
	 */
	
	public function deleteformAction($idDbase, $idForm)
	{
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1 AND idDbase = ?2',
			'bind' => array(1 => $idForm,
							2 => $idDbase)
		));
		
		if (!$form) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró el formulario');
		}
		
		$response = $form->delete();
		
		return $this->setJsonResponse($response);	
	}
}