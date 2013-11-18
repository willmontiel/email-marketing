<?php
class SendingprocessController extends ControllerBase
{	
	public function indexAction()
	{
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data"  => Mail::find(),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page"  => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
}	
