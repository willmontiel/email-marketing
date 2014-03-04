<?php
class TrackController extends ControllerBase
{
	public function openAction($parameters)
	{
		$this->logger->log('Inicio tracking de apertura');
		$info = $_SERVER['HTTP_USER_AGENT'];
		$idenfifiers = explode("-", $parameters);
		
		list($idLink, $idMail, $idContact, $md5) = $idenfifiers;
//		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $this->urlManager->getBaseUri(true) . 'track/open/1-' . $idMail . '-' . $idContact;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		
		if ($md5 == $md5_2) {
			$this->logger->log('El link es valido');
			try {
				//Se instancia el detector de agente de usuario para capturar el OS y el Browser con que se efectuó la 
				//petición
				$userAgent = new UserAgentDetectorObj();
				$userAgent->setInfo($info);
				
				$trackingObj = new TrackingObject();
				$trackingObj->setSendIdentification($idMail, $idContact);
				$trackingObj->trackOpenEvent($userAgent);	
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
			$this->logger->log('Preparando para retornar img');
			// TODO: la imagen debe tener la ubicacion fisica en disco y no la URL
			$img = '../public/images/tracking.gif';

			$this->response->setHeader("Content-Type", "image/gif");
			
			$this->view->disable();
			return $this->response->setContent(file_get_contents($img));
		}
		else {
			$this->logger->log('Link inválido');
			$this->response->redirect('error/link');
		}
	}
	
	public function clickAction($parameters)
	{
		$this->logger->log('Inicio tracking de click');
		$info = $_SERVER['HTTP_USER_AGENT'];
		
		$idenfifiers = explode("-", $parameters);
		
		list($idTypeLink, $idLink, $idMail, $idContact, $md5) = $idenfifiers;
//		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$href = $this->urlManager->getBaseUri(true) . 'track/click/1-' . $idLink . '-' . $idMail . '-' . $idContact;
		$md5_2 = md5($href . '-Sigmamovil_Rules');
		
		if ($md5 == $md5_2) {
			try {
				$userAgent = new UserAgentDetectorObj();
				$userAgent->setInfo($info);
				
				$trackingObj = new TrackingObject();
				$trackingObj->setSendIdentification($idMail, $idContact);
				$url = $trackingObj->trackClickEvent($idLink, $userAgent);	
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
	
	public function mtaeventAction()
	{
		$content = $this->request->getRawBody();
		if (trim($content) === '' || $content == null) {
			$this->logger->log('No hay contenido, no se registró evento de rebote');
			return false;
		}
		$cobject = json_decode($content, true);
		$this->logger->log('[' .print_r($cobject, true) .  ']');
		$i = 1;
		foreach ($cobject as $c) {
			$mxc = substr($c['click_tracking_id'], 2);
			$ids = explode('x', $mxc);
			$date = $c['event_time'];
			$this->logger->log('Empezó track de evento: ' . $i);
			try {
				$trackingObj = new TrackingObject();
				$trackingObj->setSendIdentification($ids[0], $ids[1]);
				switch ($c['event_type']) {
					case 'bounce_all':
						$trackingObj->trackSoftBounceEvent($c['bounce_code'], $date);
						break;
					case 'bounce_bad_address':
						$trackingObj->trackHardBounceEvent($date);
						break;
					case 'scomp':
						$trackingObj->trackSpamEvent($c['bounce_code'], $date);
						break;
				}
				$this->logger->log('Update funciono para : ' . $ids[0] . ', ' . $ids[1]);
				
			}
			catch (Exception $e) {
				$this->logger->log('Va a ocurrir una excepción');
				$this->logger->log('Exception: [' . $e . ']');
				return $this->setJsonResponse(array('status' => 'ERROR', 'description' => 'Invalid Argument Exception'));
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log('Va a ocurrir una excepción');
				$this->logger->log('Exception: [' . $e . ']');
				return $this->setJsonResponse(array('status' => 'ERROR', 'description' => 'Invalid Argument Exception'));
			}
			$this->logger->log('Pasó la prueba: ' . $i);
			$i ++;
		}
		$this->logger->log('Preparando para dar respuesta');
		return $this->setJsonResponse(array('status' => 'OK', 'description' => 'Everything seems to be fine!'));
	}
	
	public function opensocialAction($parameters)
	{
		$this->logger->log('Inicio tracking de apertura por red social');
		$info = $_SERVER['HTTP_USER_AGENT'];
		$idenfifiers = explode("-", $parameters);
		
		list($idLink, $idMail, $idContact, $socialType, $md5) = $idenfifiers;
		
		$src = $this->urlManager->getBaseUri(true) . 'track/opensocial/1-' . $idMail . '-' . $idContact . '-' . $socialType;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		
		if ($md5 == $md5_2) {
			$this->logger->log('El link es valido');
			try {
				//Se instancia el detector de agente de usuario para capturar el OS y el Browser con que se efectuó la 
				//petición
				$userAgent = new UserAgentDetectorObj();
				$userAgent->setInfo($info);
				
				$trackingObj = new TrackingObject();
				$trackingObj->setSendIdentification($idMail, $idContact);
				$mxc = $trackingObj->getMxC();
				
				$instance = \EmailMarketing\SocialTracking\TrackingSocialAbstract::createInstanceTracking($mxc, $socialType);
				
				$this->logger->log('Objeto de tracking de red social: [' . print_r($instance, true) . ']');
				$instance->trackOpen();
				$instance->save();
			}
			catch (Exception $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
			$this->logger->log('Preparando para retornar img');
			// TODO: la imagen debe tener la ubicacion fisica en disco y no la URL
			$img = '../public/images/tracking.gif';

			$this->response->setHeader("Content-Type", "image/gif");
			
			$this->view->disable();
			return $this->response->setContent(file_get_contents($img));
		}
		else {
			$this->logger->log('Link inválido');
			$this->response->redirect('error/link');
		}
	}
}