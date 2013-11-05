<?php
class MailController extends ControllerBase
{
	protected $image_map = array();
	
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
			$mailClone->wizardOption = $mail->wizardOption;
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
	
	public function setupAction($idMail = null)
	{
		$mailExist = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mailExist) {
			$form = new MailForm($mailExist);
			$this->view->setVar('mail', $mailExist);
		}
		else {
			$mail = new Mail();
			$form = new MailForm($mail);
			$this->view->setVar('mail', "");
		}
		
		if ($this->request->isPost()) {
			if ($mailExist) {
				$mail = $mailExist;
				$wizardOption = $mail->wizardOption;
			}
			else {
				$wizardOption = "setup";
			}
			$form->bind($this->request->getPost(), $mail);
			
			$mail->idAccount = $this->user->account->idAccount;
			$mail->fromEmail = strtolower($form->getValue('fromEmail'));
			$mail->replyTo = strtolower($form->getValue('replyTo'));
			$mail->status = "Draft";
			$mail->wizardOption = $wizardOption;
			
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
	
	public function editorAction($idMail = null, $idTemplate = null) 
	{
		$log = $this->logger;
		
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$objTemplate = Template::findFirst(array(
				"conditions" => "idTemplate = ?1",
				"bind" => array(1 => $idTemplate)
			));
		
			if ($objTemplate) {
				$log->log('Este es template: ' . print_r($objTemplate->content, true));
				$this->view->setVar('objMail', $objTemplate->content);
			}
			else {
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
	
	public function templateAction($idMail)
	{
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$templates = Template::find();
			
			$arrayTemplate = array();
			foreach ($templates as $template) {
				$templateInfo = array(
					"id" => $template->idTemplate, 
					"name" => $template->name, 
					"content" => $template->content);

					$arrayTemplate[$template->category][] = $templateInfo;
			}
			
			$this->view->setVar('templates', $templates);
			$this->view->setVar('arrayTemplate', $arrayTemplate);
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
				if ($mailContentExist) {
					$mailContent = $mailContentExist;
				}
				
				if ($mail->wizardOption == 'source' || $mail->wizardOption == 'setup') {
					$wizardOption = 'source';
				}
				else{
					$wizardOption = $mail->wizardOption;
				}
				
				$content = $this->request->getPost("content");
				$direction = $this->request->getPost("direction");
				$plainText = null;
				
				$buscar = array("<script" , "</script>");
				$reemplazar = array("<!-- ", " -->");

				$newContent = str_replace($buscar,$reemplazar, $content);
				
				$mailContent->idMail = $idMail;
				$mailContent->content = htmlspecialchars($newContent, ENT_QUOTES);
				
				$mail->type = "Html";
				$mail->wizardOption = $wizardOption;
				
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
				
				if ($mail->wizardOption == 'source' || $mail->wizardOption == 'setup') {
					$wizardOption = 'source';
				}
				else{
					$wizardOption = $mail->wizardOption;
				}
				
				$content = new Mailcontent();
				
				$content->idMail = $idMail;
				$content->content = htmlspecialchars($html, ENT_QUOTES);
				
				$mail->type = "Html";
				$mail->wizardOption = $wizardOption;
				
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
	
	public function plaintextAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2 AND type = ?3',
			'bind' => array(1 => $idMail,
							2 => 'Draft',
							3 => 'Html')
		));
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$mailContent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $mail->idMail)
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
				
				switch ($direction) {
					case 'plaintext':
						$text = new PlainText();
						$plainText = $text->getPlainText($mailContent->content);
						$this->view->setVar('plaintext', $plainText);
						break;
					
					default :
						$mailContent->plainText = $this->request->getPost('plaintext');
						if (!$mailContent->save()) {
							foreach ($mailContent->getMessages() as $msg) {
								$this->flashSession->error($msg);
							}
						}
						else {
							$this->routeRequest('plaintext', $direction, $mail->idMail);
						}
						break;;
				}
			}
		}
	}
	
	public function targetAction($idMail = null)
	{
		$log = $this->logger;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2 AND (wizardOption = ?3 OR wizardOption = ?4 OR wizardOption = ?5)',
			'bind' => array(1 => $idMail,
							2 => 'Draft',
							3 => 'source',
							4 => 'target',
							5 => 'schedule')
		));
		
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
				$targetSelected = $this->request->getPost('targetSelected');
				
				$idDbases = $this->request->getPost("dbases");
				$idContactlists = $this->request->getPost("contactlists");
				$idSegments = $this->request->getPost("segments");
				
				if ($idDbases == null && $idContactlists == null && $idSegments == null && $targetSelected == null) {
					$this->flashSession->error("No ha seleccionado listas de contactos, base de datos o segmentos, por favor verifique la información");
					return $this->response->redirect("mail/target/" . $idMail);
				}
				else if ($targetSelected !== '' && ($idDbases == null && $idContactlists == null && $idSegments == null)) {
					$this->routeRequest('target', $direction, $mail->idMail);
					return false;
				}
				try {
					$target = new TargetObj();
					
					$target->setDbases($dbases);
					$target->setContactlists($contactlists);
					$target->setSegments($segments);
					
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
		else {
			return $this->response->redirect('mail/source/' . $idMail);
		}
	}
	
	public function filterAction($idMail)
	{
		$log = $this->logger;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2 AND (wizardOption = ?3 OR wizardOption = ?4)',
			'bind' => array(1 => $idMail,
							2 => 'Draft',
							3 => 'target',
							4 => 'schedule')
		));
		if ($mail) {
			$this->view->setVar('mail', $mail);
			if ($this->request->isPost()) {
				
				$targetJson = json_decode($mail->target);
				$direction = $this->request->getPost('direction');
				
				$byMail = $this->request->getPost('sendByMail');
				$byOpen = $this->request->getPost('sendByOpen');
				$byClick = $this->request->getPost('sendByClick');
				$byExclude = $this->request->getPost('excludeContact');
				
//				$log->log("mail: " . $byMail . ", open: " . $byOpen . ", click: " . $byClick . ", exclude: " . print_r($byExclude, true));
				if ($byMail !== "" ) { 
					$filter = array(
						'type' => 'mail',
						'criteria' => $byMail
					);
					$targetJson->filter = $filter;
				}
				else if ($byOpen !== null ) {
					$filter = array(
						'type' => 'open',
						'criteria' => $byOpen
					);
					$targetJson->filter = $filter;
				}
				else if ($byClick !== null ) {
					$filter = array(
						'type' => 'click',
						'criteria' => $byOpen
					);
					$targetJson->filter = $filter;
				}
				else if ($byExclude !== null ) {
					$filter = array(
						'type' => 'exclude',
						'criteria' => $byOpen
					);
					$targetJson->filter = $filter;
				}
				else {
					$this->flashSession->error('Debe seleccionar al menos un filtro, por favor verifique la información');
					return $this->response->redirect('mail/filter/' . $idMail);
				}
				
				$mail->target = json_encode($targetJson);
				if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else {
					$this->routeRequest('filter', $direction, $mail->idMail);
				}
			}
		}
		else {
			return $this->response->redirect('mail/source/' . $idMail);
		}
	}
	
	public function scheduleAction($idMail = null)
	{
		$log = $this->logger;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2 AND (wizardOption = ?3 OR wizardOption = ?4)',
			'bind' => array(1 => $idMail,
							2 => 'Draft',
							3 => 'target',
							4 => 'schedule')
		));
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			if ($this->request->isPost()) {
				$direction = $this->request->getPost('direction');
				$schedule = $this->request->getPost('schedule');
				$date = $this->request->getPost('dateSchedule');
				
				if ($schedule == 'rightNow') {
					$mail->dateSchedule = time();
				}
				else if ($schedule == 'after' || $date !== "") {
					list($day, $month, $year, $hour, $minute) = preg_split('/[\s\/|-|:]+/', $date);
					$dateTimestamp = mktime($hour, $minute, 0, $month, $day, $year);
					
					if($dateTimestamp < time()) {
						$this->flashSession->error("la siguiente fecha: <span style='color: #fff7f8;'>" . $date . " </span> que ha ingresado ya ha pasado, por favor verifique la información");
						return false;
					}
					$mail->dateSchedule = $dateTimestamp;
				}
				else if ($schedule == null && $date == null) {
					$this->flashSession->error("No ha ingresado una fecha de envío del correo, por favor verifique la información");
					return false;
				}
				
				$mail->wizardOption = 'schedule';
				if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				
				$this->routeRequest('schedule', $direction, $mail->idMail);
			}
		}
		else {
			return $this->response->redirect('mail/target/' . $idMail);
		}
	}
	
	public function previewAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2',
			'bind' => array(1 => $idMail,
							2 => 'Draft')
		));
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			if ($this->request->isPost()) {
			}
		}
		else {
			return $this->response->redirect('mail/source/' . $idMail);
		}
	}

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
				case 'schedule':
					$go = 'mail/preview/';
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
	
}
