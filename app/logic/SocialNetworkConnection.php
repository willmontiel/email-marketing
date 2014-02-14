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

	public function findAllFacebookAccounts()
	{
		$socials = array();
		$arraysocials = Socialnetwork::find(array(
							"conditions" => "idUser = ?1 AND type = 'Facebook'",
							"bind" => array(1 => $this->user->idUser)
						));
		foreach ($arraysocials as $soc) {
			$obj = new stdClass();
			$obj->idSocialnetwork = $soc->idSocialnetwork;
			$obj->name = $soc->name;
			$socials[] = $obj;
		}
		return $socials;
	}
	
	public function findAllTwitterAccounts()
	{
		$socials = array();
		$arraysocials = Socialnetwork::find(array(
							"conditions" => "idUser = ?1 AND type = 'Twitter'",
							"bind" => array(1 => $this->user->idUser)
						));
		foreach ($arraysocials as $soc) {
			$obj = new stdClass();
			$obj->idSocialnetwork = $soc->idSocialnetwork;
			$obj->name = $soc->name;
			$socials[] = $obj;
		}
		return $socials;
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
	
	public function getFbUrlLogIn()
	{
		return $this->facebook->getLoginUrl(array('scope' => 'manage_pages, publish_stream'));
	}
	
	public function getTwUrlLogIn($idMail = '')
	{
//		$tmp_cds = $this->twitter->getRequestToken($this->urlObj->getBaseUri(TRUE). '/mail/setup/' . $idMail);
		$tmp_cds = $this->twitter->getRequestToken('http://192.168.18.165/' . $this->urlObj->getBaseUri(FALSE) . '/mail/setup/' . $idMail);
		$url = $this->twitter->getAuthorizeURL($tmp_cds);
		return $url;
	}
	
	public function saveFacebookUser()
	{
		$userid = $this->facebook->getUser();
		$this->logger->log($userid);
		if($userid) {
			$existing = Socialnetwork::findFirstByUserid($userid);
			
			if(!$existing) {
				$user = $this->facebook->api('/'.$userid);

				$newuser = new Socialnetwork();
				$newuser->idUser = $this->user->idUser;
				$newuser->userid = $userid;
				$newuser->token = $this->facebook->getAccessToken();
				$newuser->name = $user['name'];
				$newuser->type = 'Facebook';
				$newuser->category = 'Profile';
				$newuser->save();

				$accounts = $this->facebook->api('/'.$userid.'/accounts');
				foreach ($accounts['data'] as $account) {
					$newaccount = new Socialnetwork();
					$newaccount->idUser = $this->user->idUser;
					$newaccount->userid = $account['id'];
					$newaccount->token = $account['access_token'];
					$newaccount->name = $account['name'];
					$newaccount->type = 'Facebook';
					$newaccount->category = 'Fan Page';
					$newaccount->save();
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
				$newuser = new Socialnetwork();
				$newuser->idUser = $this->user->idUser;
				$newuser->userid = $token_cds['oauth_token'];
				$newuser->token = $token_cds['oauth_token_secret'];
				$newuser->name = $token_cds['screen_name'];
				$newuser->type = 'Twitter';
				$newuser->category = 'Profile';
				if(!$newuser->save()) {
					$this->logger->log('No se pudo crear el usuario id = '. $this->user->idUser . ' token = ' . $token_cds['oauth_token'] . ' secret = ' . $token_cds['oauth_token_secret'] . ' name = ' . $token_cds['name']);
				}
			}
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
		$phql = "SELECT userid, token FROM Socialnetwork where idSocialnetwork in ( ";
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
				"link" => "http://stage.sigmamovil.com/", //$this->urlObj->getBaseUri(TRUE) . '/',
				"picture" => "http://stage.sigmamovil.com/images/sigma_envelope.png",
				"name" => $fbcontent->title,
				"caption" => "www.stage.sigmamovil.com",
				"description" => $fbcontent->description
			  );

			  try {
				  $this->facebook->api('/'.$userid.'/feed', 'POST', $params);
				  $this->logger->log('Successfully posted to Facebook');
			  } catch(Exception $e) {
				  $this->logger->log('No publico');
				  $this->logger->log($e->getMessage());
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