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
				
				$recoverObj = new Tmprecoverpass();
				
				$recoverObj->idTmpRecoverPass = $cod;
				$recoverObj->idUser = $user->idUser;
				$recoverObj->url = $url . '/' . $cod;
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
	
	public function edituseraccountAction()
	{
		if ($this->request->isPost()) {
			
		}
	}
}