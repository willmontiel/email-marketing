<?php
class MailController extends ControllerBase
{
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
					"action" => "content",
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
	
	public function contentAction($idMail)
	{
		$this->view->setVar('idMail', $idMail);
	}

	public function editorAction($idMail) 
	{
		
	}
	public function targetAction()
	{
		
	}
	
	public function scheduleAction()
	{
		
	}
}