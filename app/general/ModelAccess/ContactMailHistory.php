<?php

namespace EmailMarketing\General\ModelAccess;

/**
 * This class search information about mail that is related with the contacts
 */
class ContactMailHistory
{
	private $mailh;
	
	public function findMailHistory($idContact)
	{
		$db = \Phalcon\DI::getDefault()->get('db');
		
		$mailh = array();
		
		$query = "SELECT m.name, c.opening, c.clicks, c.bounced, c.spam, c.unsubscribe
					FROM mxc AS c JOIN mail AS m ON (c.idMail = m.idMail)
				 WHERE c.idContact = {$idContact} ORDER BY m.createdon";
				 
		$r = $db->query($query);
		$alldata = $r->fetchAll();
		
		if(count($alldata) > 0) {
			foreach ($alldata as $a) {
				$mailh[] = array(
					'name' => $a['name'],
					'opening' => ($a['opening'] != 0) ? date('d-m-Y', $a['opening']) : NULL,
					'clicks' => ($a['clicks'] != 0) ? date('d-m-Y', $a['clicks']) : NULL,
					'bounced' => ($a['bounced'] != 0) ? date('d-m-Y', $a['bounced']) : NULL,
					'spam' => ($a['spam'] != 0) ? date('d-m-Y', $a['spam']) : NULL,
					'unsubscribe' => ($a['unsubscribe'] != 0) ? date('d-m-Y', $a['unsubscribe']) : NULL,
				);
			}
		}
		$this->mailh = json_encode($mailh);
	}
	
	public function getMailHistory()
	{
		return $this->mailh;
	}
}
