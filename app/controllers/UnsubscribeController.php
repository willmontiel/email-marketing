<?php
class UnsubscribeController extends ControllerBase 
{
	public function contactAction($parameters)
	{
		if ($this->request->isPost()) {
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
		else {
			$this->logger->log('Seguro que desea desuscribir');
		}
	}
}