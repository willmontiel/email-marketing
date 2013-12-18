<?php
class ContactlistStatisticsWrapper extends BaseWrapper
{
	public function convertStatContactList($stat)
	{
		$mail = array();
		foreach ($stat as $s) {
			$idContactlist =  $s->idContactlist;
			
			$mailStat = new stdClass();
			$mailStat->idMail = $s->idMail;
			$mailStat->date = $s->sentDate;
			$mailStat->sent = $s->sent;
			
			$mail[] = $mailStat;
		}
		
		$object = array();
		$object['id'] = intval($idContactlist);
		$object['statistic'] = $mail;
		$object['details'] = 'lala';

		return $object;
	}
	
	public function getOpenStats($stat)
	{
		$stats = $this->convertStatContactList($stat);
		return array('Contactliststatistic' => $stats) ;
	}
}