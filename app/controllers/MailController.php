<?php
require_once "../app/library/swiftmailer/lib/swift_required.php";

class MailController extends ControllerBase
{
	protected $image_map = array();
	
	public function savemailAction($mails = null, $idMail = null)
	{
		$account = $this->user->account;
		$mail = null;
		
		$contentsraw = $this->request->getRawBody();
		$contentsT = json_decode($contentsraw);
		$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
		$this->logger->log('idMail: ' . $idMail);
		$content = $contentsT->mail;

		if ($idMail != null) {
			
			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1 AND idAccount = ?2',
				'bind' => array(1 => $idMail,
								2 => $account->idAccount)
			));
			
			if (!$mail) {
				return $this->setJsonResponse(array('errors' => 'No se ha encontrado el correo por favor verifique la información'), 404, 'Mail not found!');
			}
		}
		
		if ($this->request->isPost() || $this->request->isPut()) {
			$MailWrapper = new MailWrapper();
			$MailWrapper->setAccount($account);
			$MailWrapper->setMail($mail);
			$MailWrapper->setContent($content);
			
			try {	
				$MailWrapper->processDataForMail();
				$MailWrapper->saveMail();
				$MailWrapper->processDataForMailContent();
				$MailWrapper->saveContent();
				$response = $MailWrapper->getResponse();
				
				return $this->setJsonResponse(array($response->key => $response->data), $response->code);
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log("InvalidArgumentException: {$e}");
				$response = $MailWrapper->getResponseMessageForEmber();
				return $this->setJsonResponse(array($response->key => $response->message), $response->code);
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('errors' => 'Ha ocurrido un error contacte al administrador'), 500);
			}
		}	
	}
	
	public function indexAction()
	{	
	}
	
	public function listAction()
	{	
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		
		$idAccount = $this->user->account->idAccount;
		

		$builder = $this->modelsManager->createBuilder()
			->from('Mail')
			->where("idAccount = $idAccount AND deleted = 0")
			->orderBy('createdon DESC');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder"  => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page"  => $currentPage
		));
		
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
			$mailClone->wizardOption = "source";
			$mailClone->finishedon = 0;
			$mailClone->createdon = time();
			$mailClone->deleted = 0;
			$mailClone->previewData = $mail->previewData;
			
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
		try {
			$process = new ProcessMail();
			$process->setAccount($this->user->account);
			$process->setUser($this->user);
			$process->deleteMail($idMail);
		} catch (\InvalidArgumentException $e) {
			$this->flashSession->error($e->getMessage());
			return $this->response->redirect("mail");
		}
		$this->flashSession->warning("Se ha eliminado el correo exitosamente");
		return $this->response->redirect("mail");
	}
	
	private function validateTemplate($template, $account)
	{
		if ($template && $template->idAccount == null) {
			return true;
		}
		else if ($template && $template->idAccount == $account->idAccount) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function setupAction($idMail = null, $idTemplate = null, $new = null)
	{
		$log = $this->logger;
		$account = $this->user->account;
		
		if ($new != null) {
			$template = Template::findFirst(array(
				'conditions' => 'idTemplate = ?1',
				'bind' => array(1 => $idTemplate)
			));
			
			if ($this->validateTemplate($template, $account)) {
				$this->view->setVar('new', true);
				$this->view->setVar('idTemplate', $idTemplate);
			}
			else {
				$this->flashSession->error('El template seleccionado no existe, por favor verifique la información');
				return $this->response->redirect('template');
			}
		}
		else {
			$this->view->setVar('new', false);
		}
		
		$mailExist = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2",
			"bind" => array(1 => $idMail,
							2 => $account->idAccount)
		));
		
		if(isset ($_SESSION['tmpData'])) {
			$form = new MailForm($_SESSION['tmpData']);
			$this->view->setVar('fbids', $_SESSION['tmpData']->facebookaccounts);
			$this->view->setVar('twids', $_SESSION['tmpData']->twitteraccounts);
			unset($_SESSION['tmpData']);
		}
		else if ($mailExist) {
			$sm = Socialmail::findFirstByIdMail($mailExist->idMail);
			if($sm) {
				$fbdesc = json_decode($sm->fbdescription);
				$twdesc = json_decode($sm->twdescription);
				$mailExist->fbtitlecontent = $fbdesc->title;
				$mailExist->fbdescriptioncontent = $fbdesc->description;
				$mailExist->fbmessagecontent = $fbdesc->message;
				$mailExist->fbimagepublication = $fbdesc->image;
				$mailExist->twpublicationcontent = $twdesc->message;
			}
			$form = new MailForm($mailExist);
			$netwids = json_decode($mailExist->socialnetworks);
			$this->view->setVar('fbids', $netwids->facebook);
			$this->view->setVar('twids', $netwids->twitter);
			$this->view->setVar('mail', $mailExist);
		}
		else {
			$mail = new Mail();
			$form = new MailForm($mail);
			$this->view->setVar('mail', "");
		}
		try {
			$socialnet = new SocialNetworkConnection();
			$socialnet->setAccount($account);
			$socialnet->setFacebookConnection($this->fbapp->iduser, $this->fbapp->token);
			$socialnet->setTwitterConnection($this->twapp->iduser, $this->twapp->token);

			$fbsocials = $socialnet->getSocialIdNameArray($socialnet->findAllFacebookAccountsByUser());
			$twsocials = $socialnet->getSocialIdNameArray($socialnet->findAllTwitterAccountsByUser());
			
			$redirect = ($idMail != null) ? '/socialmedia/create/' . $idMail : '/socialmedia/create/' ;
			$fbloginUrl = $socialnet->getFbUrlLogIn($redirect);
			$twloginUrl = $socialnet->getTwUrlLogIn($redirect);

			$this->view->setVar('fbsocials', $fbsocials);
			$this->view->setVar("fbloginUrl", $fbloginUrl);
			$this->view->setVar('twsocials', $twsocials);
			$this->view->setVar("twloginUrl", $twloginUrl);
		} 
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
		}	
		
		if ($this->request->isPost()) {
			if ($mailExist) {
				$mail = $mailExist;
				$wizardOption = $mail->wizardOption;
				$previewData = $mail->previewData;
				$type = $mail->type;
			}
			else {
				if ($new != null) {
					$wizardOption = "source";
					$previewData = $template->previewData;
					$type = 'Editor';
				}
				else {
					$wizardOption = "setup";
					$previewData = null;
					$type = null;
				}
			}
			$form->bind($this->request->getPost(), $mail);
			$fbaccounts = $this->request->getPost("facebookaccounts");
			$twaccounts = $this->request->getPost("twitteraccounts");
			$mail->idAccount = $this->user->account->idAccount;
			$mail->fromEmail = strtolower($form->getValue('fromEmail'));
			$mail->replyTo = strtolower($form->getValue('replyTo'));
			$mail->status = "Draft";
			$mail->deleted = 0;
			$mail->finishedon = 0;
			$mail->type = $type;
			$mail->wizardOption = $wizardOption;
			$mail->previewData = $previewData;

			if($fbaccounts || $twaccounts) {
				$mail->socialnetworks = $socialnet->saveSocialsIds($fbaccounts, $twaccounts);
			}
			
            if ($form->isValid() && $mail->save()) {
				if ($new != false) {
					$text = new PlainText();
					$plainText = $text->getPlainText($template->contentHtml);

					$content = new Mailcontent();

					$content->idMail = $mail->idMail;
					$content->content = $template->content;
					$content->plainText = $plainText;

					if (!$content->save()) {
						foreach ($content->getMessages() as $msg) {
							$this->logger->log('Error while creating content mail from template: ' . $msg);
						}
						return false;
					}
				}
				
				if($fbaccounts || $twaccounts) {
					$socialmail = Socialmail::findFirstByIdMail($mail->idMail);
					if(!$socialmail) {
						$socialmail = new Socialmail();
						$socialmail->idMail = $mail->idMail;
					}
					if($fbaccounts) {
						$fbtitlecontent = $this->request->getPost("fbtitlecontent");
						$fbdescriptioncontent = $this->request->getPost("fbdescriptioncontent");
						$fbmsgcontent = $this->request->getPost("fbmessagecontent");
						$fbimage = $this->request->getPost("fbimagepublication");
						$socialmail->fbdescription = $socialnet->saveFacebookDescription($fbtitlecontent, $fbdescriptioncontent, $fbmsgcontent, $fbimage);
					}
					if($twaccounts) {
						$twmessagecontent = $this->request->getPost("twpublicationcontent");
						$socialmail->twdescription = $socialnet->saveTwitterDescription($twmessagecontent);
					}
					$socialmail->save();
				}
				if(!$mail->type) {
					return $this->response->redirect("mail/source/" .$mail->idMail);
				}
				else {
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
			}
			else {
				foreach ($mail->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
			}
			
		}
		
		$assets = AssetObj::findAllAssetsInAccount($this->user->account);
		if(empty($assets)) {
				$arrayAssets = array();
		}
		else {
			foreach ($assets as $a) {
				$arrayAssets[] = array ('thumb' => $a->getThumbnailUrl(), 
									'image' => $a->getImagePrivateUrl(),
									'title' => $a->getFileName(),
									'id' => $a->getIdAsset());								
			}
		}
		$this->view->setVar('assets', $arrayAssets);
		$this->view->MailForm = $form;
	}
	
	public function savecontentAction($idMail)
	{
		$log = $this->logger;
		if ($this->request->isPost()) {
			$mail = Mail::findFirst(array(
				"conditions" => "idMail = ?1 AND idAccount = ?2",
				"bind" => array(1 => $idMail,
								2 => $this->user->account->idAccount)
			));
			$content = $this->request->getPost("editor");
			if($mail && !empty($content)) {
				$objMail = Mailcontent::findFirst(array(
					"conditions" => "idMail = ?1",
					"bind" => array(1 => $mail->idMail)
				));

				if ($objMail) {
					$text = $objMail->plainText;
				}
				else  {
					$text = null;
				}
				
				if ($mail->wizardOption == 'source' || $mail->wizardOption == 'setup') {
					$wizardOption = 'source';
				}
				else{
					$wizardOption = $mail->wizardOption;
				}
				
				$mailContent = new Mailcontent();
				$content = $this->request->getPost("editor");
				$mailContent->idMail = $idMail;
				$mailContent->content = $content;
				$mailContent->plainText = $text;
				
				$mail->type = "Editor";
				$mail->wizardOption = $wizardOption;
				
				if(!$mailContent->save() || !$mail->save()) {
					$log->log('No guardo');
					return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 500 , 'failed');
				}
			}
			else {
				$log->log('No existe el mail o contenido');
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 500 , 'failed');
			}
		}
	}
	
	public function savetmpdataAction()
	{
		$log = $this->logger;
		if($this->request->isPost()){
			$setuptmpdata = new stdClass();
			$setuptmpdata->name = $this->request->getPost('name');
			$setuptmpdata->subject = $this->request->getPost('subject');
			$setuptmpdata->fromName = $this->request->getPost('fromName');
			$setuptmpdata->fromEmail = $this->request->getPost('fromEmail');
			$setuptmpdata->replyTo = $this->request->getPost('replyTo');
			$setuptmpdata->fbtitlecontent = $this->request->getPost('fbtitlecontent');
			$setuptmpdata->fbdescriptioncontent = $this->request->getPost('fbdescriptioncontent');
			$setuptmpdata->fbmessagecontent = $this->request->getPost('fbmessagecontent');
			$setuptmpdata->twpublicationcontent = $this->request->getPost('twpublicationcontent');
			$setuptmpdata->facebookaccounts = $this->request->getPost("facebookaccounts");
			$setuptmpdata->twitteraccounts = $this->request->getPost("twitteraccounts");
			$_SESSION['tmpData'] = $setuptmpdata;
		}
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
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$objTemplate = Template::findFirst(array(
				"conditions" => "idTemplate = ?1",
				"bind" => array(1 => $idTemplate)
			));
		
			if ($objTemplate) {
				$this->view->setVar('objMail', $objTemplate->content);
			}
			else {
				$objMail = Mailcontent::findFirst(array(
					"conditions" => "idMail = ?1",
					"bind" => array(1 => $mail->idMail)
				));

				if ($objMail) {
					$text = $objMail->plainText;
					$this->view->setVar('objMail', $objMail->content);
				}
				else  {
					$text = null;
					$this->view->setVar('objMail', 'null');
				}
			}
			
			if ($this->request->isPost()) {
				if ($mail->wizardOption == 'source' || $mail->wizardOption == 'setup') {
					$wizardOption = 'source';
				}
				else{
					$wizardOption = $mail->wizardOption;
				}
				
				$mailContent = new Mailcontent();
				$content = $this->request->getPost("editor");
//				$log->log($content);
//				$log->log('Analytics: ' . $analytics);				
				$mailContent->idMail = $idMail;
				$mailContent->content = $content;
				$mailContent->plainText = $text;
				
				$mail->type = "Editor";
				$mail->wizardOption = $wizardOption;
				
				if(!$mailContent->save()) {
//					$log->log("No guarda");
					foreach ($mailContent->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
				else if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
				}
			}		
		}
	}
	
	public function contenteditorAction() 
	{
		$this->view->setVar('objMail', 'null');
	}
	
	public function editor_frameAction($idMail = NULL, $idTemplate = null) 
	{
		$log = $this->logger;
		
		if (!$this->request->isPost()) {
			
			$assets = AssetObj::findAllAssetsInAccount($this->user->account);
			if(empty($assets)) {
					$arrayAssets = array();
			}
			else {
				foreach ($assets as $a) {
					$arrayAssets[] = array ('thumb' => $a->getThumbnailUrl(), 
										'image' => $a->getImagePrivateUrl(),
										'title' => $a->getFileName(),
										'id' => $a->getIdAsset());								
				}
			}
			$this->view->setVar('assets', $arrayAssets);
		}
		else {
			$this->view->setVar('assets', $arrayAssets);
		}
		
		$arrayCf = array();
		
		$cfs = Customfield::findAllCustomfieldNamesInAccount($this->user->account);
		
		foreach ($cfs as $cf) {
			$linkname = strtoupper(str_replace(array ("á", "é", "í", "ó", "ú", "ñ", " ", "&", ), 
											   array ("a", "e", "i", "o", "u", "n", "_"), $cf[0]));
			
			$arrayCf[] = array('originalName' => ucwords($cf[0]), 'linkName' => $linkname);
		}

		$this->view->setVar('cfs', $arrayCf);
		
		if($idMail) {
			$this->view->setVar('idMail', $idMail);
		}
		
		$this->logger->log("IdTemplate: {$idTemplate}");
		
		if ($idTemplate != null) {
			$objTemplate = Template::findFirst(array(
				"conditions" => "idTemplate = ?1",
				"bind" => array(1 => $idTemplate)
			));
			
			$this->logger->log('Entra');
			
			if ($objTemplate) {
				$this->view->setVar('objMail', $objTemplate->content);
			}
			else {
				$this->view->setVar('objMail', 'null');
			}
		}
		else {
			$this->view->setVar('objMail', 'null');
		}
	}
	
	public function templateAction($idMail)
	{
		$mail = $this->validateProcess($idMail);
		
		if ($mail) {
			$this->view->setVar('mail', $mail);
			
			$templates = Template::findGlobalsAndPrivateTemplates($this->user->account);
			
			$arrayTemplate = array();
			foreach ($templates as $template) {
				$templateInfo = array(
					"id" => $template->idTemplate, 
					"name" => $template->name, 
					"content" => $template->content,
					"html" => $template->contentHtml,
					"idAccount" => $template->idAccount
				);

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
				$mailContentExist->content = html_entity_decode($mailContentExist->content);
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
			$cfs = Customfield::findAllCustomfieldNamesInAccount($this->user->account);
		
			foreach ($cfs as $cf) {
				$linkname = strtoupper(str_replace(array ("á", "é", "í", "ó", "ú", "ñ", " ", "&", ), 
												   array ("a", "e", "i", "o", "u", "n", "_"), $cf[0]));

				$arrayCf[] = array('originalName' => ucwords($cf[0]), 'linkName' => $linkname);
			}

			$this->view->setVar('cfs', $arrayCf);
			$this->view->MailForm = $form;
		}
	}
	
	public function contenthtmlAction($idMail = null)
	{
		if ($idMail != null) {
			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			));
			
			if ($mail) {
				$mailContentExist = Mailcontent::findFirst(array(
					"conditions" => "idMail = ?1",
					"bind" => array(1 => $idMail)
				));

				if ($mailContentExist) {
					$mailContentExist->content = html_entity_decode($mailContentExist->content);
					$this->view->setVar("mailContent", $mailContentExist);
					$form = new MailForm($mailContentExist);
				}
				else {
					$mailContent = new Mailcontent();
					$form = new MailForm($mailContent);
				}

				$this->view->setVar('mail', $mail);
			}
		}
		
		$cfs = Customfield::findAllCustomfieldNamesInAccount($this->user->account);
		foreach ($cfs as $cf) {
			$linkname = strtoupper(str_replace(array ("á", "é", "í", "ó", "ú", "ñ", " ", "&", ), 
											   array ("a", "e", "i", "o", "u", "n", "_"), $cf[0]));
			$arrayCf[] = array('originalName' => ucwords($cf[0]), 'linkName' => $linkname);
		}
		$this->view->setVar('cfs', $arrayCf);
		$content = $this->session->get("{$this->user->account->idAccount}-{$this->user->idUser}-createMail");
		
		if ($content) {
			$this->view->setVar('content', $content);
			$this->session->remove("{$this->user->account->idAccount}-{$this->user->idUser}-createMail");
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

				try {
					$this->logger->log('Iniciando importacion de contenido html a traves de una url');
					$getHtml = new LoadHtml();
					$html = $getHtml->gethtml($url, $image, $dir, $account);
					$this->logger->log('Se cargó el contenido exitosamente');
				}
				catch (Exception $e) {
					$this->logger->log("Exception: {$e}");
					$this->flashSession->error("No se pudo conectar con el servidor solicitado, por favor intente más tarde");
					return $this->response->redirect("mail/import/" . $idMail);
				}
				
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
	
	public function importcontentAction()
	{	
		if ($this->request->isPost()) {
			
			$user = $this->user;
			$account = $user->account;

			$url = $this->request->getPost("url");
			$image = $this->request->getPost("image");

			$dir = $this->asset->dir . $account->idAccount . "/images";

			if(!filter_var($url, FILTER_VALIDATE_URL)) {
				$this->logger->log("Error url no válida {$url}");
				return $this->setJsonResponse(array('errors' => 'La url ingresada no es válida, por favor verifique la información'), 400);
			}

			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			} 
			
			try {
				$getHtml = new LoadHtml();
				$html = $getHtml->gethtml($url, $image, $dir, $account);
				
				$this->session->set("{$account->idAccount}-{$user->idUser}-createMail", htmlspecialchars($html, ENT_QUOTES));
						
				return $this->setJsonResponse(array('status' => 'success'), 200);
			}
			catch (Exception $e){
				$this->logger->log("Exception {$e}");
				return $this->setJsonResponse(array('errors' => 'Ha ocurrido un error, contacte al administrador'), 500);
			}
		}
	}
	
	public function plaintextAction($idMail)
	{
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
			
			$mailContent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $mail->idMail)
			));
			
			if ($mail->type == 'Editor') {
				$editorObj = new HtmlObj;
				$editorObj->assignContent(json_decode($mailContent->content));
				$content = $editorObj->render();
			}
			else {
				$content = $mailContent->content;
			}
			
			if ($mailContent->plainText == null) {
				$text = new PlainText();
				$plainText = $text->getPlainText($content);
			}
			else {
				$plainText = $mailContent->plainText;
			}
			
			$this->view->setVar('plaintext', $plainText);
			
			if ($this->request->isPost()) {
				
				$direction = $this->request->getPost('direction');
				
				switch ($direction) {
					case 'plaintext':
						if ($mail->type == 'Editor') {
							$editorObj = new HtmlObj;
							$editorObj->assignContent(json_decode($mailContent->content));
							$content = $editorObj->render();
						}
						else {
							$content = $mailContent->content;
						}
						$text = new PlainText();
						$plainText = $text->getPlainText($content);
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
						break;
				}
			}
		}
		else {
			return $this->response->redirect('mail/source/' . $idMail);
		}
	}
	
	public function trackAction($idMail = null)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2 AND (wizardOption = ?3 OR wizardOption = ?4 OR wizardOption = ?5)',
			'bind' => array(1 => $idMail,
							2 => 'Draft',
							3 => 'source',
							4 => 'target',
							5 => 'schedule')
		));
		
		$content = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if ($mail && $content) {
			switch ($mail->type) {
				case 'Html':
					$html = html_entity_decode($content->content); 
					break;

				case 'Editor':
					$editor = new HtmlObj();
					$editor->assignContent(json_decode($content->content));
					$html = $editor->render();
					break;
			}
			$urlObj = new TrackingUrlObject();
			$links = $urlObj->searchDomainsAndProtocols($html, $content->plainText);
			$this->logger->log(print_r($links, true));

			$this->view->setVar('links', $links);
			$mail->name = substr($mail->name, 0, 24);
			$this->view->setVar('mail', $mail);
			if ($content->googleAnalytics !== null) {
				$analytics = json_decode($content->googleAnalytics);
				$campaignName = $content->campaignName;
				$x = 'true';
			}
			else {
				$campaignName = null;
				$analytics = null;
				$x = 'null';
			}
			$this->view->setVar('analytics', $analytics);
			$this->view->setVar('campaignName', $campaignName);
			$this->view->setVar('x', $x);

			if ($this->request->isPost()) {
				$campaignName = $this->request->getPost('campaignName');
				if ($campaignName == null) {
					$campaignName = $mail->name;
				}
				else if (strlen($campaignName) > 25) {
					$this->flashSession->error('El nombre de campaña para google analytics es demasiado largo, debe tener máximo 15 caracteres');
					return $this->response->redirect('mail/track/' . $idMail);
				}
				$googleAnalytics = $this->request->getPost("googleAnalytics");
				$links = $this->request->getPost("links");
				$direction = $this->request->getPost("direction");

				if ($googleAnalytics == 'googleAnalytics') {
					$content->campaignName = $campaignName;
					$content->googleAnalytics = json_encode($links);
				}
				else {
					$content->campaignName = null;
					$content->googleAnalytics = null;
				}
				if (!$content->save()) {
					foreach ($content->getMessages() as $msg) {
						$this->logger->log('Error: ' . $msg);
					}
					$this->flashSession->error('Ha ocurrido un error mientras se guardaba el seguimiento de Google Analytics');
					return $this->response->redirect('mail/track/' . $idMail);
				}
				$this->routeRequest('track', $direction, $mail->idMail);
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
			
			if (count($dbases) > 0) {
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
				$this->view->setVar('db', true);
			}
			else {
				$this->view->setVar('db', false);
			}
			
			
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
					
					$target->setIdsDbase(implode(",", $idDbases));
					$target->setIdsContactlist(implode(",", $idContactlists));
					$target->setIdsSegment(implode(",", $idSegments));
					
					$target->createTargetObj();
					
					$response = $target->getTargetObject();
					
					if ($response == null) {
						$this->flashSession->error("No hay contactos registrados, por favor seleccione otra base de datos, lista o segmento");
					}
					else {
						$mail->target = $response->target;
						$mail->totalContacts = $response->totalContacts;
						$mail->wizardOption = 'target';
						
						if (!$mail->save()) {
							foreach ($mail->getMessages() as $msg) {
								throw new \Exception("Exception while saving target mail: {$msg}");
							}	
						}

						$this->routeRequest('target', $direction, $mail->idMail);
					}
				}
				catch (InvalidArgumentException $e) {
					$this->logger->log('Error while saving targetObj in db');
					$this->logger->log('InvalidArgumentException: [' . $e . ']');
				}
				catch (Exception $e) {
					$this->logger->log('Exception: [' . $e . ']');
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
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2 AND status = ?3 AND (wizardOption = ?4 OR wizardOption = ?5)',
			'bind' => array(1 => $idMail,
							2 => $account->idAccount,
							3 => 'Draft',
							4 => 'target',
							5 => 'schedule')
		));
		if ($mail) {
			$mails = Mail::find(array(
				'conditions' => 'idAccount = ?1 AND status = ?2',
				'bind' => array(1 => $account->idAccount,
								2 => 'Sent')
			));
			
			$links = Maillink::find(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $account->idAccount)
			));
			
			$this->view->setVar('mail', $mail);
			$this->view->setVar('mails', $mails);
			$this->view->setVar('links', $links);
			
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
						'type' => 'email',
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
						'criteria' => $byClick
					);
					$targetJson->filter = $filter;
				}
				else if ($byExclude !== null ) {
					$filter = array(
						'type' => 'mailExclude',
						'criteria' => $byExclude
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
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2 AND (wizardOption = ?3 OR wizardOption = ?4)',
			'bind' => array(1 => $idMail,
							2 => 'Draft',
							3 => 'target',
							4 => 'schedule')
		));
		
		if ($mail) {
			if($mail->scheduleDate < time()){
				$mail->scheduleDate = time() + 60;
			}
			$this->view->setVar('mail', $mail);
			if ($this->request->isPost()) {
				$direction = $this->request->getPost('direction');
				$schedule = $this->request->getPost('schedule');
				$date = $this->request->getPost('dateSchedule');
				
				if ($schedule == 'rightNow') {
					$mail->scheduleDate = time();
				}
				else if ($schedule == 'after' || $date !== "") {
					list($day, $month, $year, $hour, $minute) = preg_split('/[\s\/|-|:]+/', $date);
					$dateTimestamp = mktime($hour, $minute, 0, $month, $day, $year);
					
					if($dateTimestamp < time()) {
						$this->flashSession->error("la fecha: <span style='color: #fff7f8;'>" . $date . " </span> que ha ingresado ya ha pasado, por favor verifique la información");
						return false;
					}
					$mail->scheduleDate = $dateTimestamp;
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
					return $this->response->redirect('mail/schedule/' . $idMail);
				}
				
				if ($schedule == 'after' || $date !== "") {
					$mailSchedule = new MailScheduleObj($mail);
					$scheduled = $mailSchedule->scheduleTask();
					if (!$scheduled) {
						$this->flashSession->error('Ha ocurrido un error');
						return $this->response->redirect('mail/schedule/' . $idMail);
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
		$log = $this->logger;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND status = ?2',
			'bind' => array(1 => $idMail,
							2 => 'Draft')
		));
		if ($mail) {
			$target = $this->findTargetsName($mail->target);
			$this->view->setVar('mail', $mail);
			$this->view->setVar('target', $target);
		}
		else {
			return $this->response->redirect('mail/source/' . $idMail);
		}
	}
	
	protected function findTargetsName($target)
	{
		$t = json_decode($target);
		$ids = implode(", ", $t->ids);
		//{"destination":"dbases","ids":["7","8"],"filter":{"type":"open","criteria":["week11","week12"]}}
		
		switch ($t->destination) {
			case 'dbases':
				$query = $this->modelsManager->createQuery("SELECT name FROM Dbase WHERE idDbase IN (" . $ids . ")");
				$result = $query->execute();
				break;
			
			case 'contactlists':
				$query = $this->modelsManager->createQuery("SELECT name FROM Contactlist WHERE idContactlist IN (" . $ids . ")");
				$result = $query->execute();
				break;
				
			case 'segments':
				$query = $this->modelsManager->createQuery("SELECT name FROM Segment WHERE idSegment IN (" . $ids . ")");
				$result = $query->execute();
				break;
		}
		return $result;
	}
	
	public function previeweditorAction($idMail)
	{
		$content = $this->request->getPost("editor");
		$this->session->remove('htmlObj');
		$url = $this->url->get('mail/previewmail');		
		$editorObj = new HtmlObj(true, $url, $idMail);
		$editorObj->assignContent(json_decode($content));
		$this->session->set('htmlObj', $editorObj->render());
		
		return $this->setJsonResponse(array('status' => 'Success'), 201, 'Success');
	}
	
	public function previewindexAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 and idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $account->idAccount)
		));
		
		if ($mail) {
			$content = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $mail->idMail)
			));
			
			if ($content) {
				switch ($mail->type) {
					case 'Editor':
						$editorObj = new HtmlObj();
						$editorObj->assignContent(json_decode($content->content));
						$response = $editorObj->render();
						break;
					case 'Html':
						$response = html_entity_decode($content->content);
						break;			
				}
				return $this->setJsonResponse(array('preview' => $response));
			}
			else {
				return $this->setJsonResponse(array('status' => 'error'), 401, 'Error');
			}
		}
		else {
			return $this->setJsonResponse(array('status' => 'error'), 401, 'Error');
		}
	}
	
	public function previewdataAction()
	{
		$htmlObj = $this->session->get('htmlObj');
		$this->session->remove('htmlObj');

		$this->view->disable();
		return $this->response->setContent($htmlObj);
	}
	
	public function previewhtmlAction($idMail)
	{
		$html = $this->request->getPost("html");
		$this->session->remove('htmlObj');
		
		if (trim($html) === '' || $html == null || empty($html)) {
			return $this->setJsonResponse(array('status' => 'Error'), 401, 'No hay html que previsualizar por favor verfique la informacion');
		}
		$url = $this->url->get('mail/previewmail');
		$script1 =  '<head>
						<title>Preview</title>
						<script type="text/javascript" src="' . $this->url->get('js/html2canvas.js'). '"></script>
						<script type="text/javascript" src="' . $this->url->get('js/jquery-1.8.3.min.js') .'"></script>
						<script>
							function createPreviewImage(img) {
							console.log(img);
							$.ajax({
								url: "' . $url . '/' . $idMail .'",
								type: "POST",			
								data: { img: img},
								success: function(){}
								});
							}
						</script>';
		
		$script2 = '<script> 
						html2canvas(document.body, { 
							onrendered: function (c) { 
								c.getContext("2d");	
								createPreviewImage(c.toDataURL("image/png"));
							},
							height: 700
						});
				   </script></body>';
		
		$search = array('<head>', '</body>');
		$replace = array($script1, $script2);
		
		$htmlFinal = str_ireplace($search, $replace, $html);
		
		$this->session->set('htmlObj', $htmlFinal);
//		return $this->setJsonResponse(array('response' => $htmlFinal));
	}
	
	public function previewmailAction($idMail)
	{
		$content = $this->request->getPost("img");
//		$this->logger->log("Id: " . $idMail);
//		$this->logger->log("Img: " . $content);
		$imgObj = new ImageObject();
		$imgObj->createFromBase64($content);
		$imgObj->resizeImage(200, 250);
		$newImg = $imgObj->getImageBase64();
		
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		$mail->previewData = $newImg;
		
		if (!$mail->save()) {
			$this->logger->log("Error while saving image base64");
			foreach ($mail->getMessages() as $msg) {
				$this->logger->log("Error: " . $msg);
			}
		}
//		$this->logger->log("NewImg: " . $newImg);
	}
	
	public function previewtemplateAction()
	{
//		$content = $this->request->getPost("img");
////		$this->logger->log("Id: " . $idMail);
////		$this->logger->log("Img: " . $content);
//		$imgObj = new ImageObject();
//		$imgObj->createFromBase64($content);
//		$imgObj->resizeImage(150, 200);
//		$newImg = $imgObj->getImageBase64();
//		
//		$mail = Template::findFirst(array(
//			'conditions' => 'idTemplate = ?1',
//			'bind' => array(1 => $idTemplate)
//		));
//		
//		$mail->previewData = $newImg;
//		
//		if (!$mail->save()) {
//			
//		}
	}


	public function converttotemplateAction($idMail)
	{
		$mail = Mail::findFirstByIdMail($idMail);
		$mailContent = Mailcontent::findFirstByIdMail($idMail);
		
		if ($mail && $mailContent) {
			$name = $this->request->getPost("nametemplate");
		
			$category = $this->request->getPost("category");

			try {
				$template = new TemplateObj();
				$template->setAccount($this->user->account);
				$template->setMail($mail);
				$template->convertMailToTemplate($name, $category, $mailContent);
				$this->flashSession->success("Se ha creado la plantilla a partir del correo exitosamente");
				$this->response->redirect('template');
			}
			catch (InvalidArgumentException $e) {
				$this->flashSession->error("Ha ocurrido un error mientras se creaba una plantilla a partir de un correo, contacte al administrador");
				$this->response->redirect('mail');
				$this->logger->log('Exception: ' . $e);
			}
		}
		else {
			$this->flashSession->success("El correo base no existe por favor verifique la información");
			$this->response->redirect('mail');
		}
	}
	
	public function confirmAction($idMail)
	{
		$schedule = Mailschedule::findFirstByIdMail($idMail);
		$mail = Mail::findFirstByIdMail($idMail);
		
		if($schedule) {
			$mail->status = 'Scheduled';
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				return $this->response->redirect('mail/preview/' . $idMail);
			}
			$schedule->confirmationStatus = 'Yes';
			if(!$schedule->save()){
				foreach ($schedule->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				return $this->response->redirect('mail/preview/' . $idMail);
			}
			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendSchedulingToParent($idMail);	
			
			return $this->response->redirect("mail/index");
		}
		
		$mail->status = 'Scheduled';
		$mail->startedon = time();
		
		if(!$mail->save()) {
			foreach ($mail->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
			return $this->response->redirect('mail/preview/' . $idMail);
		}
		
		$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		$commObj->sendPlayToParent($idMail);
		
		return $this->response->redirect("mail/index");
	}
	
	public function confirmmailAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		$mailcontent = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if ($mail && $mailcontent) {
			
		}
		
		$schedule = Mailschedule::findFirstByIdMail($idMail);
		$mail = Mail::findFirstByIdMail($idMail);
		
		if($schedule) {
			$mail->status = 'Scheduled';
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				return $this->response->redirect('mail/preview/' . $idMail);
			}
			$schedule->confirmationStatus = 'Yes';
			if(!$schedule->save()){
				foreach ($schedule->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				return $this->response->redirect('mail/preview/' . $idMail);
			}
			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendSchedulingToParent($idMail);	
			
			return $this->response->redirect("mail/index");
		}
		
		$mail->status = 'Scheduled';
		$mail->startedon = time();
		
		if(!$mail->save()) {
			foreach ($mail->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
			return $this->response->redirect('mail/preview/' . $idMail);
		}
		
		$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		$commObj->sendPlayToParent($idMail);
		
		return $this->response->redirect("mail/index");
	}
	
	public function stopAction($direction, $idMail)
	{
		$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail && $mail->status == 'Scheduled') {
			$this->stopScheduledTask($mail);
			$commObj->sendSchedulingToParent($idMail);
		}
		else {
			$commObj->sendPausedToParent($idMail);
		}
		
		switch ($direction) {
			case 'programming':
				$route = 'programmingmail/index';
				break;
			case 'manage':
				$route = 'programmingmail/manage';
				break;
			case 'index':
				$route = 'mail/index';
				break;
		}
		return $this->response->redirect($route);
	}
	
	public function playAction($idMail)
	{
		$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		$commObj->sendPlayToParent($idMail);
		
		return $this->response->redirect("mail/index");
	}
	
	
	public function sendtestAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if ($this->request->isPost() && $mail) {
			$mailContent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			));
			
			$target = $this->request->getPost("target");
			$msg = $this->request->getPost("message");
			
			if (trim($target) === '') {
				$this->flashSession->error("No ha enviado una direccion de correo válida por favor verifique la información");
				return $this->response->redirect('mail/target/' . $idMail);
			}
			
			$recipients = explode(', ', $target);
			
			$emails = array();
			foreach ($recipients as $recipient) {
				$r = trim($recipient);
				if (!empty($r) && !in_array($r, $emails) && filter_var($r, FILTER_VALIDATE_EMAIL)) {
					$emails[] = $r;
				}
			}
			
			if (count($emails) == 0) {
				$this->flashSession->error("No ha enviado una direccion de correo válida por favor verifique la información");
				return $this->response->redirect('mail/target/' . $idMail);
			}
			
			$transport = Swift_SendmailTransport::newInstance();
			$swift = Swift_Mailer::newInstance($transport);
			
			$account = $this->user->account;
			$domain = Urldomain::findFirstByIdUrlDomain($account->idUrlDomain);
			
			$testMail = new TestMail();
			$testMail->setAccount($account);
			$testMail->setDomain($domain);
			$testMail->setUrlManager($this->urlManager);
			$testMail->setMail($mail);
			$testMail->setMailContent($mailContent);
			$testMail->setPersonalMessage($msg);
			
			$testMail->load();
			
			$subject = $mail->subject;
			$from = array($mail->fromEmail => $mail->fromName);
			$content = $testMail->getBody();
			$text = $testMail->getPlainText();
			$replyTo = $mail->replyTo;
			
			foreach ($emails as $email) {
				$to = array($email => 'Nombre Apellido');
				
				$message = new Swift_Message($subject);
				$message->setFrom($from);
				$message->setTo($to);
				$message->setBody($content, 'text/html');
				$message->addPart($text, 'text/plain');
				
				if ($replyTo != null) {
					$message->setReplyTo($replyTo);
				}
				
				$sendMail = $swift->send($message, $failures);
				
				if (!$sendMail){
					$this->logger->log("Error while sending test mail: " . print_r($failures));
				}
			}
			if ($sendMail){
				$this->flashSession->success("Se ha enviado el mensaje de prueba exitosamente");
				return $this->response->redirect('mail/target/' . $idMail);
			}
			
			$this->flashSession->error("Ha ocurrido un error mientras se intentaba enviar el correo de prueba, contacte al administrador");
			return $this->response->redirect('mail/target/' . $idMail);
		}
	}

	public function cancelAction($idMail)
	{
		try {
			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendCancelToParent($idMail);
		}
		catch(\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
		}
		catch(\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
		}
		
		return $this->response->redirect("mail/index");
	}

	protected function stopScheduledTask(Mail $mail)
	{
		$scheduled = Mailschedule::findFirstByIdMail($mail->idMail);
		if(!$scheduled->delete()) {
			foreach ($scheduled->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
		}
		$mail->status = "Draft";
		if(!$mail->save()) {
			foreach ($mail->getMessages() as $msg) {
				$this->flashSession->error($msg);
			}
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
					$go = 'mail/track/';
					break;
				case 'track':
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
				case 'track':
					$go = 'mail/plaintext/';
					break;
				case 'target':
					$go = 'mail/track/';
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
	
	public function newAction($idMail = null)
	{
		$account = $this->user->account;
		$dbases = Dbase::findByIdAccount($account->idAccount);
		
		if (count($dbases) > 0) {
			$array = array();
			foreach ($dbases as $dbase) {
				$array[] = $dbase->idDbase;
			}

			$idsDbase = implode(",", $array);

			$phql1 = "SELECT Dbase.name AS Dbase, Contactlist.idContactlist, Contactlist.name FROM Dbase JOIN Contactlist ON (Contactlist.idDbase = Dbase.idDbase) WHERE Dbase.idDbase IN (". $idsDbase .")";
			$phql2 = "SELECT * FROM Segment WHERE idDbase IN (". $idsDbase .")";

			$contactlists = $this->modelsManager->executeQuery($phql1);
			$segments = $this->modelsManager->executeQuery($phql2);

			$mails = Mail::find(array(
				'conditions' => 'idAccount = ?1 AND status = ?2',
				'bind' => array(1 => $account->idAccount,
								2 => 'Sent')
			));

			$links = Maillink::find(array(
				'conditions' => 'idAccount = ?1',
				'bind' => array(1 => $account->idAccount)
			));
			
			if($idMail != null) {
				$mail = Mail::findFirst(array(
					'conditions' => 'idAccount = ?1 AND idMail = ?2',
					'bind' => array(1 => $account->idAccount,
									2 => $idMail)
				));
				$this->view->setVar('mail', $mail);
			}


			$this->view->setVar('mails', $mails);
			$this->view->setVar('links', $links);
			$this->view->setVar('dbases', $dbases);
			$this->view->setVar('contactlists', $contactlists);
			$this->view->setVar('segments', $segments);
			$this->view->setVar('db', true);
		}
		else {
			$this->view->setVar('db', false);
		}
		
//		$mail = Mail::findFirst(array(
//			'conditions' => 'idMail = ?1',
//			'bind' => array(1 => $idMail)
//		));
//		
//		if ($mail) {
//			$MailWrapper = new MailWrapper();
//			
//			$MailWrapper->setMail($mail);
//			$response = $MailWrapper->getResponse();
//			$this->setJsonResponse($response->key, $response->data, $response->code);
//		}
	}
}
