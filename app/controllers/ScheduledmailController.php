<?php
class ScheduledmailController extends ControllerBase
{
	protected function router($action)
	{
		switch ($action) {
			case 'list':
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
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => Mail::find("idAccount = {$this->user->account->idAccount} AND deleted = 0 AND status != 'Draft' ORDER BY scheduleDate"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function stopAction($action, $idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail) {
			try {
				$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
				$communication->sendPausedToParent($idMail);
				$this->flashSession->warning("Se ha pausado el correo exitosamente");
				$this->traceSuccess("Stopping send, idMail: {$idMail}");
			}
			catch (Exception $e) {
				$this->logger->log("Exception: Error while stopping send, idMail: {$idMail}, {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Stopping send, idMail: {$idMail}");
				$this->router($action);
			}
		}
		else {
			$this->flashSession->error("Ha intentado pausar el envío de un correo que no existe, por favor verifique la información");
		}
		$this->router($action);
		
	}

	public function playAction($action, $idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail) {
			try {
				$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
				$communication->sendPlayToParent($idMail);
				$this->traceSuccess("Resuming send, idMail: {$idMail}");
				$this->flashSession->warning("Se ha reanudado el correo exitosamente");
			}
			catch (Exception $e) {
				$this->logger->log("Exception: Error while resuming send, idMail: {$idMail}, {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Resuming send, idMail: {$idMail}");
				$this->router($action);
			}
		}
		else {
			$this->flashSession->error("Ha intentado reanudar el envío de un correo que no existe, por favor verifique la información");
		}
		$this->router($action);
	}

	public function cancelAction($action, $idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $this->user->account->idAccount)
		));
		
		if ($mail) {
			try {
				if ($mail->status == 'Scheduled') {
					$mail->status = 'Cancelled';

					if (!$mail->save()) {
						foreach ($mail->getMessages() as $msg) {
							$this->flashSession->error($msg);
						}
					}
					else {
						$this->flashSession->warning("Se ha cancelado el correo exitosamente, recuerde que esta acción no se puede revertir");
						$this->traceSuccess("Cancel send, idMail: {$idMail}");
					}
				}
				else {
					$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
					$communication->sendCancelToParent($idMail);
					$this->flashSession->warning("Se ha cancelado el correo exitosamente, recuerde que esta acción no se puede revertir");
					$this->traceSuccess("Cancel send, idMail: {$idMail}");
				}
			}
			catch (Exception $e) {
				$this->logger->log("Exception: Error while canceling send, idMail: {$idMail}, {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				$this->traceFail("Canceling send, idMail: {$idMail}");
				$this->router($action);
			}
		}
		else {
			$this->flashSession->error("Ha intentado cancelar el envío de un correo que no existe, por favor verifique la información");
		}
		$this->router($action);
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