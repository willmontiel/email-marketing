<?php
class TrackController extends ControllerBase
{
	public function openAction($parameters)
	{
		$info = $_SERVER['HTTP_USER_AGENT'];
		$idenfifiers = explode("-", $parameters);
		
		list($idLink, $idMail, $idContact, $md5) = $idenfifiers;
		
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $urlManager->getBaseUri(true) . 'track/open/1-' . $idMail . '-' . $idContact;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		
		if ($md5 == $md5_2) {
			try {
				$userAgent = new UserAgentDetectorObj();
				$userAgent->setInfo($info);
				
				$trackingObj = new TrackingObject();
				$trackingObj->updateTrackOpen($idMail, $idContact, $userAgent->getOperativeSystem(), $userAgent->getBrowser());
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
			
			$img = $urlManager->getBaseUri(true) . 'images/tracking.gif';

			$this->response->setHeader("Content-Type", "image/gif");
			
			$this->view->disable();
			return $this->response->setContent(file_get_contents($img));
		}
		else {
			$this->response->redirect('error/link');
		}
	}
	
	public function clickAction($parameters)
	{
//		$this->logger->log('Entró a tracking de click');
		$info = $_SERVER['HTTP_USER_AGENT'];
		
		$idenfifiers = explode("-", $parameters);
		
//		$this->logger->log('Parameters' . $parameters);
//		$this->logger->log('Parameters: ' . print_r($idenfifiers, true));
		list($idTypeLink, $idLink, $idMail, $idContact, $md5) = $idenfifiers;
		
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$href = $urlManager->getBaseUri(true) . 'track/click/1-' . $idLink . '-' . $idMail . '-' . $idContact;
		$md5_2 = md5($href . '-Sigmamovil_Rules');
		
		if ($md5 == $md5_2) {
			try {
				$userAgent = new UserAgentDetectorObj();
				$userAgent->setInfo($info);
				
				$trackingObject = new TrackingObject();
				$url = $trackingObject->updateTrackClick($idLink, $idMail, $idContact, $userAgent->getOperativeSystem(), $userAgent->getBrowser());
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e . ']');
			}
			if (!$url) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($url, true);
		}
		else {
			$this->logger->log('Enlace de tracking click inválido');
			return $this->response->redirect('error/link');
		}
	}
}