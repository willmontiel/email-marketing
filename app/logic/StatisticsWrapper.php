<?php
//->log('Entra');
class StatisticsWrapper extends BaseWrapper
{
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}


	public function showMailStatistics(Mail $mail, $compare = true)
	{
		$manager = Phalcon\DI::getDefault()->get('modelsManager');
		$total = $mail->totalContacts;
		$opens = ($mail->uniqueOpens != null) ? $mail->uniqueOpens : 0;
		$bounced = ($mail->bounced != null) ? $mail->bounced : 0;
		$clicks = ($mail->clicks != null) ? $mail->clicks : 0;
		$unopened = $total -($opens + $bounced);
		$unsubscribed = ($mail->unsubscribed != null) ? $mail->unsubscribed : 0;
		$spam = ($mail->spam != null) ? $mail->spam : 0;

		$summaryChartData[] = array(
			'title' => "Aperturas",
			'value' => $opens,
		);
		$summaryChartData[] = array(
			'title' => "Rebotados",
			'value' => $bounced,
		);
		$summaryChartData[] = array(
			'title' => "No Aperturas",
			'value' => $unopened,
		);

		$statisticsData = new stdClass();
		$statisticsData->total = $total;
		$statisticsData->opens = $opens;
		$statisticsData->statopens = round(( $opens / $total ) * 100 );
		
		$clicksql = "SELECT SUM(clicks) AS total, SUM(IF(clicks != 0, 1, 0)) AS ctr
					FROM Mxc 
					WHERE idMail = :idMail:";
		$clicksql1 = $manager->createQuery($clicksql);
		$click_r = $clicksql1->execute(array(
			'idMail' => $mail->idMail
		));
		
		$statisticsData->clicks_CTR = $click_r['ctr']->ctr;
		$statisticsData->totalclicks = $click_r['total']->total;
		$statisticsData->percent_clicks_CTR = round(($click_r['ctr']->ctr / ($total - $bounced)) * 100);
		$statisticsData->percent_clicks_CTO = round(($click_r['ctr']->ctr / $opens) * 100);
		
		$sql = "SELECT COUNT(*) AS total
				FROM Mxc AS m 
				WHERE m.idMail = :idMail: AND m.idBouncedcode = 10";
		$sqlHardBounced = $manager->createQuery($sql);
		$hardBounced = $sqlHardBounced->execute(array(
			'idMail' => $mail->idMail
		));
		Phalcon\DI::getDefault()->get('logger')->log($hardBounced['total']->total);
		$statisticsData->bounced = $bounced;
		$statisticsData->statbounced = round(( $bounced / $total ) * 100 );
		$statisticsData->hardbounced = $hardBounced['total']->total;
		$statisticsData->stathardbounced = round(( $hardBounced['total']->total / $bounced ) * 100 );
		$statisticsData->softbounced = $bounced -  $hardBounced['total']->total;
		$statisticsData->statsoftbounced = round(( $statisticsData->softbounced / $bounced ) * 100 );
		
		$statisticsData->unsubscribed = $unsubscribed;
		$statisticsData->statunsubscribed = round (( $unsubscribed / $total ) * 100 );
		$statisticsData->spam = $spam;
		$statisticsData->statspam = round(( $spam / $total ) * 100 );

		$allMails = Mail::find(array(
			'conditions' => 'idAccount = ?1 AND status = "Sent" AND idMail != ?2',
			'bind' => array(1 => $this->account->idAccount, 2 => $mail->idMail)
		));
		
		if ($compare) {
			$mailCompare = array();
			foreach ($allMails as $m) {
				$objfc = new stdClass();
				$objfc->id = $m->idMail;
				$objfc->name = $m->name;
				$mailCompare[] = $objfc;				
			}
				$response['compareMail'] = $mailCompare;
		}
		
		$snQuery = "SELECT SUM(m.share_fb) AS share_fb, 
						  SUM(m.share_tw) AS share_tw, 
						  SUM(m.share_gp) AS share_gp, 
						  SUM(m.share_li) AS share_li, 
						  SUM(m.open_fb) AS open_fb, 
						  SUM(m.open_tw) AS open_tw, 
						  SUM(m.open_gp) AS open_gp, 
						  SUM( m.open_li) AS open_li
					  FROM Mxc AS m
				   WHERE m.idMail = :idMail:";
		$social = $manager->createQuery($snQuery);
		$socialStats = $social->execute(array(
			'idMail' => $mail->idMail
		));
		
		
		$snCliksQuery = "SELECT SUM(l.click_fb) AS click_fb, 
						  SUM(l.click_tw) AS click_tw, 
						  SUM(l.click_gp) AS click_gp, 
						  SUM(l.click_li) AS click_li
					  FROM Mxcxl AS l
				   WHERE l.idMail = :idMail:";
		$socialClicks = $manager->createQuery($snCliksQuery);
		$socialClickStats = $socialClicks->execute(array(
			'idMail' => $mail->idMail
		));
		
		
		$this->logger->log("Stats: " . print_r($statisticsData, true));
		$response['summaryChartData'] = $summaryChartData;
		$response['statisticsData'] = $statisticsData;
		$response['statisticsSocial'] = $socialStats->getFirst();
		$response['statisticsClicksSocial'] = $socialClickStats->getFirst();
		
		return $response;
	}
	
	public function showContactlistStatistics(Contactlist $list, Dbase $dbase)
	{
		$statsContactList = Statcontactlist::find(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $list->idContactlist)
		));
		
		if(!$statsContactList) {
			return FALSE;
		}

		$stat = new stdClass();
		$sent = 0;
		$uniqueOpens = 0;
		$clicks = 0;
		$bounced = 0;
		$spam = 0;
		$unsubscribed = 0;

		foreach ($statsContactList as $s) {
			$sent +=  $s->sent;
			$uniqueOpens +=  $s->uniqueOpens;
			$clicks +=  $s->clicks;
			$bounced +=  $s->bounced;
			$spam +=  $s->spam;
			$unsubscribed += $s->unsubscribed;
		}
		
		$summaryChartData[] = array(
			'title' => "Aperturas",
			'value' => $uniqueOpens,
		);
		$summaryChartData[] = array(
			'title' => "Rebotados",
			'value' => $bounced,
		);
		$summaryChartData[] = array(
			'title' => "No Aperturas",
			'value' => $sent - ( $uniqueOpens + $bounced ),
		);

		$stat->idContactlist = $list->idContactlist;
		$stat->sent = $sent;
		$stat->uniqueOpens = $uniqueOpens;
		$stat->percentageUniqueOpens = round(($uniqueOpens*100)/$sent);
		$stat->clicks = $clicks;
		$stat->bounced = $bounced;
		$stat->percentageBounced = round(($bounced*100)/$sent);
		$stat->spam = $spam;
		$stat->percentageSpam = round(($spam*100)/$sent);
		$stat->unsubscribed = $unsubscribed;
		$stat->percentageUnsubscribed = round(($unsubscribed*100)/$sent);

		$stat->undelivered = ($sent-$stat->percentageUniqueOpens);
					
		$allLists = Contactlist::find(array(
			'conditions' => 'idDbase = ?1 AND idContactlist != ?2',
			'bind' => array(1 => $dbase->idDbase, 2 => $list->idContactlist)
		));
		
		$listCompare = array();
		foreach ($allLists as $l) {
			$objfc = new stdClass();
			$objfc->id = $l->idContactlist;
			$objfc->name = $l->name;
			$listCompare[] = $objfc;				
		}
		
		$response['summaryChartData'] = $summaryChartData;
		$response['statisticsData'] = $stat;
		$response['compareList'] = $listCompare;
		
		return $response;
	}
	
	public function showDbaseStatistics(Dbase $dbase)
	{
		$statsDbase = Statdbase::find(array(
			'conditions' => 'idDbase = ?1',
			'bind' => array(1 => $dbase->idDbase)
		));
		
		if(!$statsDbase) {
			return FALSE;
		}

		$stat = new stdClass();
		$sent = 0;
		$uniqueOpens = 0;
		$clicks = 0;
		$bounced = 0;
		$spam = 0;
		$unsubscribed = 0;

		foreach ($statsDbase as $s) {
			$sent +=  $s->sent;
			$uniqueOpens +=  $s->uniqueOpens;
			$clicks +=  $s->clicks;
//			$clicks +=  ($s->sent/$s->clicks);
			$bounced +=  $s->bounced;
			$spam +=  $s->spam;
			$unsubscribed += $s->unsubscribed;
		}
		
		$summaryChartData[] = array(
			'title' => "Aperturas",
			'value' => $uniqueOpens,
		);
		$summaryChartData[] = array(
			'title' => "Rebotados",
			'value' => $bounced,
		);
		$summaryChartData[] = array(
			'title' => "No Aperturas",
			'value' => $sent - ( $uniqueOpens + $bounced ),
		);

		$stat->idDbase = $dbase->idDbase;
		$stat->sent = $sent;
		$stat->uniqueOpens = $uniqueOpens;
		$stat->percentageUniqueOpens = round(($uniqueOpens*100)/$sent);
		$stat->clicks = $clicks;
		$stat->bounced = $bounced;
		$stat->percentageBounced = round(($bounced*100)/$sent);
		$stat->spam = $spam;
		$stat->percentageSpam = round(($spam*100)/$sent);
		$stat->unsubscribed = $unsubscribed;
		$stat->percentageUnsubscribed = round(($unsubscribed*100)/$sent);

		$stat->undelivered = ($sent-$stat->percentageUniqueOpens);
					
		$allDbs = Dbase::find(array(
			'conditions' => 'idAccount = ?1 AND idDbase != ?2',
			'bind' => array(1 => $this->account->idAccount, 2 => $dbase->idDbase)
		));
		
		$dbaseCompare = array();
		foreach ($allDbs as $db) {
			$objfc = new stdClass();
			$objfc->id = $db->idDbase;
			$objfc->name = $db->name;
			$dbaseCompare[] = $objfc;				
		}
		
		$response['summaryChartData'] = $summaryChartData;
		$response['statisticsData'] = $stat;
		$response['compareDbase'] = $dbaseCompare;
		
		return $response;
	}
	
	public function findMailOpenStats($idMail)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql1 = "SELECT COUNT(*) AS t
					FROM mxc AS m
						JOIN contact as c ON (c.idContact = m.idContact)
						JOIN email as e ON (c.idEmail = e.idEmail)
					WHERE m.idMail = ? AND m.opening != 0";
		
		$result1 = $db->query($sql1, array($idMail));
		$total = $result1->fetch();
		
		$sql2 = "SELECT m.idContact, m.opening AS date, e.email 
					FROM mxc AS m
						JOIN contact as c ON (c.idContact = m.idContact)
						JOIN email as e ON (c.idEmail = e.idEmail)
					WHERE m.idMail = ? AND m.opening != 0";
		
		$sql2 .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		$result2 = $db->query($sql2, array($idMail));
		$info = $result2->fetchAll();
		
		$sql3 = "SELECT m.opening AS date
					FROM mxc AS m
					WHERE m.idMail = ? AND m.opening != 0";
		$result3 = $db->query($sql3, array($idMail));
		$stats = $result3->fetchAll();
		
		
		$opencontact = array();
		$opens = array();
		
		if (count($info) > 0) {
			foreach ($info as $i) {
				$opencontact[] = array(
					'id' => $i['idContact'],
					'email' => $i['email'],
					'date' => date('Y-m-d H:i', $i['date']),
				);
			}
		}
		
		
		if (count($stats) > 0) {
			foreach ($stats as $i) {
				$opens[] = $i['date'];
			}
		}
		
		sort($opens);
		
