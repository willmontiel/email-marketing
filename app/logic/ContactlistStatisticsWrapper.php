<?php
class ContactlistStatisticsWrapper extends BaseWrapper
{
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