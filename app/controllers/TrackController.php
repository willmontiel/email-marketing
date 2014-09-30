<?php
require 'vendor/autoload.php';

class TrackController extends ControllerBase
{
	public function openAction($parameters)
	{
//		$this->logger->log('Inicio tracking de apertura');
//		$info = $_SERVER['HTTP_USER_AGENT'];
		$this->getIp();
		
		$gi = geoip_open("/usr/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);

		$this->logger->log(geoip_country_code_by_addr($gi, "24.24.24.24"));
		$this->logger->log(geoip_country_name_by_addr($gi, "24.24.24.24"));
		$this->logger->log(geoip_country_code_by_addr($gi, "80.24.24.24"));
		$this->logger->log(geoip_country_name_by_addr($gi, "80.24.24.24"));

		geoip_close($gi);
		
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('track/open', $parameters);
			list($idLink, $idMail, $idContact) = $idenfifiers;
			//Se instancia el detector de agente de usuario para capturar el OS y el Browser con que se efectuó la 
			//petición
//			$userAgent = new UserAgentDetectorObj();
//			$userAgent->setInfo($info);

			$trackingObj = new TrackingObject();
			$trackingObj->setSendIdentification($idMail, $idContact);
			$trackingObj->trackOpenEvent($userAgent);	
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
		}
//		$this->logger->log('Preparando para retornar img');
		// TODO: la imagen debe tener la ubicacion fisica en disco y no la URL
		$img = '../public/images/tracking.gif';

		$this->response->setHeader("Content-Type", "image/gif");

		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function clickAction($parameters)
	{
//		$this->logger->log('Inicio tracking de click');
//		$info = $_SERVER['HTTP_USER_AGENT'];
		
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('track/click', $parameters);
			list($v, $idLink, $idMail, $idContact) = $idenfifiers;
			
			$trackingObj = new TrackingObject();
			
//			$userAgent = new UserAgentDetectorObj();
//			$userAgent->setInfo($info);
			
			if($idContact != 0) {
				$trackingObj->setSendIdentification($idMail, $idContact);
			}
				
			$url = $trackingObj->trackClickEvent($idLink, $userAgent);	
				
			if (!$url) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($url, true);
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error/link');
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			return $this->response->redirect('error/link');
		}
	}
	
	public function mtaeventAction()
	{
		$content = $this->request->getRawBody();
		if (trim($content) === '' || $content == null) {
			$this->logger->log('No hay contenido, no se registró evento de mta (Rebote, Spam)');
			return false;
		}
		$cobject = json_decode($content, true);
//		$this->logger->log('[' .print_r($cobject, true) .  ']');
		$i = 1;
		foreach ($cobject as $c) {
			$mxc = substr($c['click_tracking_id'], 2);
			$ids = explode('x', $mxc);
			$date = $c['event_time'];
//			$this->logger->log('Empezó track de evento: ' . $i);
			try {
				$trackingObj = new TrackingObject();
				$trackingObj->setSendIdentification($ids[0], $ids[1], $ids[2]);
				switch ($c['event_type']) {
					case 'bounce_all':
						$trackingObj->trackSoftBounceEvent($c['bounce_code'], $date);
						break;
					case 'bounce_bad_address':
						$trackingObj->trackHardBounceEvent($c['bounce_code'], $date);
						break;
					case 'scomp':
						$trackingObj->trackSpamEvent($c['bounce_code'], $date);
						break;
				}
				$this->logger->log("Update funcionó para : idMail: {$ids[0]}, idContact: {$ids[1]}, idEmail: {$ids[2]} ");
				
			}
			catch (\Exception $e) {
				$this->logger->log('Exception: [' . $e . ']');
				return $this->setJsonResponse(array('status' => 'ERROR', 'description' => 'Exception'));
			}
			catch (\InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e . ']');
				return $this->setJsonResponse(array('status' => 'ERROR', 'description' => 'Invalid Argument Exception'));
			}
//			$this->logger->log('Pasó la prueba: ' . $i);
			$i ++;
		}
//		$this->logger->log('Preparando para dar respuesta');
		return $this->setJsonResponse(array('status' => 'OK', 'description' => 'Everything seems to be fine!'));
	}
	
	public function opensocialAction($parameters)
	{
//		$this->logger->log('Inicio tracking de apertura por red social');
//		$info = $_SERVER['HTTP_USER_AGENT'];
		try {
			// Decodificar enlace
			$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
			$parts = $linkdecoder->decodeLink('track/opensocial', $parameters);
			list($v, $idMail, $idContact, $socialType) = $parts;
//			$this->logger->log('El link es valido');
			
			//Se instancia el detector de agente de usuario para capturar el OS y el Browser con que se efectuó la 
			//petición
//			$userAgent = new UserAgentDetectorObj();
//			$userAgent->setInfo($info);
				
			$trackingObj = new TrackingObject();
			$trackingObj->setSendIdentification($idMail, $idContact);
			$mxc = $trackingObj->getMxC();
				
			$instance = \EmailMarketing\SocialTracking\TrackingSocialAbstract::createInstanceTracking($socialType);
			$instance->setMxc($mxc);
			$instance->trackOpen();
			$instance->save();
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
		}
		
//		$this->logger->log('Preparando para retornar img');
			// TODO: la imagen debe tener la ubicacion fisica en disco y no la URL
		$img = '../public/images/tracking.gif';	
		$this->response->setHeader("Content-Type", "image/gif");

		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function clicksocialAction($parameters)
	{
//		$this->logger->log('Inicio tracking de apertura por red social');
//		$info = $_SERVER['HTTP_USER_AGENT'];
		
		try {		
			// Decodificar enlace
			$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
			$parts = $linkdecoder->decodeLink('track/clicksocial', $parameters);
			list($v, $idLink, $idMail, $idContact, $socialType) = $parts;
//			$this->logger->log('El link es valido');
			
			$trackingObj = new TrackingObject();
			
			if($idContact != 0) {
				$trackingObj->setSendIdentification($idMail, $idContact);
				$mxcxl = $trackingObj->getMxCxL($idLink);
				if (!$mxcxl) {
					$mxcxl = $trackingObj->createNewMxcxl($idLink, 0);
				}

				$instance = \EmailMarketing\SocialTracking\TrackingSocialAbstract::createInstanceTracking($socialType);
				$instance->setMxcxl($mxcxl);
				$instance->trackClick();
				$instance->saveClick();
			}
			
			$url = $trackingObj->getLinkToRedirect($idLink);
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			return $this->response->redirect('error/link');
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			return $this->response->redirect('error/link');
		}			
		return $this->response->redirect($url, true);
	}
	
	private function getIp () {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){//Verificar la ip compartida de internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){//verificar si la ip fue provista por un proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else { 
			$ip = $_SERVER['REMOTE_ADDR']; 
		}
		
		$this->logger->log($ip);
	}
}
