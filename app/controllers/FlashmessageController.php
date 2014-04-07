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
		date_default_timezone_set('America/Bogota');
		
		$fm = new Flashmessage();
		$form = new FlashMessageForm($fm);
		
		if ($this->request->isPost()) {
			
			$form->bind($this->request->getPost(), $fm);
			
			$start = $form->getValue('start');
			$end = $form->getValue('end');
			$allAccounts = $form->getValue('allAccounts');
			$certainAccounts = $form->getValue('accounts');
			
			$this->logger->log("Begin: {$start}");
			$this->logger->log("End: {$end}");
			 
			list($month1, $day1, $year1, $hour1, $minute1) = preg_split('/[\s\/|-|:]+/', $start);
			$dateBegin = mktime($hour1, $minute1, 0, $month1, $day1, $year1);
			
//			$dateBegin	= strtotime($start);
			
//			$date1 = new DateTime($start);
//			$dateBegin = $date1->getTimestamp();

			list($month2, $day2, $year2, $hour2, $minute2) = preg_split('/[\s\/|-|:]+/', $end);
			$dateEnd = mktime($hour2, $minute2, 0, $month2, $day2, $year2);
			
//			$dateEnd = strtotime($end);
			
//			$date2 = new DateTime($end);
//			$dateEnd = $date2->getTimestamp();
			
//			$this->logger->log("Begin: {$dateBegin}");
//			$this->logger->log("Begin again: " . date('m/d/Y H:i:s', $dateBegin));
//			$this->logger->log("End: {$dateEnd}");
//			$this->logger->log("End again: " . date('m/d/Y H:i:s', $dateEnd));
//			$this->logger->log("Now: " . time());
//			$this->logger->log("Now again: " . date('m/d/Y H:i:s', time()));
			
			if(time() > $dateEnd || $dateEnd < $dateBegin) {
				$this->flashSession->error('Ha selecionado una fecha que ya ha pasado, por favor verifique la información');
			}
			else if (trim($allAccounts) === '' && empty($certainAccounts)){
				$this->flashSession->error('No ha seleccionado una cuenta, por favor verifique la información');
			}
			else {
				if (!empty($allAccounts)) {
					$fm->accounts = 'all';
				}
				else {
					$fm->accounts = json_encode($certainAccounts);
				}
				
				$fm->start = $dateBegin;
				$fm->end = $dateEnd;
				$fm->createdon = time();
				
				if ($form->isValid() && $fm->save()) {
					$this->flashSession->success('Mensaje creado exitosamente');
					return $this->response->redirect('flashmessage/index');
				}
				else {
					foreach ($fm->getMessages() as $m) {
						$this->logger->log("Error saving message: " . $m);
						$this->flashSession->error($m);
					}
				}
			}
		}
		$this->view->MessageForm = $form;
	}
	
	public function editAction($idMessage)
	{
		$message = Flashmessage::findFirst(array(
			'conditions' => 'idFlashMessage = ?1',
			'bind' => array(1 => $idMessage)
		));
		
		$accounts = Account::find();
		
		if ($message) {
			
			$this->view->setVar('accounts', $accounts);
			$this->view->setVar('message', $message);
				
			if ($this->request->isPost()) {
				$name = $this->request->getPost('name');
				$msg = $this->request->getPost('message');
				$allAccounts = $this->request->getPost('allAccounts');
				$account = $this->request->getPost('accounts');
				$type = $this->request->getPost('type');
				$start = $this->request->getPost('start');
				$end = $this->request->getPost('end');
				
				$this->logger->log("Begin: {$start}");
				$this->logger->log("End: {$end}");
				
				if (trim($name) === '' || trim($msg) === '' || trim($allAccounts) === '' || trim($type) === '' || trim($start) === '' || trim($end) === '') {
					$this->flashSession->error('Ha enviado campos vacios, por favor verifique la información');
				}
				else {
					list($day1, $month1, $year1, $hour1, $minute1) = preg_split('/[\s\/|-|:]+/', $start);
					$dateBegin = mktime($hour1, $minute1, 0, $month1, $day1, $year1);
					
//					$dateBegin	= strtotime($start);
					
					list($day2, $month2, $year2, $hour2, $minute2) = preg_split('/[\s\/|-|:]+/', $end);
					$dateEnd = mktime($hour2, $minute2, 0, $month2, $day2, $year2);
					
//					$dateEnd = strtotime($end);
					
					$this->logger->log("Begin: {$dateBegin}");
					$this->logger->log("End: {$dateEnd}");
					
					$this->logger->log("Begin again: " . date('m/d/Y H:s', $dateBegin));
					$this->logger->log("End again: ". date('m/d/Y H:s', $dateEnd));
					$this->logger->log("Now: ". date('m/d/Y H:s', time()));
					
					if($dateEnd < $dateBegin || $dateEnd < time()) {
						$this->flashSession->error('Ha selecionado una fecha que ya ha pasado, por favor verifique la información');
					}
					else {
						if ($allAccounts == 'any') {
							if (count($account) == 0) {
								$this->flashSession->error('No ha seleccionado una cuenta, por favor verifique la información');
								return $this->response->redirect('flashmessage/edit/' . $idMessage);
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
						}
						else {
							$this->flashSession->success('Mensaje editado exitosamente');
							return $this->response->redirect('flashmessage/index');
						}
					}
				}
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