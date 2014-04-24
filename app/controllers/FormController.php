<?php
class FormController extends ControllerBase
{
	public function frameAction($idForm)
	{
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1',
			'bind' => array(1 => $idForm)
		));
		
		try {
			$creator = new FormCreator();
			$html = $creator->getHtmlForm($form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, $e);	
		}
		
		$this->view->setVar('elements', $html);
	}
}
