<?php
class MailController extends ControllerBase
{
	protected function validateProcess($idMail)
	{
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2 AND status = ?3",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount,
							3 => "Draft")
		)); 
		if ($mail) {
			return true;
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
	
	public function setupAction()
	{
		$mail = new Mail();
		$form = new MailForm($mail);
		
		if ($this->request->isPost()) {
			$form->bind($this->request->getPost(), $mail);
			
			$mail->idAccount = $this->user->account->idAccount;
			$mail->fromEmail = strtolower($form->getValue('fromEmail'));
			$mail->replyTo = strtolower($form->getValue('replyTo'));
			$mail->status = "Draft";
			
            if ($form->isValid() && $mail->save()) {
				$this->response->redirect("mail/source/" .$mail->idMail);
			}
			else {
				foreach ($mail->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				return $this->response->redirect("mail/setup");
			}
			
		}
		$this->view->MailForm = $form;
	}
	
	public function sourceAction($idMail = null)
	{
		$isOk = $this->validateProcess($idMail);
		
		if ($isOk) {
			$this->view->setVar('idMail', $idMail);
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
		$isOk = $this->validateProcess($idMail);
		
		if ($isOk) {
			$mailContent = new Mailcontent();
			$form = new MailForm($mailContent);
			$this->view->setVar('idMail', $idMail);
			
			if ($this->request->isPost()) {
				$form->bind($this->request->getPost(), $mailContent);
				
				$mailContent->idMail = $idMail;
				
				if($form->isValid() && $mailContent->save()) {
					$this->response->redirect("mail/target/" .$idMail);
				}
				else {
					foreach ($mailContent->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					return $this->response->redirect("mail/html/". $idMail);
				}
			}
			
			$this->view->MailForm = $form;
		}
	}

	/**
	 * Funcion que guarda las imagenes de la url cargada
	 * @param type $file
	 */
	protected function downloadimages($file)
	{
	//				$internalNumber = uniqid();
	//				$date = date("ymdHi",time());
	//				$name = "{$idAccount}_{$date}_{$internalNumber}";
	//				
	//				$destiny =  "../assets/" . $idAccount . "/" . $internalName;
	//				copy($_FILES['importFile']['tmp_name'],$destiny);
		
	}
	
	/**
	 * Función que descarga código html desde una url y la guarda en una carpeta determinada en el servidor
	 * @param type $url
	 * @param type $dir
	 * @return type
	 */
	protected function downloadhtml($url, $dir)
	{
		$html = file_get_html($url);
		
		$htmlbase = $html->find('head base');
		if (count($htmlbase) > 0) {
			$htmlbase = $htmlbase[0];
		}
		
		if ($htmlbase) {
			$base = $htmlbase->href;
			$htmlbase->outertext = '';
		}
		
		else {
			$path = pathinfo($dir); 
			$base = $path['dirname'];
		}
		
		foreach($html->find('img') as $element) {
			if (trim($element->src) !== null && trim($element->src) !== '') {
				$oldlocation = $element->src;
				$location = $this->addImageToMap($oldlocation, $base);

				$element->src = $location;
			}
		}
		
		$this->html = $html;
		
		return $html;
		
	}
	
	public function importAction($idMail = null)
	{
		$log = $this->logger;
		$isOk = $this->validateProcess($idMail);
		
		if ($isOk) {
			$this->view->setVar('idMail', $idMail);
			
			if ($this->request->isPost()) {
				$idAccount = $this->user->account->idAccount;
				
				$url = $this->request->getPost("url");
				$image = $this->request->getPost("image");
				
				$dir = "../assets/" . $idAccount;
				if(!filter_var($url, FILTER_VALIDATE_URL)) {
					$this->flashSession->error("La url ingresada no es válida, por favor verifique la información");
					return $this->response->redirect("mail/import/" . $idMail);
				}
				
				if (!file_exists($dir)) {
					mkdir($dir, 0700);
				} 
				
				$html = $this->downloadhtml($url, $dir);
				file_put_contents('downloaded.html', $html);
//				if ($image == "load") {
//					$this->downloadimages($file);
//				}
			}
		}
	}
	
	public function targetAction($idMail = null)
	{
		$isOk = $this->validateProcess($idMail);
		
		if ($isOk) {
			$this->view->setVar('idMail', $idMail);
			
			if ($this->request->isPost()) {
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
				
				$dbases = $this->request->getPost("dbases");
				$contactlists = $this->request->getPost("contactlists");
				$segments = $this->request->getPost("segments");
				
				$sql = "REPLACE INTO mxc (idMail, idContact)";
				
				if ($dbases != null) {
					$dbase = implode(',', $dbases);
					$phql = "SELECT Contact.idContact FROM Contact WHERE Contact.idDbase IN (" . $dbase . ")";
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
					$this->flashSession->error("No hay contactos registrados en el criterio seleccionado, por favor verifique la información");
					return $this->response->redirect("mail/target/" . $idMail);
				}
				$idContacts = $this->returnIds($contacts, $idMail);
				$sql .= $idContacts;
				
				$log->log("este es: ". $sql);
				$db = Phalcon\DI::getDefault()->get('db');

				$db->begin();
				$result = $db->execute($sql);
				if(!$result) {
					$db->rollback();
				}
				$db->commit();
			}
		}
	}
	
	public function deleteAction($idMail)
	{
		$log = $this->logger;
		$time = strtotime("-31 days");
		$mail = Mail::findFirst(array(
			"conditions" => "(idMail = ?1 AND idAccount = ?2 AND finishedon <= ?3) OR status = ?4",
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
}