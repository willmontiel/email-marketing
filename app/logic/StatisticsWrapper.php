<?php
class StatisticsWrapper extends BaseWrapper
{
	public function showMailStatistics($idMail)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
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
				'url' => '#'
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
			$statisticsData->statopens = ( $opens / $total ) * 100 ;
			$statisticsData->clicks = $clicks;
			$statisticsData->statclicks = ( $clicks / $opens ) * 100 ;
			$statisticsData->bounced = $bounced;
			$statisticsData->statbounced = ( $bounced / $total ) * 100 ;
			$statisticsData->unsubscribed = $unsubscribed;
			$statisticsData->statunsubscribed = ( $unsubscribed / $total ) * 100 ;
			$statisticsData->spam = $spam;
			$statisticsData->statspam = ( $spam / $total ) * 100 ;

			$response['summaryChartData'] = $summaryChartData;
			$response['statisticsData'] = $statisticsData;
			
			return $response;
		}
		else {
			return FALSE;
		}
	}
	
	public function findMailOpenStats($idMail)
	{
		$opens = array();
		$h1 = 1380657600;
		$v1 = 3000;
		$v2 = 2900;
		for ($i = 0; $i < 1800; $i++) {
			$value = rand($v1, $v2);
			if($i == 20 || $i == 100 || $i == 150) {
				$value = 0;
			}
			$opens[] = array(
				'title' =>$h1,
				'value' => $value
			);
			$v1 = $v1 - 1;
			$v2 = $v2 - 1;
			$h1+=3600;
		}
		
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
		
		$response['statistics'] = $opens;
		$response['details'] = $opencontact;
			
		return $response;
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
				$link = 'https://www.google.com';
			}
			else if($i < 35) {
				$link = 'https://www.facebook.com';
			}
			else {
				$link = 'https://www.twitter.com';
			}
			$clicks[] = array(
				'title' =>$h1,
				'value' => $value,
				'link' => $link
			);
			$v1 = $v1 - 1;
			$v2 = $v2 - 1;
			$h1+=3600;
		}
		
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
		
		$response['statistics'] = $clicks;
		$response['details'] = $clickcontact;
		$response['links'] = $links;
			
		return $response;
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
		
		$response['statistics'] = $unsubscribed;
		$response['details'] = $unsubscribedcontact;
			
		return $response;
	}
	
	public function findDbaseOpenStats($idDbase)
	{
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
			'os' => 'Mac'
		);
		
		$response['statistics'] = $opens;
		$response['details'] = $opencontact;
			
		return $response;
	}
	
	public function findDbaseClickStats($idDbase)
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
		
		$response['statistics'] = $clicks;
		$response['details'] = $clickcontact;
			
		return $response;
	}
	
	public function findDbaseUnsubscribedStats($idDbase)
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
		
		$response['statistics'] = $unsubscribed;
		$response['details'] = $unsubscribedcontact;
			
		return $response;
	}
	
	public function getOpenStats($idContactList)
	{
		$statsContactList = Statcontactlist::find(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $idContactList)
		));
		
		$stats = array();
		$mail = array();
		
		foreach ($statsContactList as $s) {
			$dt = new DateTime();
			$dt->setTimestamp($s->sentDate);
			$idContactlist =  $s->idContactlist;
			$mailStat = new stdClass();
			$mailStat->idMail = $s->idMail;
			$mailStat->title = $dt->format('d/M/Y');
			$mailStat->value = $s->uniqueOpens;
			
			$mail[] = $mailStat;
		}
		
		$stats[] = array(
			'id' => intval($idContactlist),
			'statistics' => json_encode($mail),
			'details' => json_encode($det['opens'] = array('lala'))
		);
		
		return array('drilldownopen' => $stats) ;
	}
	
}
