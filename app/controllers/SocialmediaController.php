<?php
class SocialmediaController extends ControllerBase
{
	public function indexAction()
	{
		$socialnet = new SocialNetworkConnection($this->logger);
		$socialnet->setAccount($this->user->account);
		$socialnet->setFacebookConnection($this->fbapp->iduser, $this->fbapp->token);
		$socialnet->setTwitterConnection($this->twapp->iduser, $this->twapp->token);
		$fbloginUrl = $socialnet->getFbUrlLogIn('/socialmedia/new/');
		$twloginUrl = $socialnet->getTwUrlLogIn('/socialmedia/new/');
		$accounts = $socialnet->findAllAccountsByUser();
		$this->view->setVar('accounts', $accounts);
		$this->view->setVar('fbloginUrl', $fbloginUrl);
		$this->view->setVar('twloginUrl', $twloginUrl);
	}
	
	public function newAction()
	{
		$this->view->disable();
		$socialnet = new SocialNetworkConnection($this->logger);
		$socialnet->setAccount($this->user->account);
		$socialnet->setFacebookConnection($this->fbapp->iduser, $this->fbapp->token);
		$socialnet->setTwitterConnection($this->twapp->iduser, $this->twapp->token);
		if(isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier'])) {
			$socialnet->setTwitterConnection($this->twapp->iduser, $this->twapp->token, $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
			$socialnet->saveTwitterUser($_REQUEST['oauth_verifier']);
		}
		else {
			$socialnet->saveFacebookUser();
		}
		return $this->response->redirect("socialmedia");
	}
	
	public function createAction($idMail = '')
	{
		$this->view->disable();
		$socialnet = new SocialNetworkConnection($this->logger);
		$socialnet->setAccount($this->user->account);
		$socialnet->setFacebookConnection($this->fbapp->iduser, $this->fbapp->token);
		$socialnet->setTwitterConnection($this->twapp->iduser, $this->twapp->token);
		if(isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier'])) {
			$socialnet->setTwitterConnection($this->twapp->iduser, $this->twapp->token, $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
			$socialnet->saveTwitterUser($_REQUEST['oauth_verifier']);
		}
		else {
			$socialnet->saveFacebookUser();
		}
		return $this->response->redirect("mail/setup/" . $idMail);
	}
	
	public function deleteAction($idSocialnetwork)
	{
		$socialaccount = Socialnetwork::findFirst(array(
			'conditions' => 'idSocialnetwork = ?1',
			'bind' => array(1 => $idSocialnetwork)
		));
		if ($socialaccount) {
			if (!$socialaccount->delete()) {
				foreach ($socialaccount->getMessages() as $msg) {
					$this->logger->log('Error deleting social account: ' . $msg);
				}
				$this->logger-log('Ha ocurrido un error mientras se eliminaba la cuenta social');
//				$this->flashSession->error('Ha ocurrido un error mientras se eliminaba la cuenta social');
				return $this->response->redirect('socialmedia');
			}
			$this->logger-log('Se ha eliminado la cuenta social exitosamente');
//			$this->flashSession->warning('Se ha eliminado la cuenta social exitosamente');
			return $this->response->redirect('socialmedia');
		}
		else {
			$this->logger-log('La cuenta social que desea eliminar no existe o ya ha sido elminada, por favor verifique la información');
//			$this->flashSession->warning('La cuenta social que desea eliminar no existe o ya ha sido elminada, por favor verifique la información');
			return $this->response->redirect('socialmedia');
		}
	}
	
	public function shareAction($parameters)
	{
		$idenfifiers = explode("-", $parameters);
		list($idMail, $idContact, $md5, $socialtype) = $idenfifiers;
		$src = $this->urlManager->getBaseUri(true) . 'socialmedia/share/' . $idMail . '-' . $idContact;
		$md5_2 = md5($src . '-Sigmamovil_Rules');
		if ($md5 == $md5_2) {
			$url_1 = $this->urlManager->getBaseUri(true) . 'webversion/share/1-' . $idMail . '-' . $idContact . '-' . $socialtype;
			$md5_3 = md5($url_1 . '-Sigmamovil_Rules');
			$url_2 = $url_1 . '-' . $md5_3;
			$url = urlencode($url_2);
			
			$trackingObj = new TrackingObject();
			$trackingObj->setSendIdentification($idMail, $idContact);
			$mxc = $trackingObj->getMxC();

			$instance = \EmailMarketing\SocialTracking\TrackingSocialAbstract::createInstanceTracking($socialtype);
			$instance->setMxc($mxc);
			$instance->trackShare();
			$instance->save();
			
			switch ($socialtype) {
				case 'facebook':
						$urlFinal = 'https://facebook.com/sharer/sharer.php?u=' . $url . '&display=popup';
					break;
				case 'twitter':
						$urlFinal = 'https://twitter.com/intent/tweet?text=' . $url . '&source=webclient';
					break;
				case 'linkedin':
						$urlFinal = 'https://linkedin.com/cws/share?url=' . $url;
					break;
				case 'googleplus':
						$urlFinal = 'https://plus.google.com/share?url=' . $url;
					break;
			}
			return $this->response->redirect($urlFinal, true);
		}
		else {
			return $this->response->redirect('error/link');
		}
	}
}