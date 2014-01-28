<?php
class TrackController extends ControllerBase
{
	public function openAction($parameters)
	{
		$info = $_SERVER['HTTP_USER_AGENT'];
		$this->logger->log('Info: ' . $info);
		$idenfifiers = explode("_", $parameters);
		
		list($idLink, $idMail, $idContact, $md5) = $idenfifiers;
		
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $urlManager->getBaseUri(true) . '/track/open/1_' . $idMail . '_' . $idContact;
		$md5_2 = md5($src . '_Sigmamovil_Rules');
		
//		$this->logger->log('Iniciando validación de enlace');
//		$this->logger->log('md1: ' . $md5);
//		$this->logger->log('md2: ' . $md5_2);
		if ($md5 == $md5_2) {
//			$this->logger->log('Enlace aprobado');
			try {
				$userAgent = new UserAgentDetectorObj();
				$userAgent->setInfo($info);
				
				$trackingObj = new TrackingObject();
				$trackingObj->updateTrackOpen($idMail, $idContact, $userAgent->getOperativeSystem(), $userAgent->getBrowser());
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
			/**
			 * =================================================================== *
			 */
			
			$this->logger->log('Devolviendo imagen');
			$img = $urlManager->getBaseUri(true) . '/images/tracking.gif';

			$this->response->setHeader("Content-Type", "image/gif");
//			$this->response->setHeader("Content-Length", 43);
			$this->view->disable();
			return $this->response->setContent(file_get_contents($img));
		}
		else {
			$this->logger->log('Enlace reprobado');
			$this->response->redirect('error');
		}
	}
}