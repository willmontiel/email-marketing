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
	
	/**
	 * 
	 * @param string $parameters
	 * @return array
	 */
	public function shareAction($parameters)
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		try {
			$p = explode('-', $parameters);
			array_pop($p);
			$parts = $linkdecoder->decodeLink('socialmedia/share', implode('-',$p));
			list($v, $idMail, $idContact, $md5, $socialType) = $parts;
			
			$p = array(1, $idMail, $idContact, $socialType);
			$u = $linkdecoder->encodeLink('webversion/share', $p);
			$url = urlencode($u);
			
			$trackingObj = new TrackingObject();
			$trackingObj->setSendIdentification($idMail, $idContact);
			$mxc = $trackingObj->getMxC();

			$instance = \EmailMarketing\SocialTracking\TrackingSocialAbstract::createInstanceTracking($socialType);
			$instance->setMxc($mxc);
			$instance->trackShare();
			$instance->save();
			
			switch ($socialType) {
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
		catch (Exception $e) {
			$this->logger->log('Exception: ' . $e);
			return $this->response->redirect('error/link');
		}
	}
}