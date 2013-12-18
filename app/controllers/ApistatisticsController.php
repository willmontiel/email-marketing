<?php
/**
 * @RoutePrefix("/apistatistics")
 */
class ApistatisticsController extends ControllerBase
{
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/dbasestatistics")
	 */
	public function dbaseAction($idDbase)
	{
		$log = $this->logger;
		$log->log('El id dbase es: ' . $idDbase);
		$values[0] = array(
			'id' => 1,
			'title' =>'Enero',
			'value' => '20'
		);
		$values[1] = array(
			'id' => 2,
			'title' =>'Febrero',
			'value' => '30'
		);
		$values[2] = array(
			'id' => 3,
			'title' =>'Marzo',
			'value' => '50'
		);
		
		
		$contact[0] = array(
			'id' => 100,
			'email' => 'recipient00001@test001.local.discardallmail.drh.net',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$contact[1] = array(
			'id' => 145,
			'email' => 'recipient00002@test002.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$contact[2] = array(
			'id' => 161,
			'email' => 'recipient00003@test003.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Mac'
		);
		
		$statistics['statistics'] = json_encode($values);
		$statistics['details'] = json_encode($contact);
		
		return $this->setJsonResponse(array('dbasestatistic' => $statistics));
	}
	/**
	 * @Get("/clickstatistics")
	 */
	
	public function clickstatisticsAction($idMail)
	{
		$log = $this->logger;
		
		$values[0] = array(
			'id' => 58,
			'title' =>'Enero',
			'value' => '20'
		);
		$values[1] = array(
			'id' => 26,
			'title' =>'Febrero',
			'value' => '30'
		);
		$values[2] = array(
			'id' => 37,
			'title' =>'Marzo',
			'value' => '50'
		);

		return $this->setJsonResponse(array('clickstatistic' => $values));
	}
	
	/**
	 * @Get("/clickdetaillists")
	 */
	public function clickdetaillistsAction()
	{
		$log = $this->logger;
		
		$contact[0] = array(
			'id' => 71,
			'email' => 'recipient00001@test001.local.discardallmail.drh.net',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$contact[1] = array(
			'id' => 254,
			'email' => 'recipient00002@test002.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$contact[2] = array(
			'id' => 89,
			'email' => 'recipient00003@test003.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Mac'
		);

		return $this->setJsonResponse(array('clickdetaillist' => $contact));
	}
	
	/**
	 * @Get("/opendetaillists/{idContactlist:[0-9]+}")
	 */
	public function contactlistAction($idContactList)
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
