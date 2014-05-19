<?php
/**
 * @RoutePrefix("/apistatistics")
 */
class ApistatisticsController extends ControllerBase
{
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownopens")
	 */
	public function mailopensAction($idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailOpenStats($idMail);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownclicks")
	 */
	public function mailclicksAction($idMail)
	{
//		$this->logger->log("Type: {$type}");
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		$filter = $this->request->getQuery('filter');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailClickStats($idMail, $filter, $type);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownunsubscribeds")
	 */
	public function mailunsubscribedAction($type)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailUnsubscribedStats($idMail);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownspams")
	 */	
	public function mailspamAction($idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailSpamStats($idMail);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownbounceds")
	 */	
	public function mailbouncedAction($idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		$type = $this->request->getQuery('type');
		$filter = $this->request->getQuery('filter');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailBouncedStats($idMail, $type, $filter);
		
		return $this->setJsonResponse($stat);
	}
	
	
	
	
	
	/**
	 * @Get("/mail/{type:[a-z]+}/{idMail:[0-9]+}/drilldownopens")
	 */
	public function mailpublicopensAction($type, $idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailOpenStats($idMail, $type);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{type:[a-z]+}/{idMail:[0-9]+}/drilldownclicks")
	 */
	public function mailpublicclicksAction($type, $idMail)
	{
		$this->logger->log("Type: {$type}");
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		$filter = $this->request->getQuery('filter');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailClickStats($idMail, $filter, $type);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{type:[a-z]+}/{idMail:[0-9]+}/drilldownunsubscribeds")
	 */
	public function mailpublicunsubscribedAction($type, $idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailUnsubscribedStats($idMail, $type);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{type:[a-z]+}/{idMail:[0-9]+}/drilldownspams")
	 */	
	public function mailpublicspamAction($type, $idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailSpamStats($idMail, $type);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{type:[a-z]+}/{idMail:[0-9]+}/drilldownbounceds")
	 */	
	public function mailpublicbouncedAction($request, $idMail)
	{
		$limit = $this->request->getQuery('limit');
		$page = $this->request->getQuery('page');
		$type = $this->request->getQuery('type');
		$filter = $this->request->getQuery('filter');
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailBouncedStats($idMail, $type, $filter, $request);
		
		return $this->setJsonResponse($stat);
	}
}
