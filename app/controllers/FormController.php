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

			$form = Form::findFirst(array(
				'conditions' => 'idForm = ?1 AND idDbase = ?2',
				'bind' => array(1 => $idForm,
								2 => $idDbase)
			));

			$creator = new FormCreator();
			$html = $creator->getHtmlForm($form);
			$link = $creator->getLinkAction($form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error');
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			return $this->response->redirect('error');
		}
//		$this->response->setHeader("X-Frame-Options", "GOFORIT");
		$this->view->setVar('elements', $html);
		$this->view->setVar('link', $link);
	}
	
	
	public function updateAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('form/update', $parameters);
			list($idLink, $idForm, $idContact, $idMail) = $idenfifiers;

			$contact = Contact::findFirst(array(
				'conditions' => 'idContact = ?1',
				'bind' => array(1 => $idContact)
			));

			$form = Form::findFirst(array(
				'conditions' => 'idForm = ?1 AND idDbase = ?2',
				'bind' => array(1 => $idForm,
								2 => $contact->idDbase)
			));

			$creator = new FormCreator();
			$creator->setContact($contact);
			$html = $creator->getHtmlForm($form);
			$link = $creator->getLinkUpdateAction($form);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error');
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			return $this->response->redirect('error');
		}

		$this->view->setVar('elements', $html);
		$this->view->setVar('link', $link);
	}
}
