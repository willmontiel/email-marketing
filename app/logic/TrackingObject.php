<?php
class TrackingObject
{
	public function __construct() 
	{
		
	}
	
	public function updateTracking($idMail, $idContact)
	{
		$mxc = Mxc::findFirst(array(
			'conditions' => 'idMxc = ?1 AND idContact = ?2',
			'bind' => array(1 => $idMail,
							2 => $idContact)
		));

		if ($mxc) {
			if ($mxc->opening == 0 || $mxc->bounced == 0 || $mxc->spam == 0) {
				$mxc->opening = time();
				$mxc->bounced = time();
				$mxc->spam = time();
				
				$event = new Mailevent();
				$event->idMail = $idMail;
				$event->idContact = $idContact;
				$event->type;
				$event->date = time();
				$event->description;
			}
			else {
				
			}
		}
	}
}
