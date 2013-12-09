<?php
class ProgrammingmailController extends ControllerBase
{
	public function indexAction()
	{
		$idAccount = $this->user->account->idAccount;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => Mail::find("idAccount = $idAccount AND status != 'Draft'"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function stopAction($idMail)
	{
		$communication = new Communication();
		
		$communication->sendPausedToParent($idMail);
		
		return $this->response->redirect('programmingmail');
	}

	public function playAction($idMail)
	{
		$communication = new Communication();
		
		$communication->sendPlayToParent($idMail);
		
		return $this->response->redirect('programmingmail');
	}

	public function cancelAction($idMail)
	{
		$communication = new Communication();
		
		$communication->sendCancelToParent($idMail);
		
		return $this->response->redirect('programmingmail');
	}
	
	public function manageAction()
	{
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => Mail::find("status != 'Draft'"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}	
}