<?php
class FormController extends ControllerBase
{
	public function frameAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('form/frame', $parameters);
			list($idLink, $idForm, $idDbase) = $idenfifiers;
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
		}
		$form = Form::findFirst(array(
			'conditions' => 'idForm = ?1 AND idDbase = ?2',
			'bind' => array(1 => $idForm,
							2 => $idDbase)
		));
		
		try {
			$creator = new FormCreator();
			$html = $creator->getHtmlForm($form);
			$link = $creator->getLinkAction($form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->setJsonResponse(array('status' => 'error'), 400, $e);	
		}
		
		$this->view->setVar('elements', $html);
		$this->view->setVar('link', $link);
	}
}
