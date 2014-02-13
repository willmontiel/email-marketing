<?php
class SocialNetworkConnection
{
	public $facebook = null;
	public $user;
	
	function __construct($logger = null) {
		$this->logger = $logger;
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
	
	public function getFbUrlLogIn()
	{
		return $this->facebook->getLoginUrl(array('scope' => 'manage_pages, publish_stream'));
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
	
	public function postOnFacebook($idsobj, Socialmail $desc, Mail $mail)
	{
		$facebook_ids = $idsobj->facebook;
		$first = TRUE;
		$phql = "SELECT userid, token FROM Socialnetwork where idSocialnetwork in ( ";
		foreach ($facebook_ids as $id) {
			if(!$first) {
				$phql.= ', ';
			}
			$phql.= $id;
			$first = FALSE;
		}
		$phql.= ' )';

		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		$ids_tokens = $mm->executeQuery($phql);
		foreach ($ids_tokens as $id_token){
			$userid = $id_token->userid;
			$access_token = $id_token->token;
			$params = array(
				"access_token" => $access_token,
				"message" => $desc->fbdescription,
				"link" => "http://stage.sigmamovil.com/",
				"picture" => "http://stage.sigmamovil.com/images/sigma_envelope.png",
				"name" => $mail->name,
				"caption" => "www.stage.sigmamovil.com",
				"description" => "Correo enviado desde la plataforma de Sigma Movil"
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
}