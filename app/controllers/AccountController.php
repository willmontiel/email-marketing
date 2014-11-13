<?php
class AccountController extends ControllerBase
{
	/**
	 * 
	 * Esta funcion se encarga de retornar en json la cantidad de contactos activos por cuenta, para
	 * visualizarlos desde la sección de listas de contactos, esto se hace con jquery que hará la peticion
	 * cada determinado tiempo para mentener actualizada la información al usuario.
	 */
	public function loadcontactsinfoAction()
	{
		$account = $this->user->account;
		$currentActiveContacts = $this->user->account->countActiveContactsInAccount();
		
		// Convirtiendo a Json
		$object = array();
		$object['activeContacts'] = $currentActiveContacts;
		$object['contactLimit'] = $account->contactLimit;
		$object['accountingMode'] = $account->accountingMode;
					
		return $this->setJsonResponse($object);
	
	}
	
	public function loadindicatorAction()
	{
		$account = $this->user->account;
		$value = 0;
		
		switch ($account->accountingMode) {
			case 'Envio':
				$value = $account->countTotalMessagesSent();
				break;
			
			case 'Contacto':
				$value = $account->countActiveContactsInAccount();
				break;
		}
		
		$object = array();
		$object['indicator'] = (empty($value) ? 0 : $value);
		$object['contactLimit'] = $account->contactLimit;
		$object['messageLimit'] = $account->messageLimit;
		$object['accountingMode'] = $account->accountingMode;
		$object['subscriptionMode'] = $account->subscriptionMode;
		
		return $this->setJsonResponse($object);
	}
	
