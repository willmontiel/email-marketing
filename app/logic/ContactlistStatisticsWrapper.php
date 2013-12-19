<?php
class ContactlistStatisticsWrapper extends BaseWrapper
{
	public function getOpenStats($stat)
	{
		$stats = array();
		$mail = array();
		
		foreach ($stat as $s) {
			$idContactlist =  $s->idContactlist;
			$mailStat = new stdClass();
			$mailStat->idMail = $s->idMail;
			$mailStat->title = $s->sentDate;
			$mailStat->value = $s->sent;
			
			$mail[] = $mailStat;
		}
		
		$stats[] = array(
			'id' => intval($idContactlist),
			'statistics' => json_encode($mail),
			'details' => 'lala'
		);
		
		return array('drilldownopen' => $stats) ;
	}
}