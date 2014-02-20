<?php
class SocialNetworkConnection
{
	public $facebook = null;
	public $twitter = null;
	public $user;
	
	function __construct($logger = null) {
		$this->logger = $logger;
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function findAllAccountsByUser()
	{
		$socials = Socialnetwork::find(array(
							"conditions" => "idUser = ?1",
							"bind" => array(1 => $this->user->idUser),
							"order" => "type DESC, category"
						));
		return $socials;
	}
	
	public function findAllFacebookAccountsByUser()
	{
		$socials = Socialnetwork::find(array(
							"conditions" => "idUser = ?1 AND type = 'Facebook' AND status = 'Activated'",
							"bind" => array(1 => $this->user->idUser)
						));
		return $socials;
	}
	
	public function findAllTwitterAccountsByUser()
	{
		$socials = Socialnetwork::find(array(
							"conditions" => "idUser = ?1 AND type = 'Twitter'",
							"bind" => array(1 => $this->user->idUser)
						));
		return $socials;
	}
	
	public function getSocialIdNameArray($socials)
	{
		$arraysocials = array();
		foreach ($socials as $social) {
			$obj = new stdClass();
			$obj->idSocialnetwork = $social->idSocialnetwork;
			$obj->name = $social->name;
			$obj->category = $social->category;
			$arraysocials[] = $obj;
		}
		return $arraysocials;
	}
	
	public function setFacebookConnection($id, $secret)
	{
		$this->facebook = new Facebook(array(
		  'appId'  => $id,
		  'secret' => $secret,
		));
	}
	
	public function setTwitterConnection($id, $secret, $oauth_token = null, $oauth_secret = null)
	{
		if($oauth_token != null && $oauth_secret != null ) {
			$this->twitter = new TwitterOAuth($id, $secret, $oauth_token, $oauth_secret);
		}
		else {
			$this->twitter = new TwitterOAuth($id, $secret);
		}
	}
	
	public function getFbUrlLogIn($redirect = false)
	{
		if($redirect) {
			$url = $this->facebook->getLoginUrl(array('scope' => 'manage_pages, publish_stream', 'redirect_uri' => $this->urlObj->getBaseUri(TRUE). $redirect));
		}
		else {
			$url = $this->facebook->getLoginUrl(array('scope' => 'manage_pages, publish_stream'));
		}
		return $url;
	}
	
	public function getTwUrlLogIn($redirect)
	{
		do {
			$tmp_cds = $this->twitter->getRequestToken($this->urlObj->getBaseUri(TRUE). $redirect);
		} while (!isset($tmp_cds['oauth_token']));
		$url = $this->twitter->getAuthorizeURL($tmp_cds);
		return $url;
	}
	
	public function saveFacebookUser()
	{
		$userid = $this->facebook->getUser();
		$this->logger->log($userid);
		if($userid) {
			$existing = Socialnetwork::findFirstByUserid($userid);
			$user = $this->facebook->api('/'.$userid);
			if(!$existing) {
				$this->saveNewAccount($userid, $this->facebook->getAccessToken(), $user['name'], 'Facebook', 'Profile');
			}
			else if($existing->status === 'Deactivated'){
				$this->activateAccount($existing, $existing->userid, $this->facebook->getAccessToken(), $user['name']);
			}
			
			$accounts = $this->facebook->api('/'.$userid.'/accounts');
			foreach ($accounts['data'] as $account) {
				$fanpage = Socialnetwork::findFirstByUserid($account['id']);
				if(!$fanpage) {
					$this->saveNewAccount($account['id'], $account['access_token'], $account['name'], 'Facebook', 'Fan Page');
				}
				else if($fanpage->status === 'Deactivated'){
					$this->activateAccount($fanpage, $fanpage->userid, $account['access_token'], $account['name']);
				}
			}
		}
	}
	
	public function saveTwitterUser($oauth_verifier)
	{
		$token_cds = $this->twitter->getAccessToken($oauth_verifier);
		if($token_cds['oauth_token']) {
			$existing = Socialnetwork::findFirstByName($token_cds['screen_name']);
			if(!$existing) {
				$this->saveNewAccount($token_cds['oauth_token'], $token_cds['oauth_token_secret'], $token_cds['screen_name'], 'Twitter', 'Profile');
			}
			else if($existing->status === 'Deactivated'){
				$this->activateAccount($existing, $token_cds['oauth_token'], $token_cds['oauth_token_secret'], $token_cds['screen_name']);
			}
		}
	}
	
	protected function saveNewAccount($userid, $token, $name, $type, $category)
	{
		$newsaccount = new Socialnetwork();
		$newsaccount->idUser = $this->user->idUser;
		$newsaccount->userid = $userid;
		$newsaccount->token = $token;
		$newsaccount->name = $name;
		$newsaccount->type = $type;
		$newsaccount->category = $category;
		$newsaccount->status = 'Activated';
		if(!$newsaccount->save()) {
			$this->logger->log('Error al crear la cuenta de ' . $category);
		}
	}
	
	protected function activateAccount($saccount, $userid, $token, $name)
	{
		$saccount->userid = $userid;
		$saccount->token = $token;
		$saccount->name = $name;
		$saccount->status = 'Activated';
		if(!$saccount->save()) {
			$this->logger->log('Error al actualizar la cuenta de ' . $saccount->category);
		}
	}

	public function saveSocialsIds($fbaccounts, $twaccounts)
	{
		$socialsnetworks = new stdClass();
		if($fbaccounts) {
			$socialsnetworks->facebook = $fbaccounts;
		}
		if($twaccounts){
			$socialsnetworks->twitter = $twaccounts;
		}
		return json_encode($socialsnetworks);
	}
	
	public function saveFacebookDescription($fbtitle = '', $fbdescription = '', $fbmsg = '')
	{
		$fbcontent = new stdClass();
		$fbcontent->title = $fbtitle;
		$fbcontent->description = $fbdescription;
		$fbcontent->message = $fbmsg;
		return json_encode($fbcontent);
	}
	
	public function saveTwitterDescription($twmessage = '')
	{
		$twcontent = new stdClass();
		$twcontent->message = $twmessage;
		return json_encode($twcontent);
	}

	public function postOnFacebook($ids, Socialmail $desc, Mail $mail)
	{
		$first = TRUE;
		$phql = "SELECT idSocialnetwork, userid, token FROM Socialnetwork where idSocialnetwork in ( ";
		foreach ($ids as $id) {
			if(!$first) {
				$phql.= ', ';
			}
			$phql.= $id;
			$first = FALSE;
		}
		$phql.= ' )';
		$fbcontent = json_decode($desc->fbdescription);
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		$ids_tokens = $mm->executeQuery($phql);
		foreach ($ids_tokens as $id_token){
			$userid = $id_token->userid;
			$access_token = $id_token->token;
			$params = array(
				"access_token" => $access_token,
				"message" => $fbcontent->message,
				"link" => $this->urlObj->getBaseUri(TRUE), //"http://stage.sigmamovil.com/",
				"picture" => $this->urlObj->getBaseUri(TRUE) . 'images/sigma_envelope.png', //"http://stage.sigmamovil.com/images/sigma_envelope.png",
				"name" => $fbcontent->title,
				"caption" => $this->urlObj->getBaseUri(TRUE), //"www.stage.sigmamovil.com/",
				"description" => $fbcontent->description
			  );

			  try {
				  $this->facebook->api('/'.$userid.'/feed', 'POST', $params);
				  $this->logger->log('Successfully posted to Facebook');
			  } catch(Exception $e) {
				  $this->logger->log('No publico');
				  $this->logger->log($e->getMessage());
				  $socialnetwork = Socialnetwork::findFirstByIdSocialnetwork($id_token->idSocialnetwork);
				  $socialnetwork->status = 'Deactivated';
				  $socialnetwork->save();
			  }
		}
	}
	
	public function postOnTwitter($ids, Socialmail $desc, Mail $mail, $appids)
	{
		$first = TRUE;
		$phql = "SELECT userid, token FROM Socialnetwork where idSocialnetwork in ( ";
		foreach ($ids as $id) {
			if(!$first) {
				$phql.= ', ';
			}
			$phql.= $id;
			$first = FALSE;
		}
		$phql.= ' )';
		$twcontent = json_decode($desc->twdescription);
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		$ids_tokens = $mm->executeQuery($phql);
		foreach ($ids_tokens as $id_token){
			$oauth_token = $id_token->userid;
			$oauth_secret = $id_token->token;
			$this->setTwitterConnection($appids['id'], $appids['secret'], $oauth_token, $oauth_secret);
			try {
				$account = $this->twitter->get('account/verify_credentials');
				$post = $this->twitter->post('statuses/update', array('status' => $twcontent->message . ' stage.sigmamovil.com'));
				$this->logger->log('Successfully posted in Twitter');
			} catch(Exception $e) {
				$this->logger->log('No Tweet');
				$this->logger->log($account);
				$this->logger->log($post);
				$this->logger->log($e->getMessage());
			}
		}
	}
}