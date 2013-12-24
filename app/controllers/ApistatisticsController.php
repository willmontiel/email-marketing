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
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailOpenStats($idMail);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($stat['statistics']),
			'details' => json_encode($stat['details'])
		);		
		return $this->setJsonResponse(array('drilldownopen' => $statistics));
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownclicks")
	 */
	public function mailclicksAction($idMail)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailClickStats($idMail);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($stat['statistics']),
			'details' => json_encode($stat['details']),
			'links' => json_encode($stat['links']),
		);
		
		return $this->setJsonResponse(array('drilldownclick' => $statistics));
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownunsubscribeds")
	 */
	public function mailunsubscribedAction($idMail)
	{
		$statWrapper = new StatisticsWrapper();
		
		$stat = $statWrapper->findMailUnsubscribedStats($idMail);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($stat['statistics']),
			'details' => json_encode($stat['details'])
		);
		
		return $this->setJsonResponse(array('drilldownunsubscribed' => $statistics));
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
}