//		$this->logger->log("Opens: " . print_r($opens, true));
//		$sql = "SELECT FROM_UNIXTIME(CAST((opening/3600) AS UNSIGNED)*3600) AS date, COUNT(*) AS total
//					FROM mxc
//				WHERE idMail = ? AND opening != 0
//				GROUP BY 1";
//		
//		$result = $db->query($sql, array($idMail));
//		$openStats = $result->fetchAll();
		
//		$this->logger->log(print_r($openStats, true));
		$timePeriod = new \EmailMarketing\General\Misc\TotalTimePeriod();
		$timePeriod->setData($opens);
		$timePeriod->processTimePeriod();

		$periodModel = new \EmailMarketing\General\Misc\TimePeriodModel('Aperturas');
		$periodModel->setTimePeriod($timePeriod);
		$periodModel->modelTimePeriod();
		$openData = $periodModel->getModelTimePeriod();		
		$this->pager->setTotalRecords($total['t']);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($response['statistics'] = $openData),
			'details' => json_encode($response['details'] = $opencontact)
		);
		
		$this->pager->setRowsInCurrentPage(count($opencontact));
		
		return array('drilldownopen' => $statistics, 'meta' => $this->pager->getPaginationObject());
	}
	
	public function printData($obj, $indent = 0)
	{
		$this->logger->log('ind: ' . $indent);
		$ind = str_repeat("\t", $indent);
		
		$this->logger->log($ind . 'Nombre: ' . $obj->getPeriodName());
		$this->logger->log($ind . 'Total: ' . $obj->getTotal());
		
		foreach ($obj->getTimePeriod() as $child) {
			$this->printData($child, $indent+1);
		}
	}




	public function findMailClickStats($idMail, $filter)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$manager = Phalcon\DI::getDefault()->get('modelsManager');
		
		/*
		 * SQL para extraer los enlaces que se encontrarón en el correo
		 */
		$sqlForLinks = "SELECT m.idMailLink, m.totalClicks, l.link 
				FROM mxl AS m 
				JOIN maillink AS l ON (l.idMailLink = m.idMailLink)
			 WHERE idMail = ?";
		
		$allLinks = $db->query($sqlForLinks, array($idMail));
		$total = $allLinks->fetchAll();
		
		$links = array();
		$valueLinks = array();
		$arrayLinks = array();
		$info = array();
		
		if (count($total) > 0 ) {
			foreach ($total as $t) {
				$valueLinks[$t['idMailLink']] = $t['link'];
				if ($t['totalClicks'] == null) {
					$t['totalClicks'] = 0;
				}
				$links[] = array(
					'link' => $t['link'],
					'total' => $t['totalClicks'],
					'uniques' => 15,
				);
				
				$arrayLinks[$t['idMailLink']] = 0;
			}
			
			$info[] = array(
				'amount' => count($valueLinks),
				'value' => $valueLinks
			);
		}
		
		/**
		 * SQL para extraer los links y el total de clicks por cada uno
		 */
