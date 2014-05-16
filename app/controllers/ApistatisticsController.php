<?php
/**
 * @RoutePrefix("/apistatistics")
 */
class ApistatisticsController extends ControllerBase
{
	/**
	 * @Get("/mail/{type:[a-z]+}/{idMail:[0-9]+}/drilldownopens")
	 */
	public function mailopensAction($type, $idMail)
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
	 * @Get("/mail/{idMail:[0-9]+}/drilldownclicks")
	 */
	public function mailclicksAction($idMail)
	{
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
		
		$stat = $statWrapper->findMailClickStats($idMail, $filter);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownunsubscribeds")
	 */
	public function mailunsubscribedAction($idMail)
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
}
