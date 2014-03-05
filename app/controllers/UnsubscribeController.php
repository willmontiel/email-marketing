<?php
class UnsubscribeController extends ControllerBase 
{
	public function contactAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri($this->urlManager->getBaseUri(true));

			$idenfifiers = $linkEncoder->decodeLink('unsubscribe/contact', $parameters);
			list($v, $idMail, $idContact) = $idenfifiers;

			$trackingObj = new TrackingObject();
			$trackingObj->setSendIdentification($idMail, $idContact);
			
			$contact = $trackingObj->getContact();
			$email = Email::findFirstByIdEmail($contact->idEmail);
			if($trackingObj->canTrackUnsubscribedEvents()) {
				$dbase = Dbase::findFirstByIdDbase($contact->idDbase);
				$this->view->setVar('dbase', $dbase);
			}
			
			$this->view->setVar('email', $email);
			$this->view->setVar('contact', $contact);
		}
		catch (Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error/link');
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error/link');
		}
	}
	
	public function successAction($parameters)
	{
		$this->logger->log('Inicio tracking de desuscripcion');
		$info = $_SERVER['HTTP_USER_AGENT'];

		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri($this->urlManager->getBaseUri(true));

			$idenfifiers = $linkEncoder->decodeLink('unsubscribe/contact', $parameters);
			list($v, $idMail, $idContact) = $idenfifiers;

			$userAgent = new UserAgentDetectorObj();
			$userAgent->setInfo($info);

			$trackingObj = new TrackingObject();
			$trackingObj->setSendIdentification($idMail, $idContact);
			$trackingObj->trackUnsubscribedEvent();
			//return $this->response->redirect('unsubscribe/success');
		}
		catch (Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error/link');
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error/link');
		}
	}
}