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
		else if(!$mail || count($mail) < 0) {
			return $this->response->redirect("mail/setup");
		}
	}
	
	protected function routeRequest($action, $direction, $idMail)
	{
		if ($direction == 'next') {
			switch ($action) {
				case 'setup':
					$go = 'mail/source/';
					break;
				case 'html':
					$go = 'mail/plaintext/';
					break;
				case 'editor':
					$go = 'mail/plaintext/';
					break;
				case 'plaintext':
					$go = 'mail/target/';
					break;
				case 'target':
					$go = 'mail/schedule/';
					break;
				case 'filter':
					$go = 'mail/schedule/';
					break;
			}
			return $this->response->redirect($go . $idMail);
		}
		else if ($direction == 'prev') {
			switch ($action) {
				case 'html':
					$go = 'mail/setup/';
					break;
				case 'editor':
					$go = 'mail/setup/';
					break;
				case 'plaintext':
					$go = 'mail/source/';
					break;
				case 'target':
					$go = 'mail/plaintext/';
					break;
				case 'filter':
					$go = 'mail/target/';
					break;
				case 'schedule':
					$go = 'mail/target/';
					break;
			}
			return $this->response->redirect($go . $idMail);
		}
		else if ($direction == 'filter') {
			return $this->response->redirect('mail/filter/' . $idMail);
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
		$log = $this->logger;
		
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('idMail', $idMail);
			
			$objMail = Mailcontent::findFirst(array(
				"conditions" => "idMail = ?1",
				"bind" => array(1 => $mail->idMail)
			));
			
			if ($objMail) {
				$this->view->setVar('objMail', $objMail->content);
			}
			else  {
				$this->view->setVar('objMail', 'null');
			}
			
			if ($this->request->isPost()) {
				
				$mailContent = new Mailcontent();
				$content = $this->request->getPost("editor");
				$log->log($content);
				$mailContent->idMail = $idMail;
				$mailContent->content = $content;

				$mail->type = "Editor";

				if(!$mailContent->save()) {
					$log->log("No guarda");
					foreach ($mailContent->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else {
					//$this->routeRequest('editor', $direction, $idMail);
					return $this->response->redirect("mail/plaintext/" .$idMail);
				}
			}		
		}
		
	}
	
	public function htmlAction($idMail = null)
	{
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
			
			$this->view->setVar('mail', $mail);
			
			if ($this->request->isPost()) {
				$mailContent = new Mailcontent();
				
				$content = $this->request->getPost("content");
				$direction = $this->request->getPost("direction");
				
				$buscar = array("<script" , "</script>");
				$reemplazar = array("<!-- ", " -->");

				$newContent = str_replace($buscar,$reemplazar, $content);
				
				$mailContent->idMail = $idMail;
				$mailContent->content = htmlspecialchars($newContent, ENT_QUOTES);
				
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
					$this->routeRequest('html', $direction, $idMail);
				}
			}
			$this->view->MailForm = $form;
		}
	}
	
	public function importAction($idMail = null)
	{
		$mail = $this->validateProcess($idMail);
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			if ($this->request->isPost()) {
				$this->db->begin();
				$account = $this->user->account;
				
				$url = $this->request->getPost("url");
				$image = $this->request->getPost("image");
				
				$dir = $this->asset->dir . $account->idAccount . "/images";
				
				if(!filter_var($url, FILTER_VALIDATE_URL)) {
					$this->flashSession->error("La url ingresada no es válida, por favor verifique la información");
					return $this->response->redirect("mail/import/" . $idMail);
				}
				
				if (!file_exists($dir)) {
					mkdir($dir, 0777, true);
				} 
				
				$getHtml = new LoadHtml();
				$html = $getHtml->gethtml($url, $image, $dir, $account);
				
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
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$dbases = Dbase::findByIdAccount($this->user->account->idAccount);

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
				$direction = $this->request->getPost('direction');
				
				$idDbases = $this->request->getPost("dbases");
				$idContactlists = $this->request->getPost("contactlists");
				$idSegments = $this->request->getPost("segments");
				
				if ($idDbases == null && $idContactlists == null && $idSegments == null) {
					$this->flashSession->error("No ha seleccionado listas de contactos, base de datos o segmentos, por favor verifique la información");
					return $this->response->redirect("mail/target/" . $idMail);
				}
				
				try {
					$target = new TargetObj($dbases, $contactlists, $segments);
					$response = $target->createTargetObj($idDbases, $idContactlists, $idSegments, $mail);
				}
				catch (InvalidArgumentException $e) {
					throw new InvalidArgumentException("Error while saving targetObj in db");
				}
				
				if (!$response) {
					$this->flashSession->error("No hay contactos registrados, por favor seleccione otra base de datos, lista o segmento");
				}
				else {
					$this->routeRequest('target', $direction, $mail->idMail);
				}
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
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
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
	
	public function editor_frameAction($idMail) 
	{
		$log = $this->logger;
		if (!$this->request->isPost()) {
		$assets = AssetObj::findAllAssetsInAccount($this->user->account);
		
		foreach ($assets as $a) {
			$arrayAssets[] = array ('thumb' => $a->getThumbnailUrl(), 
								'image' => $a->getImagePrivateUrl(),
								'title' => $a->getFileName(),
								'id' => $a->getIdAsset());								
		}
		
		$this->view->setVar('assets', $arrayAssets);
		}
		else {
			$this->view->setVar('assets', $arrayAssets);
		}
		$this->view->setVar('idMail', $idMail);
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
		
		$this->flashSession->error('Un error no permitió duplicar el correo');
		return $this->response->redirect("mail/index");
	}
	
	public function plaintextAction($idMail)
	{
		$log = $this->logger;
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$mailContent = Mailcontent::findFirst(array(
				"conditions" =>"idMail = ?1",
				"bind" => array(1 => $mail->idMail)
			));
			
			if ($mailContent->plainText == null) {
				$text = new PlainText();
				$plainText = $text->getPlainText($mailContent->content);
			}
			else {
				$plainText = $mailContent->plainText;
			}
			
			$this->view->setVar('plaintext', $plainText);
			
			if ($this->request->isPost()) {
				
				$direction = $this->request->getPost('direction');
				$mailContent->plainText = $this->request->getPost('plaintext');
				
				if (!$mailContent->save()) {
					foreach ($mailContent->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else {
					$this->routeRequest('plaintext', $direction, $mail->idMail);
				}
			}
		}
	}
	
	public function filterAction($idMail)
	{
		$mail = $this->validateProcess($idMail);
		if ($mail) {
			$this->view->setVar('mail', $mail);
			if ($this->request->isPost()) {
				
				$direction = $this->request->getPost('direction');
				
				
				$this->routeRequest('filter', $direction, $mail->idMail);
			}
		}
	}
	
	public function previewAction($idMail)
	{
		$mail = $this->validateProcess($idMail);
		if ($mail) {
			$mailContent = Mailcontent::findFirst(array(
				"conditions" => "idMail = ?1",
				"bind" => array(1 => $mail->idMail)
			));
			if ($this->request->isPost()) {
				
			}
			$this->view->setVar('mail', $mail);
			$this->view->setVar('mailContent', $mailContent);
		}
	}
}