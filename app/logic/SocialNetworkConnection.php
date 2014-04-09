<?php
class SocialNetworkConnection
{
	public $facebook = null;
	public $twitter = null;
	public $user;
	
	const IMG_SN_WIDTH = 650;
	const IMG_SN_HEIGHT = 277;
	
	function __construct($logger = null) {
		$this->logger = $logger;
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
		$this->assetsrv = Phalcon\DI::getDefault()->get('asset');
	}

	public function setAccount(Account $account)
	{
		$this->account = $account;
	}

	public function findAllAccountsByUser()
	{
		$socials = Socialnetwork::find(array(
							"conditions" => "idAccount = ?1",
							"bind" => array(1 => $this->account->idAccount),
							"order" => "type DESC, category"
						));
		return $socials;
	}
	
	public function findAllFacebookAccountsByUser()
	{
		$socials = Socialnetwork::find(array(
							"conditions" => "idAccount = ?1 AND type = 'Facebook' AND status = 'Activated'",
							"bind" => array(1 => $this->account->idAccount)
						));
		return $socials;
	}
	
	public function findAllTwitterAccountsByUser()
	{
		$socials = Socialnetwork::find(array(
							"conditions" => "idAccount = ?1 AND type = 'Twitter'",
							"bind" => array(1 => $this->account->idAccount)
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
		if($userid) {
			try{
			$existing = Socialnetwork::findFirst(array(
							"conditions" => "userid = ?1 AND idAccount = ?2",
							"bind" => array(1 => $userid, 2 => $this->account->idAccount)
			));
			$user = $this->facebook->api('/'.$userid);
			if(!$existing) {
				$this->saveNewAccount($userid, $this->facebook->getAccessToken(), $user['name'], 'Facebook', 'Profile');
			}
			else if($existing->status === 'Deactivated'){
				$this->activateAccount($existing, $existing->userid, $this->facebook->getAccessToken(), $user['name']);
			}
			
			$accounts = $this->facebook->api('/'.$userid.'/accounts');
			foreach ($accounts['data'] as $account) {
				$fanpage = Socialnetwork::findFirst(array(
								"conditions" => "userid = ?1 AND idAccount = ?2",
								"bind" => array(1 => $account['id'], 2 => $this->account->idAccount)
				));
				if(!$fanpage) {
					$this->saveNewAccount($account['id'], $account['access_token'], $account['name'], 'Facebook', 'Fan Page');
				}
				else if($fanpage->status === 'Deactivated'){
					$this->activateAccount($fanpage, $fanpage->userid, $account['access_token'], $account['name']);
				}
			}
			} 
			catch (InvalidArgumentException $e) {
				$this->logger->log('Exception: [' . $e . ']');
			}
			catch (Exception $e) {
				$this->logger->log('Exception: [' . $e . ']');
			}
		}
	}
	
	public function saveTwitterUser($oauth_verifier)
	{
		$token_cds = $this->twitter->getAccessToken($oauth_verifier);
		if($token_cds['oauth_token']) {
			$existing = Socialnetwork::findFirst(array(
							"conditions" => "name = ?1 AND idAccount = ?2",
							"bind" => array(1 => $token_cds['screen_name'], 2 => $this->account->idAccount)
			));
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
		$newsaccount->idAccount = $this->account->idAccount;
		$newsaccount->userid = $userid;
		$newsaccount->token = $token;
		$newsaccount->name = $name;
		$newsaccount->type = $type;
		$newsaccount->category = $category;
		$newsaccount->status = 'Activated';
		if(!$newsaccount->save()) {
			$this->logger->log('Error al crear la cuenta de ' . $category);
			throw new InvalidArgumentException('Error al crear la cuenta de ' . $category);
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
			throw new InvalidArgumentException('Error al actualizar la cuenta de ' . $saccount->category);
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
	
	public function saveFacebookDescription($fbtitle = '', $fbdescription = '', $fbmsg = '', $fbimage = '')
	{
		$fbcontent = new stdClass();
		$fbcontent->title = $fbtitle;
		$fbcontent->description = $fbdescription;
		$fbcontent->message = $fbmsg;
		$fbcontent->image = $fbimage;
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
		
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		$action = 'webversion/show';
		$parameters = array(1, $mail->idMail, '1329266');
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		// Ajustar Tamaño de Imagen
		$imgname = $this->setImageToIdealSize($fbcontent->image);
		
		if (count($ids_tokens) > 0) {
			foreach ($ids_tokens as $id_token){
				$userid = $id_token->userid;
				$access_token = $id_token->token;
				$params = array(
					"access_token" => $access_token,
					"message" => $fbcontent->message,
					"link" => $link, //$this->urlObj->getBaseUri(TRUE) "http://stage.sigmamovil.com/",
					"picture" => $this->urlObj->getAppUrlAsset(TRUE) . '/' . $this->account->idAccount . '/sn/' . $imgname, //"http://stage.sigmamovil.com/images/sigma_envelope.png",
					"name" => $fbcontent->title,
					"caption" => $link, //$this->urlObj->getBaseUri(TRUE) "www.stage.sigmamovil.com/",
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
		else {
			$this->logger->log('There are no social facebook accounts to post');
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
		
//		$url = $this->urlObj->getBaseUri(TRUE) . 'webversion/show/1-' . $mail->idMail . '-25';
//		$md5 = md5($url . '-Sigmamovil_Rules');
//		$link = $url . '-' . $md5; 
		
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		$action = 'webversion/show';
		$parameters = array(1, $mail->idMail, '1329266');
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		if (count($ids_tokens) > 0) {
			foreach ($ids_tokens as $id_token){
				$oauth_token = $id_token->userid;
				$oauth_secret = $id_token->token;
				$this->setTwitterConnection($appids['id'], $appids['secret'], $oauth_token, $oauth_secret);
				try {
					$account = $this->twitter->get('account/verify_credentials');
					$post = $this->twitter->post('statuses/update', array('status' => $twcontent->message . ' ' . $link));
					$this->logger->log('Successfully posted in Twitter');
				} catch(Exception $e) {
					$this->logger->log('No Tweet');
					$this->logger->log($account);
					$this->logger->log($post);
					$this->logger->log($e->getMessage());
				}
			}
		}
		else {
			$this->logger->log('There are no social twitter accounts to post');
		}
	}
	
	public function setImageToIdealSize($imagepath)
	{
		$asset = Asset::findFirst(array(
			'conditions' => 'idAsset = ?1',
			'bind' => array(1 => basename($imagepath))
		));

		$imgObj = new ImageObject();
		$imgObj->createImageFromFile($this->assetsrv->dir . $this->account->idAccount . '/images/' . $asset->idAsset . '.' . pathinfo($asset->fileName, PATHINFO_EXTENSION), $asset->fileName);
		$imgObj->resizeImage(self::IMG_SN_WIDTH ,  self::IMG_SN_HEIGHT);
		
		$dir = $this->assetsrv->dir . $this->account->idAccount . '/sn/' ;

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
		
		$imgname = basename($imagepath) . '.jpg';
		$dir .= $imgname;
		
		$imgObj->saveImage('jpg', $dir);

		return $imgname;
	}
}