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
}