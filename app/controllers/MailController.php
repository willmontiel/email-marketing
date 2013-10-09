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
				$this->dispatcher->forward(array(
					"action" => "font",
					"params" => array($mail->idMail)
				));
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
	
	public function fontAction($idMail = null)
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
			$this->view->setVar('idMail', $idMail);
			
			$mail = new Mail();
			$form = new MailForm($mail);
			
			if ($this->request->isPost()) {
				$form->bind($this->request->getPost(), $mail);

				$idMail = $form->getValue('idMail');
			}
			
			$this->view->MailForm = $form;
		}
	}

	public function targetAction()
	{
		
	}
	
	public function scheduleAction()
	{
		
	}
}