	public function getscoreAction()
	{
		$account = $this->user->account;
		
		$score = Score::findFirst(array(
			'conditions' => 'idAccount = ?1',
			'bind' => array(1 => $account->idAccount)
		));
		
		$value = 0;
		
		if ($score) {
			$value = $score->score;
		}
		
		
		$object = array();
		$object['score'] = (empty($value) ? 0 : $value);
		
		return $this->setJsonResponse($object);
	}
	
	
	/**
	 * 
	 * Esta función se encarga de mostrar toda la información de las cuentas al super-administrador
	 */
	public function indexAction()
	{
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$builder = $this->modelsManager->createBuilder()
			->from('Account')
			->leftJoin('Score')
			->orderBy('Account.idAccount');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder" => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page" => $currentPage
		));
		
		$page = $paginator->getPaginate();
		$this->view->setVar("page", $page);
	}
	
	/*
	 * Esta función se utiliza para crear cuentas con su respectivo usuario y base de datos base, esta funcion es
	 * utilizada solo por los super-administradores  
	 */
	public function newAction()
    {
        $account = new Account();
        $form = new AccountForm($account);
		
        if ($this->request->isPost()) {
			
            $form->bind($this->request->getPost(), $account);
			
			$p = $form->getValue('prefix');
			$c = $form->getValue('companyName');
			$r = $form->getValue('sender');
			$status = $form->getValue('status');
			
			if (empty($r)) {
				$this->flashSession->error('No ha enviado un remitente válido, por favor verfique la información');
			}
			else {
				$remittents = explode(',', $r);
				$remittents = array_unique($remittents);
				
				$prefix = $this->validatePrefix($c, $p);
				$account->prefix = $prefix;
				$account->idUrlDomain = 1;
				$account->status = (empty($status) ? 0 : 1);
				
				$this->db->begin();

				if ($form->isValid() && $account->save()) {

					try {
						foreach ($remittents as $remittent) {
							$this->saveSender($account, $remittent);
						}
						$this->saveUser($account, $form, $prefix);
						$this->saveDbase($account);

						$this->db->commit();
						$this->traceSuccess('Create account: ' . $account->idAccount);

						$this->flashSession->success('Se ha creado la cuenta exitosamente');
						return $this->response->redirect("account/index");

					}
					catch (Exception $e) {
						$this->logger->log("Exception: {$e}");
						$this->db->rollback();
					}	
				}
				else {
					foreach ($account->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
			}
        }
       
		$this->view->newFormAccount = $form;
	} 
   
	protected function saveSender(Account $account, $remittent)
	{
		$parts = explode('/', $remittent);
		$email = trim(strtolower($parts[0]));
		$domain = explode('@', $email);
		$name = $parts[1];
		
		
		if (!\filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->flashSession->error("Hay direcciones de correo para remitente que no son válidas, por favor verifique la información");
			throw new InvalidArgumentException("Invalid email");
		}
		
		if (!$this->isAValidDomain($domain[1])) {
			$this->flashSession->error("Hay direcciones de correo para remitente que contienen dominios invalidos, recuerde que no debe usar dominios de correo públicas como hotmail o gmail");
			throw new InvalidArgumentException("Invalid domain");
		}
		
		if (empty($name)) {
			$this->flashSession->error("No ha enviado un nombre de remitente válido, por favor verifique la información");
			throw new InvalidArgumentException("Invalid remittent name");
		}
		
		$sender = new Sender();
		$sender->idAccount = $account->idAccount;
		$sender->email = $email;
		$sender->name = $name;
		$sender->createdon = time();

		if (!$sender->save()) {
			foreach ($sender->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
			throw new Exception("Error while saving account remittent");
		}
		
   }

	protected function saveUser(Account $account, $form, $prefix) 
	{
		$user = new User();
		
		$email = strtolower($form->getValue('email'));
				
		$user->email = $email;
		$user->firstName = $form->getValue('firstName');
		$user->lastName = $form->getValue('lastName');				
		$pass =	$user->password = $form->getValue('password');
		$pass2=	$user->password2 = $form->getValue('password2');
		$user->username = $prefix . '_' . $form->getValue('username');  
		$user->userrole='ROLE_ADMIN';
		$user->account = $account;
				
		if($pass == $pass2){
			if(strlen($pass) >= 8){
				$user->password = $this->security2->hash($pass);
				if (!$user->save()) {
					foreach ($user->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					throw new Exception("Error while saving principal account user");
				}
			}
			else{
				$this->flashSession->error("La contraseña es muy corta, debe tener mínimo 8 y máximo 40 caracteres");
				throw new InvalidArgumentException("Password too short");
			}
		}
		else{
			$this->flashSession->error('Las contraseñas no coinciden por favor verifique la información');
			throw new InvalidArgumentException("Error in passwords");
		}
	}


	protected function saveDbase(Account $account)
	{
		$dbase = new Dbase();
		
		$dbase->account = $account;
		$dbase->name = "Base de datos";
		$dbase->description = "Sin descripción";
		$dbase->Cdescription = "Sin descripción de contactos";
		$dbase->Ctotal = 0;
		$dbase->Cactive = 0;
		$dbase->Cunsubscribed = 0;
		$dbase->Cbounced = 0;
		$dbase->Cspam = 0;
		$dbase->color = '#0080FF';
		$dbase->createdon = time();
		$dbase->update = time();

		if (!$dbase->save()) {
			foreach ($dbase->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
			throw new Exception("Error while saving principal account data base");
		}
	}

	private function validatePrefix($name, $prefix)
	{
		 $p = (empty($prefix) ? $name : $prefix);

		 $prefix = (strlen($p) <= 4 ? strtolower($p) : $this->getPrefix($p));

		 return $prefix;
	}
   
   /*
    * Esta función se encarga de mostrar todos los usuarios de todas la cuentas. Es utilizada por el super-administrador
    * Recibe el id de la cuenta
    */
   public function showAction($id)
   {
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => User::find(array(
					"conditions" => "idAccount = ?1",
					"bind" => array(1 => $id)
				)),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);

		$page = $paginator->getPaginate();

		$this->view->setVar("page", $page);
		$this->view->setVar("idAccount", $id);
   }
 
   /*
    * Esta función se encarga de editar información de una cuenta. Es utilizada por el super-administador
    * Recibe el id de la cuenta
    */  
   public function editAction($id)
   {
	    $account = Account::findFirst(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $id)
		));
		
		if ($account) {
			$allRemittents = Sender::find(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $account->idAccount)
			));
			
			
			$completeRemittents = '';
			if (count($allRemittents) > 0) {
				foreach ($allRemittents as $r) {
					$completeRemittents .= "{$r->email}/{$r->name},";
				}
			}
			
			$account->sender = $completeRemittents;
            $this->view->setVar("account", $account);
			$editform = new AccountForm($account);

 			if ($this->request->isPost()) {   
				$editform->bind($this->request->getPost(), $account);

				$n = $editform->getValue('companyName');
				$p = $editform->getValue('prefix');
				$r = $editform->getValue('sender');
				
				if (empty($r)) {
					$this->flashSession->error('No ha enviado un remitente válido o el campo esta vacío, por favor verfique la información');
				}
				else {
					$remittents = explode(',', $r);
					$remittents = array_unique($remittents);
//					$this->logger->log("Sender: " . print_r($remittents, true));
					
					$account->prefix = $this->validatePrefix($n, $p);
					
					$this->db->begin();
					if (!$editform->isValid() || !$account->save()) {
						foreach ($account->getMessages() as $msg) {
							$this->flashSession->error($msg);
						} 
					}
					else {
						try {
							foreach ($remittents as $remittent) {
								$this->saveSender($account, $remittent);
							}
							
							foreach ($allRemittents as $r) {
								$r->delete();
							}
							
							$this->db->commit();
							$this->traceSuccess("Edit account: {$id}");
							$this->flashSession->success('Se ha editado la cuenta exitosamente');
							return $this->response->redirect("account");
						}
						catch (Exception $e) {
							$this->db->rollback();
						}
					}
				}
 			}
			$this->view->editFormAccount = $editform;
        } 
		else {
			$this->traceFail("Edit, account do not exists: {$id}");
			$this->flashSession->error('La cuenta no existe, por favor verifique la información');
			return $this->response->redirect("account");
		}
    }
	
	/*
	 * Esta función se encagar de borrar una cuenta de la base de datos, es muy delicada. Es utilizada por el administrador
	 * recibe el id de la cuenta
	 */
	public function deleteAction($id)
    {	
		$account = Account::findFirst(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $id)
		));
		
		$user = User::findFirst(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $id)
		));

		if ($account !== null) {
			if ($this->request->isPost() && ($this->request->getPost('delete')=="DELETE")) {
			   $account->delete();
			   if (!$user->deleted()) {
				   foreach ($user->getMessages() as $msg) {
					   $this->logger->log("Error while deleting account {$msg}");
				   }
				   $this->traceFail("Error deleting account {$id}");
				   $this->flashSession->error('Ha ocurrido un error contacte al administrador');
				   return $this->response->redirect("account/index");
			   }
			   $this->traceSuccess("Account deleted {$id}");
			   $this->flashSession->success('Base de Datos Eliminada!');
			   return $this->response->redirect("account/index");
			} 

		   else {
				$this->traceFail("Error deleting account {$id}");
				$this->flashSession->error('Escriba la palabra "DELETE" correctamente');
				return $this->response->redirect("account/index");
		   }
		}
     }
	 
	/*
	 * Esta funcion se encarga de crear usuarios a partir del id de la cuenta
	 */
	public function newuserAction($idAccount)
	{
		$user = new User();
		$form = new UserForm($user);
		
		$account = Account::findFirst(array(
			'conditions' => 'idAccount = ?1',
			'bind' => array(1 => $idAccount)
		));
		
		if (!$account) {
			$this->flashSession->error("La cuenta enviada no existe, por favor verifique la información");
			return $this->response->redirect("account/show/{$this->user->account->idAccount}");
		}
		
		$prefix = $account->prefix . '_';
		$this->view->setVar("prefix", $prefix);
		
		if ($this->request->isPost()) {
			$form->bind($this->request->getPost(), $user);
			
			$username = $form->getValue('username');
			
			if (strlen($username) < 4) {
				$this->flashSession->error("El nombre de usuario es muy corto, debe tener al menos 4 caracteres");
			}
			else {
				$user->username = $prefix . $username;
				$pass = $form->getValue('password');
				$pass2 = $form->getValue('password2');

				if(strlen($pass) < 8) {
					$this->flashSession->error("La contraseña es muy corta, debe tener mínimo 8 caracteres y máximo 40");
				}
				else {
					if($pass !== $pass2) {
						$this->flashSession->error("Las contraseñas no coinciden por favor verifique la información");
					}
					else {
						$email = strtolower($form->getValue('email'));

						$user->idAccount = $account->idAccount;
						$user->email = $email;
						$user->password = $this->security2->hash($pass);

						if ($form->isValid() && $user->save()) {
							$this->traceSuccess("Create user like sudo, account {$account->idAccount}");
							$this->flashSession->notice("Se ha creado el usuario exitosamente en la cuenta {$account->companyName}");
							return $this->response->redirect("account/show/{$account->idAccount}");
						}

						else {
							foreach ($user->getMessages() as $msg) {
									$this->flashSession->error($msg);
									$this->logger->log("Error while creating user account {$account->idAccount}");
							}
							$this->traceFail("Create user like sudo, account {$account->idAccount}");
						}
					}
				}
			}
		}

		$this->view->UserForm = $form;
		$this->view->setVar('account', $account);
	}
	
	/*
	 * Esta función le permite al super-administrador editar cualquier usuario de cualquier cuenta
	 * Recibe el id del usuario
	 */
	public function edituserAction($id)
	{
		$userExist = User::findFirst(array(
			"conditions" => "idUser = ?1",
			"bind" => array(1 => $id)
		));
		
		if(!$userExist){
			$this->flashSession->error("El usuario que intenta editar no existe, por favor verifique la información");
			return $this->response->redirect("account/index");
		}
		
		$account = $userExist->account;
		$prefix = $account->prefix . '_';
		
		$userExist->username = $this->removePrefix($userExist->username);
		
		$this->view->setVar("user", $userExist);
		$this->view->setVar("prefix", $prefix);
		$form = new UserForm($userExist);

		if ($this->request->isPost()) {   
			$form->bind($this->request->getPost(), $userExist);
			
			$username = $form->getValue('username');
			
			if (strlen($username) < 4) {
				$this->flashSession->error("El nombre de usuario es muy corto, debe tener al menos 4 caracteres");
				return $this->response->redirect("account/edituser/{$id}");
			}
			
			$userExist->username = $prefix . $username; 
			$pass = $form->getValue('passForEdit');
			$pass2 = $form->getValue('pass2ForEdit');
			$email = strtolower($form->getValue('email'));

			if((!empty($pass)||!empty($pass2)) && ($pass == $pass2) && (strlen($pass) >= 8)){
				$userExist->email = $email;
				$userExist->password = $this->security2->hash($pass);

				if (!$form->isValid() || !$userExist->save()) {
					foreach ($userExist->getMessages() as $msg) {
						$this->flashSession->error($msg);
						$this->logger->log("Error while editing user {$id} in account {$userExist->idAccount} {$msg}");
					}
				}
				else {
					$this->traceSuccess("Edit user {$id} like sudo, account {$userExist->idAccount}");
					$this->flashSession->notice('Se ha editado exitosamente el usuario <strong>' .$userExist->username. '</strong> de la cuenta <strong>' .$userExist->idAccount. '</strong>');
					return $this->response->redirect("account/show/{$userExist->idAccount}");
				}
			}
			else{
				$userExist->email = $email;
				if (!$form->isValid() OR !$userExist->save()) {
					foreach ($userExist->getMessages() as $msg) {
						$this->flashSession->error($msg);
						$this->logger->log("Error while editing user {$id} in account {$this->user->account->idAccount} {$msg}");
					}
				}
				else {
					$this->traceSuccess("Edit user {$id} like sudo, account {$userExist->idAccount}");
					$this->flashSession->notice('Se ha editado exitosamente el usuario <strong>' .$userExist->username. '</strong> de la cuenta <strong>' .$userExist->idAccount. '</strong>');
					return $this->response->redirect("account/show/{$userExist->idAccount}");
				}
			}

		}
		$this->view->UserForm = $form;
	}
	
	/*
	 * Esta funcion permite al super-administrador eliminar cualquier usuario de cualquier cuenta
	 * recibe el id el usuario
	 */
	public function deleteuserAction($id)
	{
		$idUser = $this->session->get('userid');
		
		if($id == $idUser){
			$this->flashSession->error("No se puede eliminar el usuario que esta actualmente en sesión, por favor verifique la información");
			$this->traceFail("Trying to delete user in session, user: {$idUser}");
			return $this->response->redirect("account/show/{$this->user->account->idAccount}");
		}
		
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1",
			"bind" => array(1 => $id)
		));

		if(!$user){
			$this->flashSession->error('El usuario que ha intentado eliminar no existe, por favor verifique la información');
			$this->traceFail("User do not exist, idUser: {$idUser}");
			return $this->response->redirect("account/index");
		}
		
		if(!$user->delete()) {
			foreach ($user->getMessages() as $msg) {
				$this->flashSession->error($msg);
				$this->logger->log("Error while deleting user {$msg}, user: {$user->idUser}/{$user->username}");
			}
			return $this->response->redirect("account/show/{$user->idAccount}");
		}
		$this->traceSuccess("User deleted, idUser: {$id}");
		$this->flashSession->warning('Se ha eliminado el usuario <strong>' .$user->username. '</strong> exitosamente');
		return $this->response->redirect("account/show/{$user->idAccount}");
	}
	
	public function accountingAction()
	{
		$user = $this->user;
		$accounts = Account::find();
		
		$accounting = new \EmailMarketing\General\Misc\AccountingObject();
		$accounting->setAccounts($accounts);
		$accounting->createCurrentAndLastAccounting();
		$accounting->classColor();
		
		try {
			$a = $accounting->getAccounting();
			$this->traceSuccess("Read accounting, idUser: {$user->idUser}");
		}
		catch (Exception $e) {
			$this->traceFail("Read accounting, idUser: {$user->idUser}");
			$this->logger->log("Exception: {$e}");
		}
		
		
		$month = date('M', time());
		$year = date('Y', time());
		
		$currentMonth = strtotime("1 {$month} {$year}");
		$lastMonth = strtotime("-1 month", $currentMonth);
		
		$this->view->setVar('currentMonth', date('M', $currentMonth));
		$this->view->setVar('lastMonth', date('M', $lastMonth));
		$this->view->setVar('accounts', $a);
	}
	
	protected function isAValidDomain($domain)
	{
		$invalidDomains = array(
//			'yahoo',
//			'hotmail',
//			'live',
//			'gmail',
//			'aol'
		);
		
		$d = explode('.', $domain);
		
		foreach ($invalidDomains as $invalidDomain) {
			if ($invalidDomain == $d[0]) {
				return false;
			}
		}
		return true;
	}
	
	public function changestatusAction($idAccount) 
	{
		$response = $this->getMessageResponse(400);
		
		if ($this->request->isPost()) {
			$account = Account::findFirst(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $idAccount)
			));
			
			$response = $this->getMessageResponse(404);
			
			if ($account) {
				$status = $account->status;
				$account->status = ($status == 1 ? 0 : 1);
				
				if (!$account->save()) {
					foreach ($account->getMessages() as $msg) {
						$this->logger->log("Error: {$msg}");
					}
					$response = $this->getMessageResponse(500);
				}
				$response = $this->getMessageResponse(200);
			}
		}
		
		return $this->setJsonResponse(array($response->type => $response->msg), $response->status);
	}
	
	public function scorehistoryAction($id)
	{
		$account = Account::findFirst(array(
			'conditions' => 'idAccount = ?1',
			'bind' => array(1 => $id)
		));
		
		if (!$account) {
			$this->flashSession->error("Cuenta no encontrada!");
			return $this->response->redirect('account');
		}
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$builder = $this->modelsManager->createBuilder()
			->from('Scorehistory')
			->leftJoin('Score')
			->leftJoin('Mail')
			->where("Scorehistory.idAccount = {$id}")
			->orderBy('Scorehistory.createdon');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder" => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page" => $currentPage
		));
		
		$page = $paginator->getPaginate();
		
//		$smarts = array();
//		$mails = array();
//		
//		foreach ($page->items as $p) {
//			$smart = Smartmanagment::findFirstByIdSmartmanagment($p->idSmartmanagment);
//			if (!in_array($smart, $smarts)) {
//				$smarts[] = $smart;
//			}
//			
//			$mail = Mail::findFirstByIdMail($p->idMail);
//			if (!in_array($mail, $mails)) {
//				$mails[] = $mail;
//			}
//		}
//		
//		$score = Score::findFirst(array(
//			'conditions' => 'idAccount = ?1',
//			'bind' => array(1 => $id)
//		));
		
		$this->view->setVar("page", $page);
//		$this->view->setVar("smarts", $smarts);
//		$this->view->setVar("mails", $mails);
		$this->view->setVar("account", $account);
//		$this->view->setVar("score", $score);
	}
 }  
