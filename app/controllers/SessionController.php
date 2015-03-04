<?php
class SessionController extends ControllerBase
{
    
    public function signinAction()
    {
        
    }
    

	public function logoutAction()
    {
        $this->session->remove("user-name");
        $this->session->destroy();
		$this->traceSuccess("logout: {$this->user->idUser}/{$this->user->username}");
        $this->response->redirect('session/signin');
    }
	
    public function loginAction()
    {
		$msg = "Usuario o contraseña incorrecta!";
		
		if ($this->request->isPost()) {
//			if ($this->security->checkToken()) {
				$login = $this->request->getPost("username");
				$password = $this->request->getPost("pass");

				$user = User::findFirst(array(
					"username = ?0",
					"bind" => array($login)
				));

				
				if ($user && $this->security2->checkHash($password, $user->password)) {
					$account = Account::findFirstByIdAccount($user->idAccount);

					if ($account && $account->status == 1) {
						$this->session->set('userid', $user->idUser);
						$this->session->set('authenticated', true);

						$this->user = $user;
						$this->traceSuccess("Access allowed username: {$this->user->idUser}/{$this->user->username}");

						return $this->response->redirect("");
					}
					else {
						$msg = "La cuenta ha sido bloqueada, por favor contacte al administrador";
						$this->traceFail("Access denied because the account is disable, username: {$login}, password: [{$password}]");
					}
				}
				else {
					$this->traceFail("Access denied username: {$login}, password: [{$password}]");
				}
//			}
        }
		
		$this->flashSession->error($msg);
		$this->response->redirect('session/signin');
	}
	
    public function recoverpassAction()
	{
		if ($this->request->isPost()) {
			$email = $this->request->getPost("email");
			
			$user = User::findFirst(array(
				'conditions' => 'email = ?1',
				'bind' => array(1 => $email)
			));
			
			if ($user) {
				$cod = uniqid();
				$urlManager = $urlManager = Phalcon\DI::getDefault()->get('urlManager');
				$url = $urlManager->getBaseUri(true);
				$url .= 'session/reset/' . $cod;
				
				$recoverObj = new Tmprecoverpass();
				$recoverObj->idTmpRecoverPass = $cod;
				$recoverObj->idUser = $user->idUser;
				$recoverObj->url = $url;
				$recoverObj->date = time();
				
				if (!$recoverObj->save()) {
					$this->logger->log('Error while saving tmpurl');
					foreach ($recoverObj->getMessages() as $msg) {
						$this->logger->log('Msg: ' . $msg);
					}
					$this->logger->log("user: {$user->idUser}/{$user->username}");
					$this->traceFail("Recover pass failed user with email '{$email}' error 500");
					$this->flashSession->error('Ha ocurrido un error contacte al administrador');
				}
				else {
					$link = '<a href="' . $url . '" style="text-decoration: underline;">Click aqui</a>';
					try {
						$this->logger->log($link);
						$message = new AdministrativeMessages();
						$message->createRecoverpassMessage($user->email, $link);
						$message->sendMessage();
					}
					catch (Exception $e) {
						$this->logger->log('Exception: ' . $e->getMessage());
						$this->logger->log("user: {$user->idUser}/{$user->username}");
						$this->traceFail("Recover pass failed user with email '{$email}' error 500");
						$this->flashSession->error('Ha ocurrido un error contacte al administrador');
					}
					$this->traceSuccess("Send email for recover pass user: {$user->idUser}/{$user->username}");
				}
			}
			else {
				$this->traceFail("User with email '{$email}'  do not exists");
			}
			$this->flashSession->success('Se ha enviado un correo electronico con instrucciones para recuperar la contraseña');
			return $this->response->redirect('session/signin');
		}
	}
	
	public function resetAction($unique)
	{
		$url = Tmprecoverpass::findFirst(array(
			'conditions' => 'idTmpRecoverPass = ?1',
			'bind' => array(1 => $unique)
		));
		
		$time = strtotime("-30 minutes");
		
		if ($url && ($url->date <= $time || $url->date >= $time)) {
			$this->session->set('idUser', $url->idUser); 
			$this->view->setVar('uniq', $unique);
		}
		else {
			$this->traceFail("Reset pass failed because the link is invalid, do not exists or is expired id: {$unique}");
			return $this->response->redirect('error/link');
		}
	}
	
