<?php
/**
 * @RoutePrefix("/apistatistics")
 */
class ApistatisticsController extends ControllerBase
{
	
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/drilldownopens")
	 */
	public function dbaseopensAction($idDbase)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findDbaseOpenStats($idDbase);
		
		$statistics[] = array(
			'id' => $idDbase,
			'statistics' => json_encode($stat['statistics']),
			'details' => json_encode($stat['details'])
		);		
		return $this->setJsonResponse(array('drilldownopen' => $statistics));
	}
	
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/drilldownclicks")
	 */
	public function dbaseclicksAction($idDbase)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findDbaseClickStats($idDbase);
		
		$statistics[] = array(
			'id' => $idDbase,
			'statistics' => json_encode($stat['statistics']),
			'details' => json_encode($stat['details'])
		);		
		return $this->setJsonResponse(array('drilldownclick' => $statistics));
	}
	
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/drilldownunsubscribeds")
	 */
	public function dbaseunsubscribedAction($idDbase)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findDbaseUnsubscribedStats($idDbase);
		
		$statistics[] = array(
			'id' => $idDbase,
			'statistics' => json_encode($stat['statistics']),
			'details' => json_encode($stat['details'])
		);		
		return $this->setJsonResponse(array('drilldownunsubscribed' => $statistics));
	}

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
		
		$stat = $statWrapper->findMailClickStats($idMail);
		
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
		
		$pager = new PaginationDecorator();
		if ($limit) {
			$pager->setRowsPerPage($limit);
		}
		if ($page) {
			$pager->setCurrentPage($page);
		}
		
		$statWrapper = new StatisticsWrapper();
		
		$statWrapper->setPager($pager);
		
		$stat = $statWrapper->findMailBouncedStats($idMail);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/contactlist/{idContactlist:[0-9]+}/drilldownopens")
	 */
	public function contactlistopensAction($idContactList)
	{
		$this->logger->log("id: " . $idContactList);
		
		$statsContactList = Statcontactlist::find(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idContactList)
		));
		
		$statWrapper = new ContactlistStatisticsWrapper();
		
		$stat = $statWrapper->getOpenStats($statsContactList);

		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/compareopens/{idMailCompare:[0-9]+}")
	 */
	public function comparemailopensAction($idMail, $idMailCompare)
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
		
		$stat = $statWrapper->findMailOpenCompareStats($idMail, $idMailCompare);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/compareclicks/{idMailCompare:[0-9]+}")
	 */
	public function comparemailclicksAction($idMail, $idMailCompare)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailClickCompareStats($idMail, $idMailCompare);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/compareunsubscribeds/{idMailCompare:[0-9]+}")
	 */
	public function comparemailunsubscribedAction($idMail, $idMailCompare)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailUnsubscribedCompareStats($idMail, $idMailCompare);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/comparebounceds/{idMailCompare:[0-9]+}")
	 */
	public function comparemailbouncedAction($idMail, $idMailCompare)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailBouncedCompareStats($idMail, $idMailCompare);
		
		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/comparespams/{idMailCompare:[0-9]+}")
	 */
	public function comparemailspamAction($idMail, $idMailCompare)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailSpamCompareStats($idMail, $idMailCompare);
		
		return $this->setJsonResponse($stat);
	}
}
