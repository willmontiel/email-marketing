<?php
class ScheduledmailController extends ControllerBase
{
	protected function router($action)
	{
		switch ($action) {
			case 'index':
				return $this->response->redirect('scheduledmail/index');
				break;
			case 'manage':
				return $this->response->redirect('scheduledmail/manage');
				break;
			default :
				return $this->response->redirect('scheduledmail/index');
				break;
		}
	}

	public function indexAction()
	{
		$idAccount = $this->user->account->idAccount;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => Mail::find("idAccount = $idAccount AND status != 'Draft' AND deleted == 0"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function stopAction($action, $idMail)
	{
		$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		
		$communication->sendPausedToParent($idMail);
		
		$this->router($action);
	}

	public function playAction($action, $idMail)
	{
		$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		
		$communication->sendPlayToParent($idMail);
		
		$this->router($action);
	}

	public function cancelAction($action, $idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if ($mail->status == 'Scheduled') {
			$mail->status = 'Cancelled';
			
			if (!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
			}
			else {
				$this->flashSession->warning("Se ha cancelado el correo exitosamente, recuerde que esta acción no se puede revertir");
			}
		}
		else {
			$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
		
			$communication->sendCancelToParent($idMail);
		}
		
		$this->router($action);
	}
	
	public function manageAction()
	{
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => Mail::find("status != 'Draft' ORDER BY scheduleDate"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}	
}