	public function setnewpassAction()
	{
		if ($this->request->isPost()) {
		
			$uniq = $this->request->getPost("uniq");
	
			$url = Tmprecoverpass::findFirst(array(
				'conditions' => 'idTmpRecoverPass = ?1',
				'bind' => array(1 => $uniq)
			));
			
			$time = strtotime("-30 minutes");
			
			if ($url && $url->date >= $time) {
				$pass = $this->request->getPost("pass");
				$pass2 = $this->request->getPost("pass2");

				if (empty($pass)||empty($pass2)){
					$this->flashSession->error("Ha enviado campos vacíos, por favor verifique la información");
					$this->dispatcher->forward(array(
						"controller" => "session",
						"action" => "reset",
						"params" => array($uniq)
					));
				}
				else if (strlen($pass) < 8 || strlen($pass) > 40) {
					$this->flashSession->error("La contraseña es muy corta o muy larga, esta debe tener mínimo 8 y máximo 40 caracteres, por favor verifique la información");
					$this->dispatcher->forward(array(
						"controller" => "session",
						"action" => "reset",
						"params" => array($uniq)
					));
				}	
				else if ($pass !== $pass2) {
					$this->flashSession->error("Las contraseñas no coinciden, por favor verifique la información");
					$this->dispatcher->forward(array(
						"controller" => "session",
						"action" => "reset",
						"params" => array($uniq)
					));
				}
				else {
					$idUser = $this->session->get('idUser');
					
					$user = User::findFirst(array(
						'conditions' => 'idUser = ?1',
						'bind' => array(1 => $idUser)
					));
					
					if ($user) {
						$user->password = $this->security2->hash($pass);

						if (!$user->save()) {
							$this->flashSession->notice('Ha ocurrido un error, contacte con el administrador');
							foreach ($user->getMessages() as $msg) {
								$this->logger->log('Error while recovering user password' . $msg);
								$this->logger->log("User {$user->idUser}/{$user->username}");
								$this->traceFail("Reset pass failed error 500");
								$this->flashSession->error('Ha ocurrido un error contacte al administrador');
							}
						}
						else {
							$idUser = $this->session->remove('idUser');
							$url->delete();
							$this->flashSession->notice('Se ha actualizado el usuario exitosamente');
							$this->traceSuccess("Recover and reset pass user: {$user->idUser}/{$user->username}");
							return $this->response->redirect('index');
						}
					}
					else {
						$this->traceFail("Reset pass failed because user do not exists");
						return $this->response->redirect('error/link');
					}
				}
			}
			else {
				$this->traceFail("Reset pass failed because the link is invalid, do not exists or is expired id: {$uniq}");
				return $this->response->redirect('error/link');
			}
		}
	}
	
	public function loginlikethisuserAction($idUser)
	{
		$user = User::findFirst(array(
			'conditions' => 'idUser = ?1',
			'bind' => array(1 => $idUser)
		));

		if (!$user) {
			$this->flashSession->error("No se ha podido ingresar como el usuario, porque este no existe");
			return $this->response->redirect('account/index');
		}

		$this->session->set('userid', $user->idUser);
		$this->session->set('authenticated', true);

		$this->user = $user;
		$this->user->userrole = 'ROLE_SUDO';
		
		$uefective = $this->session->get('userefective');
		$this->traceSuccess("Login by sudo: {$uefective->username} / {$uefective->idUser}, in account {$this->user->account->idAccount} with user {$this->user->username} / {$this->user->idUser}");
		return $this->response->redirect("");
	}
	
	public function logoutfromthisaccountAction()
	{
		$uefective = $this->session->get('userefective');
		$olduser = $this->user;
		$oldAccount = $this->user->account;
		
		if (isset($uefective)) {
			$this->session->set('userid', $uefective->idUser);
			$this->session->set('authenticated', true);

			$this->user = $uefective;

			$this->session->remove('userefective');
			$this->traceSuccess("Logout by sudo: {$uefective->username} / {$uefective->idUser}, in account {$oldAccount->idAccount} with user {$olduser->username} / {$olduser->idUser}");
			return $this->response->redirect("");
		}
		else {
			return $this->response->redirect("error/unauthorized");
		}
		
	}
}