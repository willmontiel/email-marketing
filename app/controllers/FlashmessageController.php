<?php
class FlashmessageController extends ControllerBase
{
	public function indexAction()
	{
//		$messages = Flashmessage::find();
//		$this->view->setVar('messages', $messages);
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$builder = $this->modelsManager->createBuilder()
			->from('Flashmessage')
			->orderBy('createdon');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder" => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page" => $currentPage
		));
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function newAction()
	{
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
		
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$message = $this->request->getPost('message');
			$allAccounts = $this->request->getPost('allAccounts');
			$account = $this->request->getPost('accounts');
			$type = $this->request->getPost('typeMessage');
			$start = $this->request->getPost('start');
			$end = $this->request->getPost('end');
			
			if (trim($name) === '' || trim($message) === '' || trim($allAccounts) === '' || trim($type) === '' || trim($start) === '' || trim($end) === '') {
				$this->flashSession->error('Ha enviado campos vacios, por favor verifique la información');
				return false;
			}
			
			list($day1, $month1, $year1, $hour1, $minute1) = preg_split('/[\s\/|-|:]+/', $start);
			$dateBegin = mktime($hour1, $minute1, 0, $month1, $day1, $year1);

			list($day2, $month2, $year2, $hour2, $minute2) = preg_split('/[\s\/|-|:]+/', $end);
			$dateEnd = mktime($hour2, $minute2, 0, $month2, $day2, $year2);

			if($dateBegin < time() || $dateEnd < time()) {
				$this->flashSession->error('Ha selecionado una fecha que ya ha pasado, por favor verifique la información');
				return false;
			}
			
			$msg = new Flashmessage();
			
			if ($allAccounts == 'any') {
				if (count($account) == 0) {
					$this->flashSession->error('No ha seleccionado una cuenta, por favor verifique la información');
					return false;
				}
				else {
					$msg->accounts = json_encode($account);
				}

			}
			else {
				$msg->accounts = 'all';
			}
			$msg->name = $name;
			$msg->message = $message;
			$msg->type = $type;
			$msg->start = $dateBegin;
			$msg->end = $dateEnd;
			$msg->createdon = time();
			
			if (!$msg->save()) {
				foreach ($msg->getMessages() as $m) {
					$this->logger->log("Error saving message: " . $m);
				}
				$this->flashSession->error('Ha ocurrido un error mientras se guardaba el mensaje');
				return false;
			}
			$this->flashSession->success('Mensaje creado exitosamente');
			return $this->response->redirect('flashmessage/index');
		}
	}
	
	public function editAction($idMessage)
	{
		$message = Flashmessage::findFirst(array(
			'conditions' => 'idFlashMessage = ?1',
			'bind' => array(1 => $idMessage)
		));
		
		if ($message) {
			if ($this->request->isPost()) {
				$name = $this->request->getPost('name');
				$msg = $this->request->getPost('message');
				$allAccounts = $this->request->getPost('allAccounts');
				$account = $this->request->getPost('accounts');
				$type = $this->request->getPost('typeMessage');
				$start = $this->request->getPost('start');
				$end = $this->request->getPost('end');
				
//				$this->logger->log('name: ' . $name);
//				$this->logger->log('msg: ' . $msg);
//				$this->logger->log('type: ' . $type);
//				$this->logger->log('AllAccount: ' . $allAccounts);
//				$this->logger->log('accounts: ' . print_r($account, true));
//				$this->logger->log('start: ' . $start);
//				$this->logger->log('end: ' . $end);

				if (trim($name) === '' || trim($message) === '' || trim($allAccounts) === '' || trim($type) === '' || trim($start) === '' || trim($end) === '') {
					$this->flashSession->error('Ha enviado campos vacios, por favor verifique la información');
					return false;
				}

				list($day1, $month1, $year1, $hour1, $minute1) = preg_split('/[\s\/|-|:]+/', $start);
				$dateBegin = mktime($hour1, $minute1, 0, $month1, $day1, $year1);

				list($day2, $month2, $year2, $hour2, $minute2) = preg_split('/[\s\/|-|:]+/', $end);
				$dateEnd = mktime($hour2, $minute2, 0, $month2, $day2, $year2);

				if($dateBegin < time() || $dateEnd < time()) {
					$this->flashSession->error('Ha selecionado una fecha que ya ha pasado, por favor verifique la información');
					return false;
				}

				if ($allAccounts == 'any') {
					if (count($account) == 0) {
						$this->flashSession->error('No ha seleccionado una cuenta, por favor verifique la información');
						return false;
					}
					else {
						$message->accounts = json_encode($account);
					}

				}
				else {
					$message->accounts = 'all';
				}
				$message->name = $name;
				$message->message = $msg;
				$message->type = $type;
				$message->start = $dateBegin;
				$message->end = $dateEnd;
				$message->createdon = time();

				if (!$message->save()) {
					foreach ($message->getMessages() as $m) {
						$this->logger->log("Error editing message: " . $m);
					}
					return false;
				}
				$this->flashSession->success('Mensaje editado exitosamente');
				return $this->response->redirect('flashmessage/index');
			}
			else {
				$accounts = Account::find();
				$this->view->setVar('message', $message);
				$this->view->setVar('accounts', $accounts);
			}
		}
		else {
			$this->flashSession->error('El mensaje que desea editar no existe o ha sido elminado, por favor verifique la información');
			return $this->response->redirect('flashmessage');
		}
	}
	
	public function deleteAction($idMessage)
	{
		$message = Flashmessage::findFirst(array(
			'conditions' => 'idFlashMessage = ?1',
			'bind' => array(1 => $idMessage)
		));
		if ($message) {
			if (!$message->delete()) {
				foreach ($message->getMessages() as $msg) {
					$this->logger->log('Error deleting message: ' . $msg);
				}
				$this->flashSession->error('Ha ocurrido un error mientras se eliminaba el mensaje');
				return $this->response->redirect('flashmessage');
			}
			$this->flashSession->warning('Se ha eliminado el mensaje exitosamente');
			return $this->response->redirect('flashmessage');
		}
		else {
			$this->flashSession->warning('El mensaje que desea eliminar no existe o ya ha sido elminado, por favor verifique la información');
			return $this->response->redirect('flashmessage');
		}
		
	}
}