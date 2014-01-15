<?php
class StatisticsWrapper extends BaseWrapper
{
	public function showMailStatistics($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail, 2 => $this->account->idAccount)
		));
		
		if($mail) {
			$total = $mail->totalContacts;
			$opens = $mail->uniqueOpens;
			$bounced = $mail->bounced;
			$clicks = $mail->clicks;
			$unopened = $total -($opens + $bounced);
			$unsubscribed = $mail->unsubscribed;
			$spam = $mail->spam;
			
			$summaryChartData[] = array(
				'title' => "Aperturas",
				'value' => $opens,
				'url' => '#/drilldown/opens'
			);
			$summaryChartData[] = array(
				'title' => "Rebotados",
				'value' => $bounced,
				'url' => '#/drilldown/bounced'
			);
			$summaryChartData[] = array(
				'title' => "No Aperturas",
				'value' => $unopened,
				'url' => '#'
			);
			
			$statisticsData = new stdClass();
			$statisticsData->mailName = $mail->name;
			$statisticsData->total = $total;
			$statisticsData->opens = $opens;
			$statisticsData->statopens = round(( $opens / $total ) * 100 );
			$statisticsData->clicks = $clicks;
			$statisticsData->statclicks = round(( $clicks / $total ) * 100 );
			$statisticsData->totalclicks = $clicks;
			$statisticsData->stattotalclicks = round(( $clicks / $total ) * 100 );
			$statisticsData->statCTRclicks = round(( $clicks / $opens ) * 100 );
			$statisticsData->bounced = $bounced;
			$statisticsData->statbounced = round(( $bounced / $total ) * 100 );
			$statisticsData->softbounced = 1;
			$statisticsData->statsoftbounced = round(( $statisticsData->softbounced / $bounced ) * 100 );
			$statisticsData->hardbounced = 1;
			$statisticsData->stathardbounced = round(( $statisticsData->hardbounced / $bounced ) * 100 );
			$statisticsData->otherbounced = 0;
			$statisticsData->statotherbounced = round(( $statisticsData->otherbounced / $bounced ) * 100 );
			$statisticsData->unsubscribed = $unsubscribed;
			$statisticsData->statunsubscribed = round (( $unsubscribed / $total ) * 100 );
			$statisticsData->spam = $spam;
			$statisticsData->statspam = round(( $spam / $total ) * 100 );
			
			$allMails = Mail::find(array(
				'conditions' => 'idAccount = ?1 AND status = "Sent" AND idMail != ?2',
				'bind' => array(1 => $this->account->idAccount, 2 => $idMail)
			));
			
			$mailCompare = array();
			foreach ($allMails as $m) {
				$objfc = new stdClass();
				$objfc->id = $m->idMail;
				$objfc->name = $m->name;
				$mailCompare[] = $objfc;				
			}
			
			$response['summaryChartData'] = $summaryChartData;
			$response['statisticsData'] = $statisticsData;
			$response['compareMail'] = $mailCompare;
			
			return $response;
		}
		else {
			return FALSE;
		}
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
			$idContactlist =  $s->idContactlist;
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

		$stat->idContactlist = $idContactlist;
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
			$idDbase =  $s->idDbase;
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
		$opens = array();
		$h1 = 1380657600;
		$v1 = 3000;
		$v2 = 2900;
