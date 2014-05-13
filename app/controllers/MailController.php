<?php
require_once "../app/library/swiftmailer/lib/swift_required.php";

class MailController extends ControllerBase
{
	protected $image_map = array();
	
	public function savemailAction($mails = null, $idMail = null)
	{
		$account = $this->user->account;
		$mail = null;
		$mailcontent = null;
		
		if ($idMail != null) {
			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1 AND idAccount = ?2',
				'bind' => array(1 => $idMail,
								2 => $account->idAccount)
			));
			
			if (!$mail) {
				return $this->setJsonResponse(array('errors' => 'No se ha encontrado el correo por favor verifique la información'), 404, 'Mail not found!');
			}
			
			$mailcontent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $mail->idMail)
			));
		}
		
		if ($this->request->isPost() || $this->request->isPut()) {
			$contentsraw = $this->request->getRawBody();
			$contentsT = json_decode($contentsraw);
			$this->logger->log('Turned it into this: [' . print_r($contentsT, true) . ']');
			$this->logger->log('idMail: ' . $idMail);
			$content = $contentsT->mail;
			
			$MailWrapper = new MailWrapper();
			$MailWrapper->setMail($mail);
			$MailWrapper->setContent($content);
			$MailWrapper->setAccount($account);
			$MailWrapper->setSocialsKeys($this->fbapp->iduser, $this->fbapp->token, $this->twapp->iduser, $this->twapp->token);
			if ($mailcontent) {
				$MailWrapper->setMailContent($mailcontent);
			}
			
			try {	
				$MailWrapper->processDataForMail();
				$MailWrapper->saveMail();
				$response = $MailWrapper->getResponse();
				
				if ($idMail == null) {
					$this->traceSuccess("Create mail, idMail: {$response->data['id']}");
				}
				else {
					$this->traceSuccess("Update mail, idMail: {$idMail}");
				}
				
				return $this->setJsonResponse(array($response->key => $response->data), $response->code);
			}
			catch (InvalidArgumentException $e) {
				if ($idMail == null) {
					$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
				}
				else {
					$this->traceFail("Error update mail, idMail: {$idMail}");
				}
				
				$this->logger->log("InvalidArgumentException: {$e}");
				$response = $MailWrapper->getResponseMessageForEmber();
				return $this->setJsonResponse(array($response->key => $response->message), $response->code);
			}
			catch (Exception $e) {
				if ($idMail == null) {
					$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
				}
				else {
					$this->traceFail("Error update mail, idMail: {$idMail}");
				}
				
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('errors' => 'Ha ocurrido un error contacte al administrador'), 500);
			}
		}	
		
		if ($mails != null) {
			$MailWrapper = new MailWrapper();
			$MailWrapper->setMail($mail);
			$MailWrapper->setAccount($account);
			$MailWrapper->setSocialsKeys($this->fbapp->iduser, $this->fbapp->token, $this->twapp->iduser, $this->twapp->token);
			$MailWrapper->setMailContent($mailcontent);
			$response = $MailWrapper->getResponse();
			return $this->setJsonResponse(array($response->key => $response->data), $response->code);
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
			$mailClone->name = substr($mail->name . " (copia)", 0, 79);
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
				return $this->response->redirect("mail/list");
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
					return $this->response->redirect("mail/list");
				}
			}
			$this->db->commit();
			return $this->response->redirect("mail/compose/{$mailClone->idMail}");
		}
		
		$this->flashSession->error('Un error no permitió duplicar el correo');
		return $this->response->redirect("mail/list");
	}
	
	public function deleteAction($idMail)
	{
		try {
			$process = new ProcessMail();
			$process->setAccount($this->user->account);
			$process->setUser($this->user);
			$process->deleteMail($idMail);
		} 
		catch (\InvalidArgumentException $e) {
			$this->flashSession->error($e->getMessage());
			$this->logger->log("Exception: Error while deleting mail, {$e}");
			$this->traceFail("Error deleting mail, idMail: {$idMail}");
			return $this->response->redirect("mail/list");
		}
		$this->traceSuccess("Mail deleted, idMail: {$idMail}");
		$this->flashSession->warning("Se ha eliminado el correo exitosamente");
		return $this->response->redirect("mail/list");
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
			$fbids = (isset($netwids->facebook)) ? $netwids->facebook : array();
			$twids = (isset($netwids->twitter)) ? $netwids->twitter : array();
			$this->view->setVar('fbids', $fbids);
			$this->view->setVar('twids', $twids);
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

			$fbsocials = $socialnet->getSocialIdNameArray($socialnet->findAllFacebookAccountsByUser());
			$twsocials = $socialnet->getSocialIdNameArray($socialnet->findAllTwitterAccountsByUser());
			
			$this->view->setVar('fbsocials', $fbsocials);
			$this->view->setVar('twsocials', $twsocials);
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
				
				if ($idMail == null) {
					$this->traceSuccess("Create mail, idMail: {$mail->idMail}");
				}
				else {
					$this->traceSuccess("mail updated, idMail: {$idMail}");
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
				if ($idMail == null) {
					$this->traceSuccess("Error creating mail, idMail: {$mail->idMail}");
				}
				else {
					$this->traceSuccess("Error updating mail, idMail: {$idMail}");
				}
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
					if (empty($objMail->content)) {
						$objMailContent = 'null';
					}
					else {
						$objMailContent = $objMail->content;
					}
				}
				else  {
					$text = null;
					$objMailContent = 'null' ;
				}
				$this->view->setVar('objMail', $objMailContent);
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
	
	public function contenteditorAction($idMail = null, $idTemplate = null) 
	{
		$account = $this->user->account;
		
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $account->idAccount)
		));
		
		$objTemplate = Template::findFirst(array(
			"conditions" => "idTemplate = ?1",
			"bind" => array(1 => $idTemplate)
		));
		
		$mailcontent = false;
		
		if ($mail) {
			$mailcontent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $mail->idMail)
			));
			
			$this->view->setVar('mail', $mail);
		}
		
		if ($objTemplate && ($objTemplate->idAccount == $account->idAccount || $objTemplate->idAccount == null)) {
			if (empty($objTemplate->content)) {
				$objContent = 'null';
			}
			else {
				$objContent = $objTemplate->content;
			}
		}
		else if($mailcontent) {
			$text = $mailcontent->plainText;
			if (empty($mailcontent->content)) {
				$objContent = 'null';
			}
			else {
				$objContent = $mailcontent->content;
			}
		}
		else {
			$text = null;
			$objContent = 'null';
		}
		
		$this->view->setVar('objMail', $objContent);
		
		if ($this->request->isPost()) {
			$this->db->begin();
			
			if (!$mail) {
				$mail = new Mail();
				$mail->idAccount = $this->user->idAccount;
				$mail->status = 'Draft';
				$mail->wizardOption = 'setup';
				$mail->deleted = 0;
			}
			
			$content = $this->request->getPost('editor');
			
//			$this->logger->log("Editor: {$content}");
			$mail->type = 'Editor';
				
			if (!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log("Error while saving mail {$msg}");
				}
				$this->db->rollback();
				$this->traceSuccess("Error creating mail from template");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error contacte al administrador'), 500 , 'failed');
			}
			
			if (!$mailcontent) {
				$mailcontent = new Mailcontent();
				$mailcontent->idMail = $mail->idMail;
				$mailcontent->content = $content;
				
				$editorObj = new HtmlObj;
				$editorObj->assignContent(json_decode($content));
				$contentmail = $editorObj->render();
				
				$text = new PlainText();
				$plainText = $text->getPlainText($contentmail);
				$mailcontent->plainText = $plainText;
			}
			else {
				$mailcontent->content = $content;
				$mailcontent->plainText = $text;
			}
			
			if (!$mailcontent->save()) {
				foreach ($mailcontent->getMessages() as $msg) {
					$this->logger->log("Error while saving content mail {$msg}");
				}
				$this->db->rollback();
				$this->traceSuccess("Error creating mail from template");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error contacte al administrador'), 500 , 'failed');
			} 
			
			$this->db->commit();
			
			if ($idMail == 'null') {
				$this->traceSuccess("Mail created from template, idMail: {$mail->idMail}");
			}
			return $this->setJsonResponse(array('msg' => "{$mail->idMail}"), 200);
		}
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
	
	public function contenthtmlAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => "idMail = ?1 AND idAccount = ?2 AND status = 'Draft'",
			'bind' => array(1 => $idMail,
							2 => $account->idAccount)
		));
			
		if (!$mail) {
			return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error contacte con el administrador'), 500);
		}
		
		$mailcontent = Mailcontent::findFirst(array(
			"conditions" => "idMail = ?1",
			"bind" => array(1 => $idMail)
		));

		if ($mailcontent) {
			$content = html_entity_decode($mailcontent->content);
			$this->view->setVar("content", $content);
		}
		
		$this->view->setVar('mail', $mail);
		
		$cfs = Customfield::findAllCustomfieldNamesInAccount($this->user->account);
		foreach ($cfs as $cf) {
			$linkname = strtoupper(str_replace(array ("á", "é", "í", "ó", "ú", "ñ", " ", "&", ), 
											   array ("a", "e", "i", "o", "u", "n", "_"), $cf[0]));
			$arrayCf[] = array('originalName' => ucwords($cf[0]), 'linkName' => $linkname);
		}
		$this->view->setVar('cfs', $arrayCf);
		
		if ($this->request->isPost()) {
			$content = $this->request->getPost("content");
			
			$this->db->begin();
			
			//1. Validamos si ya existe contenido html, de no ser asi se crea uno
			if ($mailcontent) {
				$mc = $mailcontent;
			}
			else {
				$mc = new Mailcontent();
				$mc->idMail = $mail->idMail;
			}
			
			//2. Capturamos el texto plano del contenido html
			$text = new PlainText();
			$plainText = $text->getPlainText($content);
			
			$this->logger->log("Textplain: {$plainText}");
			
			//3. Quitamos todos los scripts para evitar posibles errores en el contenido
			$buscar = array("<script" , "</script>");
			$reemplazar = array("<!-- ", " -->");
			$newContent = str_replace($buscar,$reemplazar, $content);
			
			//4. Escapamos el contenido html y asociamos los valores
			$mc->content = htmlspecialchars($newContent, ENT_QUOTES);
			$mc->plainText = $plainText;
			
			//5. Guardamos mail content
			if(!$mc->save()) {
				foreach ($mc->getMessages() as $msg) {
					$this->logger->log("Error while saving mail html content {$msg}");
				}
				$this->db->rollback();
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error contacte con el administrador'), 500);
			}
			
			//6. Actualizamos el tipo de contenido a Html en mail
			$mail->type = 'Html';
			
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log("Error while saving mail {$msg}");
				}
				$this->db->rollback();
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error contacte con el administrador'), 500);
			}
			$this->db->commit();
			return $this->setJsonResponse(array('msg' => 'success'), 200);
		}
	}
	
	protected function createNewMail($type)
	{
		$mail = new Mail();
		$mail->idAccount = $this->user->account->idAccount;
		$mail->type = $type;
		$mail->status = 'Draft';
		$mail->wizardOption = 'setup';
		$mail->createdon = time();
		$mail->updatedon = time();
		$mail->deleted = 0;

		if (!$mail->save()) {
			foreach ($mail->getMessages() as $msg) {
				$this->logger->log("Error while saving mail {$msg}");
			}
			$this->db->rollback();
			return false;
		}
		return $mail;
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
	
	public function importcontentAction($idMail)
	{	
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $account->idAccount)
		));
		
		$this->view->setVar('mail', $mail);
		
		if (!$mail) {
			return $this->response->redirect('error');
		}
		
		if ($this->request->isPost()) {
			$this->db->begin();
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
				
				$contentmail = Mailcontent::findFirst(array(
					'conditions' => 'idMail = ?1',
					'bind' => array(1 => $mail->idMail)
				));
				
				if (!$contentmail) {
					$contentmail = new Mailcontent();
					$contentmail->idMail = $mail->idMail;
				}
				
				$contentmail->content = $html;
				
				if (!$contentmail->save()) {
					foreach ($contentmail->getMessages() as $msg) {
						$this->logger->log("Error while saving content mail {$msg}");
					}
					throw new Exception('Error while saving content mail');
				}
				
				$mail->type = 'Html';
				
				if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->logger->log("Error while saving content mail {$msg}");
					}
					throw new Exception('Error while updating mail');
				}
				
				$this->db->commit();
				return $this->setJsonResponse(array('status' => 'success'), 200);
			}
			catch (Exception $e){
				$this->db->rollback();
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
					$target->setAccount($this->user->account);
					
					if (!empty($idDbases)) {
						$target->setIdsDbase(implode(",", $idDbases));
					}
					
					if (!empty($idContactlists)) {
						$target->setIdsContactlist(implode(",", $idContactlists));
					}
					
					if (!empty($idSegments)) {
						$target->setIdsSegment(implode(",", $idSegments));
					}
					
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
			'conditions' => "idMail = ?1 AND idAccount = ?2 AND status = 'Draft'",
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
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
		
		return $this->setJsonResponse(array('status' => 'success'), 200);
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
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
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
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		$mailContent = Mailcontent::findFirstByIdMail($idMail);
		
		if ($mail && $mailContent) {
			$name = $this->request->getPost("nametemplate");
			$category = $this->request->getPost("category");
			
			if (empty($name)) {
				$name = "Nueva plantilla";
			}
			
			if (empty($category)) {
				$category = "Mis Templates";
			}
			
			try {
				$template = new TemplateObj();
				$template->setAccount($this->user->account);
				$template->setMail($mail);
				$template->convertMailToTemplate($name, $category, $mailContent);
				$this->flashSession->success("Se ha creado la plantilla a partir del correo exitosamente");
				$this->traceSuccess("Mail converted in template, idMail: {$idMail}");
				$this->response->redirect('template');
			}
			catch (Exception $e) {
				$this->traceFail("Error converting mail in template, idMail: {$idMail}");
				$this->logger->log('Exception: ' . $e);
				$this->flashSession->error("Ha ocurrido un error mientras se creaba una plantilla a partir de un correo, contacte al administrador");
				$this->response->redirect('mail/list');
			}
		}
		else {
			$this->flashSession->success("El correo base no existe por favor verifique la información");
			$this->response->redirect('mail/list');
		}
	}
	
	public function confirmAction($idMail)
	{
		try {
			$schedule = Mailschedule::findFirstByIdMail($idMail);
			$mail = Mail::findFirstByIdMail($idMail);

			if($schedule) {
				$mail->status = 'Scheduled';
				if(!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					$this->traceFail("Error confirming mail, idMail: {$idMail}");
					return $this->response->redirect('mail/preview/' . $idMail);
				}
				
				$schedule->confirmationStatus = 'Yes';
				if(!$schedule->save()){
					foreach ($schedule->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					$this->traceFail("Error confirming mail, idMail: {$idMail}");
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
				$this->traceFail("Error confirming mail, idMail: {$idMail}");
				return $this->response->redirect('mail/preview/' . $idMail);
			}

			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendPlayToParent($idMail);
			$this->traceSuccess("Confirm mail, idMail: {$idMail}");
			
			return $this->response->redirect("mail/index");
		}
		catch (Exception $e) {
			$this->traceFail("Error confirming mail, idMail: {$idMail}");
			$this->logger->log("Exception: Error confiming mail, {$e}");
			return $this->response->redirect('mail/preview/' . $idMail);
		}
	}
	
	public function confirmmailAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		$mailcontent = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		$schedule = Mailschedule::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if (!$mail) {
			$this->logger->log("Error mail not found, user: {$this->user->idUser} / idAccount: {$this->user->account->idAccount}");
			return $this->setJsonResponse(array('error' => 'No se ha encontrado el correo, por favor contacte al administrador'), 500);
		}
		
		if (!$mailcontent) {
			$this->logger->log("Error mailcontent not found, user: {$this->user->idUser} / idAccount: {$this->user->account->idAccount}");
			return $this->setJsonResponse(array('error' => 'No se ha encontrado el contenido del correo, por favor contacte al administrador'), 500);
		}
		
		if (!$schedule) {
			$this->logger->log("Error schedule not found, user: {$this->user->idUser} / idAccount: {$this->user->account->idAccount}");
			return $this->setJsonResponse(array('error' => 'No se ha programado el correo, favor contacte al administrador'), 500);
		}
		
		$status = $this->validateMailStatus($mail, $mailcontent);
		
		if (!$status) {
			return $this->setJsonResponse(array('error' => $this->errorMsg), 400);
		}
		
		$this->db->begin();
		
		try {
			$mail->status = 'Scheduled';
			$mail->startedon = time();
			
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log("Error while updating mail {$msg}");
				}
				throw new Exception("Error while updating scheduleDate in mail");
			}
			
			$schedule->confirmationStatus = 'Yes';
			
			if(!$schedule->save()){
				foreach ($schedule->getMessages() as $msg) {
					$this->logger->log("Error while updating schedule {$msg}");
				}
				throw new Exception("Error while updating status schedule's");
			}
			
			$this->db->commit();
			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendSchedulingToParent($mail->idMail);	
			
			$this->traceSuccess("Confirm mail, idMail: {$idMail}");
			$this->flashSession->success('Se ha programado existosamente el correo');
			return $this->setJsonResponse(array('status' => 'success'), 200);
		}
		catch (Exception $e) {
			$this->db->rollback();
			$this->logger->log("Exception: {$e}");
			$this->logger->log("idUser: {$this->user->idUser} / idAccount: {$this->user->account->idAccount}");
			$this->traceFail("Error confirming mail, idMail: {$idMail}");
			return $this->setJsonResponse(array('error' => 'Ha ocurrido un error por favor contacte al administrador'), 500);
		}
	}
	
	protected function validateMailStatus(Mail $mail, Mailcontent $mailcontent)
	{
		if ($mail->totalContacts == 0) {
			$this->errorMsg = 'La lista, base de datos o segmento seleccionado no contiene contactos, por favor verifique la información';
			return false;
		}
		else if (empty($mail->scheduleDate)) {
			$this->errorMsg = 'El correo aún no tiene fecha de programación, por favor verifique la información';
			return false;
		}
		else if ($mail->deleted != 0) {
			$this->errorMsg = 'El correo que desea enviar no existe, por favor verifique la información';
			return false;
		}
		else if (empty($mail->subject)) {
			$this->errorMsg = 'No se ha configurado el asunto, por favor verifique la información';
			return false;
		}
		else if (empty($mail->fromName)) {
			$this->errorMsg = 'No se ha configurado a nombre de quien se va a enviar por favor verifique la información';
			return false;
		}
		else if (empty($mail->fromEmail)) {
			$this->errorMsg = 'No se ha configurado el correo de origen, por favor verifique la información';
			return false;
		}
		else if (!\filter_var($mail->fromEmail, FILTER_VALIDATE_EMAIL)) {
			$this->errorMsg = 'El correo de origen es incorrecto, por favor verifique la información';
			return false;
		}
		else if (empty($mail->target)) {
			$this->errorMsg = 'No se ha configurado un destino, por favor verifique la información';
			return false;
		}
		else if (empty($mailcontent->content)) {
			$this->errorMsg = 'No hay contenido que enviar, por favor verifique la información';
			return false;
		}
	    else if (empty($mailcontent->plainText)) {
			$this->errorMsg = 'No hay un texto plano, por favor verifique la información';
			return false;
		}
		
		return true;
	}
	
	public function stopAction($direction, $idMail)
	{
		switch ($direction) {
			case 'programming':
				$route = 'programmingmail/index';
				break;
			case 'manage':
				$route = 'programmingmail/manage';
				break;
			default:
			case 'list':
			case 'index':
				$route = 'mail/list';
				break;
		}
		
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail) {
			$this->logger->log("Entra");
			try {
				$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());

				if ($mail->status == 'Scheduled') {
					$this->stopScheduledTask($mail);
					$commObj->sendSchedulingToParent($idMail);
				}
				else {
					$commObj->sendPausedToParent($idMail);
				}
				$this->traceSuccess("Stop mail, idMail: {$idMail}");
				$this->flashSession->warning("Se ha pausado el correo exitosamente");
			}
			catch (Exception $e) {
				$this->logger->log("Exception: Error while stopping send, {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Error stopping mail, idMail: {$idMail}");
				return $this->response->redirect($route);
			}
		}
		else {
			$this->flashSession->error("Ha intentado pausar un envío o correo que no existe, por favor verifique la información");
		}
		return $this->response->redirect($route);
	}
	
	public function playAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail) {
			try {
				$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
				$response = $commObj->sendPlayToParent($idMail);

				if ($response) {
					$this->traceSuccess("Resume send, idMail: {$idMail}");
					$this->flashSession->success("Se ha reanudado el correo exitosamente");
				}
				else {
					$this->flashSession->error("Ha intentado reanudar un correo que nunca inició o no existe, por favor verifique la información");
				}
			}
			catch (Exception $e) {
				$this->logger->log("Exception: Error resuming send, {$e}");
				$this->flashSession->error("Ha ocurrido un error mientras se reanudaba el envío de correo, por favor contacte con el administrador");
				$this->traceFail("Error resuming send, idMail: {$idMail}");
				return $this->response->redirect("mail/list");
			}
		}
		else {
			$this->flashSession->error("Ha intentado reanudar un correo que nunca inició o no existe, por favor verifique la información");
		}
		return $this->response->redirect("mail/list");
	}
	
	
	public function sendtestAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
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
				return $this->response->redirect('mail/compose/' . $idMail);
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
				return $this->response->redirect('mail/compose/' . $idMail);
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

			try {
				$testMail->load();

				$subject = $mail->subject;
				$from = array($mail->fromEmail => $mail->fromName);
				$content = $testMail->getBody();
				$text = $testMail->getPlainText();
				$replyTo = $mail->replyTo;

				foreach ($emails as $email) {
					$to = array($email => $email);

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
						$this->traceFail("Error while sending test, idMail: {$idMail}");
						$this->logger->log("Error while sending test mail: " . print_r($failures));
					}
				}
				if ($sendMail){
					$this->traceSuccess("Send test, idMail: {$idMail}");
					$this->flashSession->success("Se ha enviado el mensaje de prueba exitosamente");
					return $this->response->redirect("mail/compose/{$idMail}");
				}

				$this->flashSession->error("Ha ocurrido un error mientras se intentaba enviar el correo de prueba, contacte al administrador");
				return $this->response->redirect("mail/compose/{$idMail}");
			}
			catch (Exception $e) {
				$this->logger->log("Exception, Error while sending test, {$e}");
				$this->flashSession->error("Ha ocurrido un error mientras se intentaba enviar el correo de prueba, contacte al administrador");
				$this->traceFail("Send test, idMail: {$idMail}");
				return $this->response->redirect("mail/compose/{$idMail}");
			}
		}
	}

	public function cancelAction($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		if ($mail) {
			try {
				$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
				$response = $commObj->sendCancelToParent($idMail);

				if ($response) {
					$this->flashSession->warning("Se ha cancelado el mensaje exitosamente");
					$this->traceSuccess("Cancel mail, idMail: {$idMail}");
				}
				else {
					$this->flashSession->error("Ha intentado cancelar un correo que no existe, por favor verifique la información");
				}
			}
			catch(\Exception $e) {
				$this->logger->log('Exception: [' . $e . ']');
				$this->flashSession->error("Ha ocurrido un error mientras se cancelaba el correo, por favor contacte al administrador");
				$this->traceFail("Error Cancelling mail, idMail: {$idMail}");
			}
		}
		else {
			$this->flashSession->error("Ha intentado cancelar un correo que no existe, por favor verifique la información");
		}
		return $this->response->redirect("mail/list");
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
		else if(!$mail) {
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
	
	public function composeAction($idMail = null)
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
		
		if($idMail != null) {
			$mail = Mail::findFirst(array(
				'conditions' => "idAccount = ?1 AND idMail = ?2 AND status = 'Draft'",
				'bind' => array(1 => $account->idAccount,
								2 => $idMail)
			));
			
			$mailcontent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			));
			
			if ($mailcontent) {
				switch ($mail->type) {
					case 'Html':
						$html = html_entity_decode($mailcontent->content); 
						break;

					case 'Editor':
						$editor = new HtmlObj();
						$editor->assignContent(json_decode($mailcontent->content));
						$html = $editor->render();
						break;
				}
				
				$urlObj = new TrackingUrlObject();
				$linksForTrack = $urlObj->searchDomainsAndProtocols($html, $mailcontent->plainText);
				$this->logger->log(print_r($linksForTrack, true));

				$campaignNameExample = substr($mail->name, 0, 24);
				
				$this->view->setVar('linksForTrack', $linksForTrack);
				$this->view->setVar('nameEx', $campaignNameExample);
				
				if ($mailcontent->googleAnalytics !== null) {
					$analytics = json_decode($mailcontent->googleAnalytics);
					$campaignName = $mailcontent->campaignName;
					
					$this->view->setVar('analytics', $analytics);
					$this->view->setVar('campaignName', $campaignName);
				}
			}
			
			$this->view->setVar('mail', $mail);
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
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e . ']');
		}
	}
}
