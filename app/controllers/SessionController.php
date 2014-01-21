<?php
class SessionController extends \Phalcon\Mvc\Controller
{
    
    public function signinAction()
    {
        
    }
    

	public function logoutAction()
    {
        $this->session->remove("user-name");
        $this->session->destroy();
		
        $this->response->redirect("session/signin");
    }
	
    public function loginAction()
    {
		if ($this->request->isPost()) {
			if ($this->security->checkToken()) {
				$login = $this->request->getPost("username");
				$password = $this->request->getPost("pass");

				$user = User::findFirst(array(
					"username = ?0",
					"bind" => array($login)
				));


				if ($user && $this->security2->checkHash($password, $user->password)) {
					$this->session->set('userid', $user->idUser);
					$this->session->set('authenticated', true);
					
					return $this->response->redirect("");

				}
      
			}
         
        }
        
		$this->flashSession->error('Usuario o contraseña incorrecta!');
		$this->view->disable();
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
				$url = $this->url->get('session/validaterequest');
				$url .= '/' . $cod;
				
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
				}
				else {
					$link = '<a href="http://localhost';
					$link .= $url;
					$link .= '" style="text-decoration: underline;">';
					$link .= 'Click aqui</a>';
					
					try {
						$message = new AdministrativeMessages();
						$message->createRecoverpassMessage($link, $user->email);
						$message->sendMessage();
					}
					catch (InvalidArgumentException $e) {
						$this->logger->log('Error: ' . $e->getMessage());
					}
				}
				$this->flashSession->success('Se ha enviado un correo electronico con instrucciones para recuperar la contraseña');
			}
		}
	}
	
	public function resetAction($unique)
	{
		$this->logger->log('0');
		$url = Tmprecoverpass::findFirst(array(
			'conditions' => 'idTmpRecoverPass = ?1',
			'bind' => array(1 => $unique)
		));
		
		$time = strtotime("-30 minutes");
		$this->logger->log('1');
		if ($url && ($url->date <= $time || $url->date >= $time)) {
			$this->logger->log('2');
			$this->session->set('idUser', $url->idUser); 
			$this->view->setVar('uniq', $unique);
		}
		else {
			$this->logger->log('3');
			return $this->response->redirect('error');
		}
	}
	
	public function setnewpassAction()
	{
		$this->logger->log('0');
		if ($this->request->isPost()) {
			$uniq = $this->request->getPost("uniq");
			$this->logger->log('1');
			$url = Tmprecoverpass::findFirst(array(
				'conditions' => 'idTmpRecoverPass = ?1',
				'bind' => array(1 => $uniq)
			));
			
			$time = strtotime("-30 minutes");
			
			if ($url && ($url->date <= $time || $url->date >= $time)) {
				$this->logger->log('2');
				$pass = $this->request->getPost("pass");
				$pass2 = $this->request->getPost("pass2");

				if (empty($pass)||empty($pass2)){
					$this->logger->log('3');
					$this->flashSession->error("Ha enviado campos vacíos, por favor verifique la información");
					$this->dispatcher->forward(array(
						"controller" => "session",
						"action" => "validaterequest",
						"params" => array($uniq)
					));
				}
				else if (strlen($pass) < 8 || strlen($pass) > 40) {
					$this->logger->log('4');
					$this->flashSession->error("La contraseña es muy corta o muy larga, esta debe tener mínimo 8 y máximo 40 caracteres, por favor verifique la información");
					$this->dispatcher->forward(array(
						"controller" => "session",
						"action" => "validaterequest",
						"params" => array($uniq)
					));
				}	
				else if ($pass !== $pass2) {
					$this->logger->log('5');
					$this->flashSession->error("Las contraseñas no coinciden, por favor verifique la información");
					$this->dispatcher->forward(array(
						"controller" => "session",
						"action" => "validaterequest",
						"params" => array($uniq)
					));
				}
				else {
					$this->logger->log('6');
					
					$idUser = $this->session->get('idUser');
					
					$user = User::findFirst(array(
						'conditions' => 'idUser = ?1',
						'bind' => array(1 => $idUser)
					));
					
					if ($user) {
						$this->logger->log('Hash: ' . $this->security2->hash($pass));
						$this->logger->log("There's user");
						$user->password = $this->security2->hash($pass);

						if (!$user->save()) {
							$this->logger->log('7');
							$this->flashSession->notice('Ha ocurrido un error, contacte con el administrador');
							foreach ($user->getMessages() as $msg) {
								$this->logger->log('Error while recovering user password' . $msg);
							}
						}
						else {
							$this->logger->log('8');
							$idUser = $this->session->remove('idUser');
							$url->delete();
							$this->flashSession->notice('Se ha actualizado el usuario exitosamente');
							return $this->response->redirect('index');
						}
					}
					else {
						$this->logger->log('9');
						return $this->response->redirect('error');
					}
				}
			}
			else {
				$this->logger->log('10');
				return $this->response->redirect('error');
			}
		}
	}
}