//		for ($i = 0; $i < 1800; $i++) {
//			$value = rand($v1, $v2);
//			if($i == 20 || $i == 100 || $i == 150) {
//				$value = 0;
//			}
//			$opens[] = array(
//				'title' =>$h1,
//				'value' => $value
//			);
//			$v1 = $v1 - 1;
//			$v2 = $v2 - 1;
//			$h1+=3600;
//		}
		
		$opens[] = array(
				'title' =>1380657600,
				'value' => 50
			);
		
		$opens[] = array(
				'title' =>1380661200,
				'value' => 34
			);
		
		$opens[] = array(
				'title' =>1387137600,
				'value' => 68
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
		
		$opencontact[] = array(
			'id' => 199,
			'email' => 'recipient00003@test007.local.discardallmail.drh.net',
			'date' => date('Y-m-d',1386688891),
			'os' => 'Windows Phone'
		);
		
		$this->pager->setTotalRecords(count($opencontact));
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($response['statistics'] = $opens),
			'details' => json_encode($response['details'] = $opencontact)
		);
		
		$this->pager->setRowsInCurrentPage(count($opencontact));
		
		return array('drilldownopen' => $statistics, 'meta' => $this->pager->getPaginationObject());
	}
	
	public function findMailClickStats($idMail)
	{
		$clicks = array();
		$h1 = 1380657600;
		$v1 = 3000;
		$v2 = 2900;
		for ($i = 0; $i < 50; $i++) {
			$value = rand($v1, $v2);
			if($i < 15) {
				$values[0] = $value;
				$values[1] = 0;
				$values[2] = 0;
			}
			else if($i < 35) {
				$values[0] = 0;
				$values[1] = $value;
				$values[2] = 0;
			}
			else {
				$values[0] = 0;
				$values[1] = 0;
				$values[2] = $value;
			}
			$clicks[] = array(
				'title' =>$h1,
				'value' => json_encode($values)
				);
			$v1 = $v1 - 1;
			$v2 = $v2 - 1;
			$h1+=3600;
		}
		
		$valueLinks[0] = 'https://www.google.com';
		$valueLinks[1] = 'https://www.facebook.com';
		$valueLinks[2] = 'https://www.twitter.com';
		
		$info[] = array(
			'amount' => 3,
			'value' => $valueLinks
		);
		
		$clickcontact[] = array(
			'id' => 100,
			'email' => 'otrocorreo@otro.correo',
			'date' => date('Y-m-d h:i', 1386878942),
			'link' => 'https://www.google.com'
		);
		
		$clickcontact[] = array(
			'id' => 145,
			'email' => 'otrocorreo2@otro2.correo2',
			'date' => date('Y-m-d h:i',1386747891),
			'link' => 'https://www.facebook.com'
		);
		
		$clickcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d h:i',1386698537),
			'link' => 'https://www.google.com'
		);
		
		$clickcontact[] = array(
			'id' => 162,
			'email' => 'otrocorreo67@otro67.correo67',
			'date' => date('Y-m-d',1386687891),
			'link' => 'https://www.twitter.com'
		);
		
		$links[] = array(
			'link' => 'https://www.google.com',
			'total' => 23,
			'uniques' => 15,
		);
		
		$links[] = array(
			'link' => 'https://www.facebook.com',
			'total' => 17,
			'uniques' => 4,
		);
		
		$links[] = array(
			'link' => 'https://www.twitter.com',
			'total' => 42,
			'uniques' => 19,
		);
		
		$this->pager->setTotalRecords(count($clickcontact));
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($clicks),
			'details' => json_encode($clickcontact),
			'links' => json_encode($links),
			'multvalchart' => json_encode($info),
		);
		
		$this->pager->setRowsInCurrentPage(count($clickcontact));
		
		return array('drilldownclick' => $statistics, 'meta' => $this->pager->getPaginationObject());
	}
	
	public function findMailUnsubscribedStats($idMail)
	{
		$unsubscribed = array();
		$h1 = 1380657600;
		$v1 = 3000;
		$v2 = 2900;
		for ($i = 0; $i < 1800; $i++) {
			$value = rand($v1, $v2);
			if($i == 20 || $i == 100 || $i == 150) {
				$value = 0;
			}
			$unsubscribed[] = array(
				'title' =>$h1,
				'value' => $value
			);
			$v1 = $v1 - 1;
			$v2 = $v2 - 1;
			$h1+=3600;
		}
		
		$unsubscribedcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => date('Y-m-d h:i', 1386687891),
			'name' => 'fulano',
			'lastname' => ''
		);
		
		$unsubscribedcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => date('Y-m-d h:i',1386687891),
			'name' => '',
			'lastname' => 'perez2'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => date('Y-m-d h:i',1386687891),
			'name' => 'fulano3',
			'lastname' => 'perez3'
		);
		
		$unsubscribedcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d h:i',1386687891),
			'name' => '',
			'lastname' => ''
		);
		
		$this->pager->setTotalRecords(count($unsubscribedcontact));
		
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
		$spam = array();
		$h1 = 1380657600;
		$v1 = 3000;
		$v2 = 2900;
		for ($i = 0; $i < 1800; $i++) {
			$value = rand($v1, $v2);
			if($i == 20 || $i == 100 || $i == 150) {
				$value = 0;
			}
			$spam[] = array(
				'title' =>$h1,
				'value' => $value
			);
			$v1 = $v1 - 1;
			$v2 = $v2 - 1;
			$h1+=3600;
		}
		
		$spamcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => date('Y-m-d h:i', 1386687891),
			'name' => 'fulano',
			'lastname' => ''
		);
		
		$spamcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => date('Y-m-d h:i',1386687891),
			'name' => '',
			'lastname' => 'perez2'
		);
		
		$spamcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => date('Y-m-d h:i',1386687891),
			'name' => 'fulano3',
			'lastname' => 'perez3'
		);
		
		$spamcontact[] = array(
			'id' => 161,
			'email' => 'otrocorreo3@otro3.correo3',
			'date' => date('Y-m-d h:i',1386687891),
			'name' => '',
			'lastname' => ''
		);
		
		$this->pager->setTotalRecords(count($spamcontact));
		
		$statistics[] = array(
			'id' => $idMail,
			'statistics' => json_encode($spam),
			'details' => json_encode($spamcontact)
		);
		
		$this->pager->setRowsInCurrentPage(count($spamcontact));
		
		return array('drilldownspam' => $statistics, 'meta' =>  $this->pager->getPaginationObject());
	}
	
	public function findMailBouncedStats($idMail)
	{
		$bounced = array();
		$h1 = 1380657600;
		$v1 = 3000;
		$v2 = 2900;
		for ($i = 0; $i < 1800; $i++) {
			$value = rand($v1, $v2);
			if($i == 20 || $i == 100 || $i == 150) {
				$value = 0;
			}
			if($i < 598) {
				$values[0] = $value;
				$values[1] = 0;
				$values[2] = 0;
			}
			else if($i < 1302) {
				$values[0] = 0;
				$values[1] = $value;
				$values[2] = 0;

			}
			else {
				$values[0] = 0;
				$values[1] = 0;
				$values[2] = $value;
			}
			$bounced[] = array(
				'title' =>$h1,
				'value' => json_encode($values)
				);
			
			$v1 = $v1 - 1;
			$v2 = $v2 - 1;
			$h1+=3600;
		}
		
		$bouncedcontact[] = array(
			'id' => 20,
			'email' => 'newmail@new.mail',
			'date' => date('Y-m-d h:i', 1386687891),
			'type' => 'Temporal',
			'category' => 'Buzon Lleno',
			'domain' => 'new.mail'
		);
		
		$bouncedcontact[] = array(
			'id' => 240,
			'email' => 'newmail1@new1.mail1',
			'date' => date('Y-m-d h:i',1386687891),
			'type' => 'Otro',
			'category' => 'Rebote General',
			'domain' => 'new1.mail1'
		);
		
		$bouncedcontact[] = array(
			'id' => 57,
			'email' => 'newmail2@new2.mail2',
			'date' => date('Y-m-d h:i',1386687891),
			'type' => 'Permanente',
			'category' => 'Direccion Mala',
			'domain' => 'new2.mail2'
		);
		
		$bouncedcontact[] = array(
			'id' => 59,
			'email' => 'newmail54@new3.mail3',
			'date' => date('Y-m-d h:i',1386687891),
			'type' => 'Temporal',
			'category' => 'Buzon Lleno',
			'domain' => 'new3.mail3'
		);
		
		$valueType[0] = 'Temporales';
		$valueType[1] = 'Permanentes';
		$valueType[2] = 'Otros';
		
		$valueCategory[0] = 'Buzon Lleno';
		$valueCategory[1] = 'Direccion Mala';
		$valueCategory[2] = 'Rebote General';
		
		$valueDomain[0] = 'new.mail';
		$valueDomain[1] = 'new1.mail1';
		$valueDomain[2] = 'new2.mail2';
		$valueDomain[3] = 'new3.mail3';
		
		$info[] = array(
			'amount' => 3,
			'value' => $valueType,
			'category' => $valueCategory,
			'domain' => $valueDomain
		);
		
		$this->pager->setTotalRecords(count($bouncedcontact));
		
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
