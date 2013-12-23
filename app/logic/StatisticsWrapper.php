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
			$statisticsData->statopens = round(( $opens / $total ) * 100 );
			$statisticsData->clicks = $clicks;
			$statisticsData->statclicks = round(( $clicks / $opens ) * 100 );
			$statisticsData->bounced = $bounced;
			$statisticsData->statbounced = round(( $bounced / $total ) * 100 );
			$statisticsData->unsubscribed = $unsubscribed;
			$statisticsData->statunsubscribed = round(( $unsubscribed / $total ) * 100 );
			$statisticsData->spam = $spam;
			$statisticsData->statspam = round(( $spam / $total ) * 100 );

			$response['summaryChartData'] = $summaryChartData;
			$response['statisticsData'] = $statisticsData;
			
			return $response;
		}
		else {
			return FALSE;
		}
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
