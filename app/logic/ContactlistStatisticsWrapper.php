<?php
class ContactlistStatisticsWrapper extends BaseWrapper
{
	public function convertStatContactList($stat)
	{
		
	}
	
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
		$stats['id'] = intval($idContactlist);
		$stats['statistics'] = json_encode($mail);
		$stats['details'] = 'lala';
		
		return array('drilldowncontactlist' => $stats) ;
	}
}