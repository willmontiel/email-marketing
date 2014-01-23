<?php
class AccountController extends ControllerBase
{
	public function openAction($parameters)
	{
		$idenfifiers = explode("_", $parameters);
		
		list($idLink, $idMail, $idContact, $md5) = $idenfifiers;
		
		$urlManager = new UrlManagerObject(true);
		$src = $urlManager->getAppUrlBase() . '/tracking/open/1_' . $idMail . '_' . $idContact;
		$md5_2 = md5($src . '_Sigmamovil_Rules');
		
		if ($md5 == $md5_2) {
			$trackingObj = new TrackingObject();
			$trackingObj->updateTracking($idMail, $idContact);
			/**
			 * =================================================================== *
			 */

			$img = $urlManager->getAppUrlBase() . '/images/tracking.gif';

			$this->response->setHeader("Content-Type", "image/gif");

			$this->view->disable();
			return $this->response->setContent($img);
		}
		else {
			$this->response->redirect('error');
		}
	}
}