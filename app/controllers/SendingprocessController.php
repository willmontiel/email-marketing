<?php
class SendingprocessController extends ControllerBase
{
	public function getprocessesinfoAction()
	{
		$account = $this->user->account;
		$currentActiveContacts = $this->user->account->countActiveContactsInAccount();
		
		// Convirtiendo a Json
		$object = array();
		$object['activeContacts'] = $currentActiveContacts;
		$object['contactLimit'] = $account->contactLimit;
		$object['accountingMode'] = $account->accountingMode;
					
		return $this->setJsonResponse($object);
		
//		$mails = Mail::find(array(
//			"conditions" => "status = ?1 OR status = ?2 OR status = ?3",
//			"bind" => array(1 => "Canceled",
//							2 => "Paused",
//							3 => "Sending")
//		));
//		
//		return $this->setJsonResponse($object);
	}
	
	public function indexAction()
	{	
//		$currentPage = $this->request->getQuery('page', null, 1); // GET
//		
//		$paginator = new \Phalcon\Paginator\Adapter\Model(
//			array(
//				"data"  => Mail::find(),
//				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
//				"page"  => $currentPage
//			)
//		);
//		
//		$page = $paginator->getPaginate();
//		
//		$this->view->setVar("page", $page);
	}
}	
