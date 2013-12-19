<?php
/**
 * @RoutePrefix("/apistatistics")
 */
class ApistatisticsController extends ControllerBase
{
	/**
	 * @Get("/dbase/{idDbase:[0-9]+}/drilldowns")
	 */
	public function dbaseAction($idDbase)
	{
		$log = $this->logger;
		$log->log('El id dbase es: ' . $idDbase);
		
		$opens[] = array(
			'title' =>'Enero',
			'value' => 20
		);
		$opens[] = array(
			'title' =>'Febrero',
			'value' => 30
		);
		$opens[] = array(
			'title' =>'Marzo',
			'value' => 50
		);
		
		$clicks[] = array(
			'title' =>'Julio',
			'value' => 15
		);
		$clicks[] = array(
			'title' =>'Agosto',
			'value' => 45
		);
		$clicks[] = array(
			'title' =>'Septiembre',
			'value' => 40
		);

		$unsubscribed[] = array(
			'title' =>'Septiembre',
			'value' => 15
		);
		
		$unsubscribed[] = array(
			'title' =>'Octubre',
			'value' => 15
		);
		$unsubscribed[] = array(
			'title' =>'Noviembre',
			'value' => 45
		);
		$unsubscribed[] = array(
			'title' =>'Diciembre',
			'value' => 40
		);
		
		$opencontact[] = array(
			'id' => 100,
			'email' => 'recipient00001@test001.local.discardallmail.drh.net',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$opencontact[] = array(
			'id' => 145,
			'email' => 'recipient00002@test002.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$opencontact[] = array(
			'id' => 161,
			'email' => 'recipient00003@test003.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Mac'
		);
		
		
		$clickcontact[] = array(
			'id' => 100,
			'email' => 'otrocorreo@otro.correo',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$clickcontact[] = array(
			'id' => 145,
			'email' => 'otrocorreo2@otro2.correo2',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$clickcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Mac'
		);
		
		$allstadistics = array(
			'opens' => $opens,
			'clicks' => $clicks,
			'unsubscribed' => $unsubscribed
		);
		
		$alldetails = array(
			'opens' => $opencontact,
			'clicks' => $clickcontact,
			'unsubscribed' => $unsubscribedcontact
		);
		
		$statistics[] = array(
			'id' => 12,
			'statistics' => json_encode($allstadistics),
			'details' => json_encode($alldetails)
		);		
		return $this->setJsonResponse(array('drilldown' => $statistics));
	}

	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownopens")
	 */
	public function mailopensAction($idMail)
	{
		return $this->setJsonResponse(array('status'=> 'Error'), 500, 'Mensaje de error');
	}
	
	/**
	 * @Get("/contactlist/{idContactlist:[0-9]+}/drilldownopens")
	 */
	public function contactlistopensAction($idContactList)
	{
		$statWrapper = new ContactlistStatisticsWrapper();
		
		$stat = $statWrapper->getOpenStats($idContactList);

		return $this->setJsonResponse($stat);
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldowns")
	 */
	public function mailsAction($idMail)
	{
		$log = $this->logger;
		$log->log('El id Mail es: ' . $idMail);		
		
		$opens[] = array(
			'title' =>'Enero',
			'value' => 20
		);
		$opens[] = array(
			'title' =>'Febrero',
			'value' => 30
		);
		$opens[] = array(
			'title' =>'Marzo',
			'value' => 50
		);
		
		$opencontact[] = array(
			'id' => 100,
			'email' => 'recipient00001@test001.local.discardallmail.drh.net',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$opencontact[] = array(
			'id' => 145,
			'email' => 'recipient00002@test002.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$opencontact[] = array(
			'id' => 161,
			'email' => 'recipient00003@test003.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($opens),
			'details' => json_encode($opencontact)
		);		
		return $this->setJsonResponse(array('drilldownopen' => $statistics));
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownclicks")
	 */
	public function mailclicksAction($idMail)
	{
		$clicks[] = array(
			'title' =>'Julio',
			'value' => 15
		);
		$clicks[] = array(
			'title' =>'Agosto',
			'value' => 45
		);
		$clicks[] = array(
			'title' =>'Septiembre',
			'value' => 40
		);
		
		$clickcontact[] = array(
			'id' => 100,
			'email' => 'otrocorreo@otro.correo',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Ubuntu'
		);
		
		$clickcontact[] = array(
			'id' => 145,
			'email' => 'otrocorreo2@otro2.correo2',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$clickcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($clicks),
			'details' => json_encode($clickcontact)
		);		
		return $this->setJsonResponse(array('drilldownclick' => $statistics));
	}
	
	/**
	 * @Get("/mail/{idMail:[0-9]+}/drilldownunsubscribeds")
	 */
	public function mailunsubscribedAction($idMail)
	{
		$unsubscribed[] = array(
			'title' =>'Septiembre',
			'value' => 15
		);
		
		$unsubscribed[] = array(
			'title' =>'Octubre',
			'value' => 15
		);
		$unsubscribed[] = array(
			'title' =>'Noviembre',
			'value' => 45
		);
		$unsubscribed[] = array(
			'title' =>'Diciembre',
			'value' => 40
		);
		
		$unsubscribedcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => date('Y-m-d', 1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Windows'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d',1386687891),
			'os' => 'Mac'
		);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($unsubscribed),
			'details' => json_encode($unsubscribedcontact)
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
