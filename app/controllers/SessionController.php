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
				$this->session->set('userid', $user->idUser);
				
				$cod = uniqid();
				$url = $this->url->get('session/edituseraccount');
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
					$message = new AdministrativeMessages();
					$message->createRecoverpassMessage($url, $user->email);
					$message->sendMessage();
				}
				$this->flashSession->success('Se le ha enviado un correo electronico con instrucciones');
			}
		}
	}
	
	public function edituseraccountAction($uniq = null)
	{
		$this->logger->log('Uniq' . $uniq);
		
		$this->view->setVar('uniq', $uniq);
		if ($this->request->isPost()) {
			$url = Tmprecoverpass::findFirst(array(
				'conditions' => 'idTmpRecoverPass = ?1',
				'bind' => array(1 => $uniq)
			));
			
			$time = strtotime("-30 minutes");
			
			if ($url && ($url->date <= $time || $url->date >= $time)) {
				$pass = $this->request->getPost("pass");
				$pass2 = $this->request->getPost("pass2");

				if (empty($pass)||empty($pass2)){
					$this->flashSession->error("Ha enviado campos vacíos, por favor verifique la información");
				}
				else if (strlen($pass) < 8 || strlen($pass) > 40) {
					$this->flashSession->error("La contraseña es muy corta o muy larga, esta debe tener mínimo 8 y máximo 40 caracteres, por favor verifique la información");
				}	
				else if ($pass !== $pass2) {
					$this->flashSession->error("Las contraseñas no coinciden, por favor verifique la información");
				}
				else {
					$idUser = $this->session->get('userid');

					$user = User::findFirst(array(
						'conditions' => 'idUser = ?1',
						'bind' => array(1 => $idUser)
					));

					$user->password = $this->security2->hash($pass);

					if (!$user->save()) {
						$this->flashSession->notice('Ha ocurrido un error, contacte con el administrador');
						foreach ($user->getMessages() as $msg) {
							$this->logger->log('Error while recovering user password' . $msg);
						}
					}
					else {
						$idUser = $this->session->remove('userid');
						
						$url->delete();
						
						$this->flashSession->notice('Se ha actualizado el usuario exitosamente');
						return $this->response->redirect('index');
					}
				}
			}
			else {
				return $this->response->redirect('error');
			}
		}
	}
}