//		$sql2 = "SELECT m.click, m.idMailLink, COUNT( m.idMailLink ) AS total
//					FROM mxcxl AS m
//				 WHERE m.idMail = ? AND m.click != 0
//				 GROUP BY m.idMailLink, m.click";
		
		$sql2 = "SELECT m.click AS date
					FROM mxcxl AS m
				 WHERE m.idMail = ? AND m.click != 0";
		
		$result2 = $db->query($sql2, array($idMail));
		$linkValues = $result2->fetchAll();
		
		$allLinks = array();
		if (count($linkValues) > 0) {
			foreach ($linkValues as $l) {
				$allLinks[] = $l['date'];
			}
		}
		
		sort($allLinks);
		
		$timePeriod = new \EmailMarketing\General\Misc\TotalTimePeriod();
		$timePeriod->setData($allLinks);
		$timePeriod->processTimePeriod();

		$periodModel = new \EmailMarketing\General\Misc\TimePeriodModel('Clics');
		$periodModel->setTimePeriod($timePeriod);
		$periodModel->modelTimePeriod();
		$statsLinks = $periodModel->getModelTimePeriod();	
		
		$phql = "SELECT ml.click, e.email, l.link
				 FROM Mxcxl AS ml
					JOIN Contact AS c ON (c.idContact = ml.idContact)
					JOIN Email AS e ON (e.idEmail = c.idEmail)
					JOIN Maillink AS l ON (l.idMailLink = ml.idMailLink)
				 WHERE ml.idMail = :idMail: AND ml.click != 0 ";
		
		if ($filter && $filter != 'Todos') {
			$phql.= "AND l.link = '" . $filter . "' ";
		}
				
		$phql.= "LIMIT " . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		
		$query = $manager->createQuery($phql);
		$result = $query->execute(array(
			'idMail' => $idMail
		));
		
		$clickcontact = array();
		if (count($result) > 0) {
			foreach ($result as $i) {
				$clickcontact[] = array(
					'email' => $i->email,
					'date' => date('Y-m-d h:i', $i->click),
					'link' => $i->link
				);
			}
		}
		
		$phqlcount = "SELECT COUNT(*) AS total
						FROM Mxcxl AS ml
						   JOIN Contact AS c ON (c.idContact = ml.idContact)
						   JOIN Email AS e ON (e.idEmail = c.idEmail)
						   JOIN Maillink AS l ON (l.idMailLink = ml.idMailLink)
						WHERE ml.idMail = :idMail: AND ml.click != 0 ";
		if ($filter && $filter != 'Todos') {
			$phqlcount.= "AND l.link = '" . $filter . "' ";
		}
		
		$querycount = $manager->createQuery($phqlcount);
		$resultcount = $querycount->execute(array(
			'idMail' => $idMail
		));

		$this->pager->setTotalRecords($resultcount['total']->total);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($statsLinks),
			'details' => json_encode($clickcontact),
			'links' => json_encode($links),
			'multvalchart' => json_encode($info),
		);
		
		$this->pager->setRowsInCurrentPage(count($clickcontact));
		
		return array('drilldownclick' => $statistics, 'meta' => $this->pager->getPaginationObject());
	}
	
	public function findMailUnsubscribedStats($idMail)
	{
		$manager = Phalcon\DI::getDefault()->get('modelsManager');
		$phql = "SELECT m.idContact, m.unsubscribe AS date, c.name, c.lastName, e.email 
				FROM Mxc AS m
					JOIN Contact AS c ON (c.idContact = m.idContact)
					JOIN Email AS e ON (c.idEmail = e.idEmail)
				WHERE m.idMail = :idMail: AND m.unsubscribe != 0";
		$phql .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		$query = $manager->createQuery($phql);
		$unsubscribeds = $query->execute(array(
			'idMail' => $idMail
		));
		
		$unsubscribed = array();
		$unsubscribedcontact = array();
		$count = count($unsubscribeds);
		
		if ($count) {
			foreach ($unsubscribeds as $u) {
				$unsubscribedcontact[] = array(
					'id' => $u->idContact,
					'email' => $u->email,
					'date' => date('Y-m-d h:i', $u->date),
					'name' => $u->name,
					'lastname' => $u->lastName
				);
			
				$unsubsArray = array();
				if (!isset($unsubsArray[$u->date])) {
					$unsubsArray[$u->date] = array(
						'title' => $u->date,
						'value' => 1
					);
				}
				else {
					$unsubsArray[$u->date]['value'] += 1;
				}
				
				foreach ($unsubsArray as $o) {
					$unsubscribed[] = array (
						'title' => $o['title'],
						'value' => $o['value']
					);
				}
			}
			
			$phqlCount = "SELECT COUNT(*) AS total
						FROM Mxc AS m
							JOIN Contact AS c ON (c.idContact = m.idContact)
							JOIN Email AS e ON (c.idEmail = e.idEmail)
						WHERE m.idMail = :idMail: AND m.unsubscribe != 0";
		
			$queryCount = $manager->createQuery($phqlCount);
			$total = $queryCount->execute(array(
				'idMail' => $idMail
			));
			
			$count = $total['total']->total;
		}
		
		$this->pager->setTotalRecords($count);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($unsubscribed),
			'details' => json_encode($unsubscribedcontact)
		);
		
		$this->pager->setRowsInCurrentPage(count($unsubscribedcontact));
		
		return array('drilldownunsubscribed' => $statistics, 'meta' =>  $this->pager->getPaginationObject());
	}
	
	public function findMailSpamStats($idMail)
	{
		$manager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = "SELECT e.email, m.spam, c.name, c.lastName
				FROM Mxc AS m 
					JOIN Contact AS c ON (c.idContact = m.idContact)
					JOIN Email AS e ON (e.idEmail = c.idEmail)
				WHERE m.idMail = :idMail: AND m.spam != 0 LIMIT " . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		
		$query = $manager->createQuery($phql);
		$spams = $query->execute(array(
			'idMail' => $idMail
		));
		
		$spamcontact = array();
		$spam = array();
		if (count($spams) > 0) {
			foreach ($spams as $s) {
				$spamcontact[] = array(
					'email' => $s->email,
					'date' => date('Y-m-d h:i', $s->date),
					'name' => $s->name,
					'lastname' => $s->lastName
				);
				
				$spamArray = array();
				if (!isset($spamArray[$s->date])) {
					$spamArray[$s->date] = array(
						'title' => $s->date,
						'value' => 1
					);
				}
				else {
					$spamArray[$s->date]['value'] += 1;
				}
				
				foreach ($spamArray as $o) {
					$spam[] = array (
						'title' => $o['title'],
						'value' => $o['value']
					);
				}
			}
		}
		
		$phql2 = "SELECT COUNT(*) AS total
				FROM Mxc AS m 
					JOIN Contact AS c ON (c.idContact = m.idContact)
					JOIN Email AS e ON (e.idEmail = c.idEmail)
				WHERE m.idMail = :idMail: AND m.spam != 0";
		$query2 = $manager->createQuery($phql2);
		$total = $query2->execute(array(
			'idMail' => $idMail
		));
		
		$this->pager->setTotalRecords($total['total']->total);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($spam),
			'details' => json_encode($spamcontact)
		);
		
		$this->pager->setRowsInCurrentPage(count($spamcontact));
		
		return array('drilldownspam' => $statistics, 'meta' =>  $this->pager->getPaginationObject());
	}
	
	public function findMailBouncedStats($idMail, $type, $filter)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql1 = "SELECT m.bounced AS date, e.email, b.type, b.description, d.name 
				FROM mxc AS m 
					JOIN contact AS c ON (c.idContact = m.idContact)
					JOIN email AS e ON (e.idEmail = c.idEmail)
					JOIN domain AS d ON (d.idDomain = e.idDomain)
					JOIN bouncedcode AS b ON (b.idBouncedCode = m.idBouncedCode)
				WHERE m.idMail = ? AND m.bounced != 0 ";

		if($filter && $filter != 'Todos') {
			switch ($type) {
				case 'category':
					$sql1.= "AND b.description = '{$filter}'";
					break;
				case 'domain':
					$sql1.= "AND d.name = '{$filter}'";
					break;
				case 'type':
					$sql1.= "AND b.type = '{$filter}'";
					break;
			}
		}
		
		$sql1 .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();	
		
		$query1 = $db->query($sql1, array($idMail));
		$result1 = $query1->fetchAll();
		
		$bounced = array();
		$soft = array(
			0 => 'Rebotes suaves',
			1 => 0
		);
		
		$hard = array(
			0 => 'Rebotes duros',
			1 => 0
		);
		$bouncedcontact = array();
		$valueDomain = array();
		if (count($result1) > 0) {
			$values = array();
			foreach ($result1 as $r) {
				$bouncedcontact[] = array(
					'email' => $r['email'],
					'date' => date('Y-m-d h:i', $r['date']),
					'type' => $r['type'],
					'category' => $r['description'],
					'domain' => $r['name']
				);

				if (!in_array($r['name'], $valueDomain)) {
					$valueDomain[] = $r['name'];
				}
			
				if ($r['type'] == 'hard') {
					$hard[1] = $hard[1] + 1;
				}
				else if ($r['type'] == 'soft') {
					$soft[1] = $soft[1] + 1;
				}
			}
		}
		
		$bounced[] = $soft;
		$bounced[] = $hard;
	
		$bouncedTypes = Bouncedcode::find();
		$valueType = array();
		$valueCategory = array();
		if (count($bouncedTypes) > 0) {
			foreach ($bouncedTypes as $b) {
				if (!in_array($b->type, $valueType)) {
					$valueType[$b->type] = ucfirst($b->type);
				}
				$valueCategory[] = $b->description;
			}
		}
		
		$info[] = array(
			'amount' => count($valueType),
			'value' => $valueType,
			'category' => $valueCategory,
			'domain' => $valueDomain
		);
		
		$phqlcount = "	SELECT COUNT(*) AS total 
						FROM mxc AS m 
							JOIN contact AS c ON (c.idContact = m.idContact)
							JOIN email AS e ON (e.idEmail = c.idEmail)
							JOIN domain AS d ON (d.idDomain = e.idDomain)
							JOIN bouncedcode AS b ON (b.idBouncedCode = m.idBouncedCode)
						WHERE m.idMail = ? AND m.bounced != 0 ";
		
		if($filter && $filter != 'Todos') {
			switch ($type) {
				case 'category':
					$phqlcount.= "AND b.description = '{$filter}'";
					break;
				case 'domain':
					$phqlcount.= "AND d.name = '{$filter}'";
					break;
				case 'type':
					$phqlcount.= "AND b.type = '{$filter}'";
					break;
			}
		}
		$querycount = $db->query($phqlcount, array($idMail));
		$resultcount = $querycount->fetchAll();
		$this->pager->setTotalRecords($resultcount[0]['total']);
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($bounced),
			'details' => json_encode($bouncedcontact),
			'multvalchart' => json_encode($info),
		);
		
		$this->pager->setRowsInCurrentPage(count($bouncedcontact));
		
		return array('drilldownbounced' => $statistics, 'meta' =>  $this->pager->getPaginationObject());
	}	
}
