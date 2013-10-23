<?php
class MailController extends ControllerBase
{
	protected $image_map = array();
	
	protected function validateProcess($idMail)
	{
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2 AND status = ?3",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount,
							3 => "Draft")
		)); 
		if ($mail) {
			return $mail;
		}
		else if(!$mail || $mail == null) {
			return $this->response->redirect("mail/setup");
		}
	}
	
	public function indexAction()
	{	
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		
		$idAccount = $this->user->account->idAccount;
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data"  => Mail::find("idAccount = $idAccount"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page"  => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function setupAction($idMail = null)
	{
		$log = $this->logger;
		$mailExist = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mailExist) {
			$form = new MailForm($mailExist);
			$this->view->setVar('idMail', $idMail);
		}
		else {
			$mail = new Mail();
			$form = new MailForm($mail);
			$this->view->setVar('idMail', " ");
		}
		
		if ($this->request->isPost()) {
			if ($mailExist) {
				$mail = $mailExist;
			}
			$form->bind($this->request->getPost(), $mail);
			
			$mail->idAccount = $this->user->account->idAccount;
			$mail->fromEmail = strtolower($form->getValue('fromEmail'));
			$mail->replyTo = strtolower($form->getValue('replyTo'));
			$mail->status = "Draft";
			
            if ($form->isValid() && $mail->save()) {
				switch ($mail->type) {
					case "Html":
						return $this->response->redirect("mail/html/" .$mail->idMail);
						break;
					
					case "Editor":
						return $this->response->redirect("mail/editor/" .$mail->idMail);
						break;
					
					default:
						return $this->response->redirect("mail/source/" .$mail->idMail);
						break;
				}
			}
			else {
				foreach ($mail->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
			}
			
		}
		$this->view->MailForm = $form;
	}
	
	public function sourceAction($idMail = null)
	{
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2 AND status = ?3",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount,
							3 => "Draft")
		)); 
		
		if ($mail) {
			switch ($mail->type) {
				case "Html":
					return $this->response->redirect("mail/html/" .$idMail);
					break;
				
				case "Editor":
					return $this->response->redirect("mail/editor/" .$idMail);
					break;
				
				default:
					$this->view->setVar('idMail', $idMail);
					break;
			}
		} 
		else {
			return $this->response->redirect("mail/setup/" .$idMail);
		}
	}

	public function editorAction($idMail = null) 
	{
		$isOk = $this->validateProcess($idMail);
		
		if ($isOk) {
			//aqui haces lo q tengas q hacer jejeje,
			//esta evita q el usuario se salte los pasos
			$this->view->setVar('idMail', $idMail);
		}
		
		
	}
	
	public function htmlAction($idMail = null)
	{
		$log = $this->logger;
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {

			$mailContentExist = Mailcontent::findFirst(array(
				"conditions" => "idMail = ?1",
				"bind" => array(1 => $idMail)
			));
			
			if ($mailContentExist) {
				$this->view->setVar("mailContent", $mailContentExist);
				$form = new MailForm($mailContentExist);
			}
			else {
				$mailContent = new Mailcontent();
				$form = new MailForm($mailContent);
			}
			
			$this->view->setVar('idMail', $idMail);
			
			if ($this->request->isPost()) {
				$mailContent = new Mailcontent();
				$content = $this->request->getPost("content");
				
				$mailContent->idMail = $idMail;
				$mailContent->content = htmlspecialchars($content, ENT_QUOTES);
				
				$mail->type = "Html";
				
				if(!$mailContent->save()) {
					foreach ($mailContentExist->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else {
					return $this->response->redirect("mail/target/" .$idMail);
				}
			}
			$this->view->MailForm = $form;
		}
	}
	
	public function importAction($idMail = null)
	{
		$log = $this->logger;
		$mail = $this->validateProcess($idMail);
		if ($mail) {
			$this->view->setVar('idMail', $idMail);
			
			if ($this->request->isPost()) {
				$this->db->begin();
				$idAccount = $this->user->account->idAccount;
				
				$url = $this->request->getPost("url");
				$image = $this->request->getPost("image");
				
				$dir = $this->asset->dir . $idAccount . "/images";
				
				if(!filter_var($url, FILTER_VALIDATE_URL)) {
					$this->flashSession->error("La url ingresada no es válida, por favor verifique la información");
					return $this->response->redirect("mail/import/" . $idMail);
				}
				
				if (!file_exists($dir)) {
					mkdir($dir, 0777, true);
				} 
				
				$getHtml = new LoadHtml();
				$html = $getHtml->gethtml($url, $image, $dir, $idAccount);
				
				$content = new Mailcontent();
				
				$content->idMail = $idMail;
				$content->content = htmlspecialchars($html, ENT_QUOTES);
				
				$mail->type = "Html";
				
				if (!$content->save()) {
					foreach ($content->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}	
					$this->db->rollback();
					return $this->response->redirect("mail/import/" . $idMail);
				}
				else if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}		
					$this->db->rollback();
					return $this->response->redirect("mail/import/" . $idMail);
				}
				else {
					$this->db->commit();
					return $this->response->redirect("mail/html/" . $idMail);
				}
			}
		}
	}
	
	public function targetAction($idMail = null)
	{
		$log = $this->logger;
		$isOk = $this->validateProcess($idMail);
		if ($isOk) {
			$this->view->setVar('idMail', $idMail);
			$idAccount = $this->user->account->idAccount;
			$dbases = Dbase::findByIdAccount($idAccount);

			$array = array();
			foreach ($dbases as $dbase) {
				$array[] = $dbase->idDbase;
			}

			$idsDbase = implode(",", $array);

			$phql1 = "SELECT Dbase.name AS Dbase, Contactlist.idContactlist, Contactlist.name FROM Dbase JOIN Contactlist ON (Contactlist.idDbase = Dbase.idDbase) WHERE Dbase.idDbase IN (". $idsDbase .")";
			$phql2 = "SELECT * FROM Segment WHERE idDbase IN (". $idsDbase .")";

			$contactlists = $this->modelsManager->executeQuery($phql1);
			$segments = $this->modelsManager->executeQuery($phql2);

			$this->view->setVar('dbases', $dbases);
			$this->view->setVar('contactlists', $contactlists);
			$this->view->setVar('segments', $segments);
			
			if ($this->request->isPost()) {
				$dbases = $this->request->getPost("dbases");
				$contactlists = $this->request->getPost("contactlists");
				$segments = $this->request->getPost("segments");
				
				$sql = "REPLACE INTO mxc (idMail, idContact)";
				
				if ($dbases != null) {
					$dbase = implode(',', $dbases);
					$phql = "SELECT contact.idContact FROM contact WHERE contact.idDbase IN (" . $dbase . ")";
				}
				
				else if ($contactlists != null) {
					$contactlist = implode(',', $contactlists);
					$phql= "SELECT coxcl.idContact FROM coxcl WHERE coxcl.idContactlist IN (" . $contactlist . ")";
				}
				
				else if ($segments != null) {
					$segment = implode(',', $segments);
					$phql .= "SELECT sxc.idContact FROM sxc WHERE sxc.idSegment IN (" . $segment . ")";
				}
				
				else {
					$this->flashSession->error("No ha seleccionado una lista, base de datos o segmento, por favor verifique la información");
					return $this->response->redirect("mail/target/" . $idMail);
				}
				$contacts = $this->modelsManager->executeQuery($phql);
				if (count($contacts) <= 0) {
					$this->flashSession->error("No hay contactos registrados, por favor seleccione otra base de datos, lista o segmento");
					return $this->response->redirect("mail/target/" . $idMail);
				}
				$idContacts = $this->returnIds($contacts, $idMail);
				$sql .= $idContacts;
				
				$db = Phalcon\DI::getDefault()->get('db');
				$db->begin();
				$result = $db->execute($sql);
				
				if(!$result) {
					$db->rollback();
				}
				$db->commit();
				return $this->response->redirect("mail/schedule/" . $idMail);
			}
		}
	}
	
	private function returnIds($contacts, $idMail) 
	{
		$idContacts = " VALUES";
		foreach ($contacts as $id) {
			if ($comma == false) {
				$idContacts .= " (" . $idMail . "," . $id->idContact . ") ";
			}
			$idContacts .= ", (" . $idMail . "," . $id->idContact . ") ";
			$comma = true;
		}
		return $idContacts;
	}

	public function scheduleAction($idMail = null)
	{
		$log = $this->logger;
		$isOk = $this->validateProcess($idMail);
		
		if ($isOk) {
			if ($this->request->isPost()) {
				
				
			}
		}
	}
	
	public function deleteAction($idMail)
	{
		$time = strtotime("-31 days");
		
		$mail = Mail::findFirst(array(
			"conditions" => "(idMail = ?1 AND idAccount = ?2 AND finishedon <= ?3) OR (idMail = ?1 AND idAccount = ?2 AND status = ?4)",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount,
							3 => $time,
							4 => "Draft")
		));
		
		if (!$mail) {
			$this->flashSession->error("No se ha encontrado el correo, por favor verifique la información");
			return $this->response->redirect("mail");
		}
		
		else if (!$mail->delete()) {
			foreach ($mail->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
			return $this->response->redirect("mail");
		}
		
		else {
			$this->flashSession->warning("Se ha eliminado el correo exitosamente");
			return $this->response->redirect("mail");
		}
		
	}
	
	public function editor_frameAction() 
	{
		
	}
	
	public function cloneAction($idMail = null)
	{
		$idAccount = $this->user->account->idAccount;
		
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2",
			"bind" => array(1 => $idMail,
							2 => $idAccount)
		)); 
		
		if ($mail) {
			
			$this->db->begin();
			/* Mail Clone */
			$mailClone = new Mail();
			
			$mailClone->idAccount = $idAccount;
			$mailClone->name = $mail->name . " (copia)";
			$mailClone->subject = $mail->subject;
			$mailClone->fromName = $mail->fromName;
			$mailClone->fromEmail = $mail->fromEmail;
			$mailClone->replyTo = $mail->replyTo;
			$mailClone->type = $mail->type;
			$mailClone->status = "Draft";
			$mailClone->createdon = time();
			
			if (!$mailClone->save()) {
				foreach ($mailClone->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				$this->db->rollback();
				return $this->response->redirect("mail/index");
			}
			
			/* Mail Content Clone*/
			$mailContentClone = new Mailcontent();
			
			$mailContent = Mailcontent::findFirst(array(
				"conditions" => "idMail = ?1",
				"bind" => array(1 => $mail->idMail)
			));
			
			if ($mailContent) {
				$mailContentClone->idMail = $mailClone->idMail;
				$mailContentClone->content = $mailContent->content;
				
				if (!$mailContentClone->save()) {
					foreach ($mailContentClone->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					$this->db->rollback();
					return $this->response->redirect("mail/index");
				}
			}
			$this->db->commit();
			return $this->response->redirect("mail/setup/" .$mailClone->idMail);
		}
		
		$this->flashSession->error('Un error no permitio duplicar el correo!');
		return $this->response->redirect("mail/index");
	}
}