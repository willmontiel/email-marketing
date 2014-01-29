<?php
class TrackingObject
{
	public $log;
	public $db;
	
	public function __construct() 
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function updateTrackOpen($idMail, $idContact, $so = null, $browser = null)
	{
		$mxc = Mxc::findFirst(array(
			'conditions' => 'idMail = ?1 AND idContact = ?2',
			'bind' => array(1 => $idMail,
							2 => $idContact)
		));
		if ($mxc) {
			if ($mxc->opening == 0 && $mxc->bounced !== 0 && $mxc->spam !== 0 && $mxc->status == 'sent') {
				$this->log->log('Es la primera apertura');
//				$this->log->log('So: ' . $so . 'Browser: ' . $browser);
				try {
					$db = Phalcon\DI::getDefault()->get('db');
					$db->begin();
					$mxc->opening = time();
					$mxc->bounced = time();
					$mxc->spam = time();

					if (!$mxc->save()) {
						foreach ($mxc->getMessages() as $msg) {
							$this->log->log('Error : '. $msg);
						}
						throw new \InvalidArgumentException('Error while updating mxc');
					}
					$this->log->log('Guardó mxc');
					$event = new Mailevent();
					$event->idMail = $idMail;
					$event->idContact = $idContact;
					$event->type = 'Opening';
					$event->date = time();
					$event->description = 'opening';
					$event->userAgent = $so . ', ' . $browser;
					$event->location = ' ';

					if (!$event->save()) {
						foreach ($event->getMessages() as $msg) {
							$this->log->log('Error : '. $msg);
						}
						throw new \InvalidArgumentException('Error while updating event');
					}
					$this->log->log('Guardó event');
					$mail = Mail::findFirst(array(
						'conditions' => 'idMail = ?1',
						'bind' => array(1 => $idMail)
					));

					$mail->uniqueOpens += 1;

					if (!$mail->save()) {
						foreach ($mail->getMessages() as $msg) {
							$this->log->log('Error : '. $msg);
						}
						throw new \InvalidArgumentException('Error while updating mail');
					}
					$this->log->log('Guardó mail');

					$contact = Contact::findFirst(array(
						'conditions' => 'idContact = ?1',
						'bind' => array(1 => $idContact)
					));

					$statdbase = Statdbase::findFirst(array(
						'conditions' => 'idDbase = ?1 AND idMail = ?2',
						'bind' => array(1 => $contact->idDbase,
										2 => $idMail)
					));

					$statdbase->uniqueOpens += 1;

					if (!$statdbase->save()) {
						foreach ($statdbase->getMessages() as $msg) {
							$this->log->log('Error : '. $msg);
						}
						throw new \InvalidArgumentException('Error while updating statdbase');
					}
	
					$idContactlists = explode(",", $mxc->contactlists);
	
					foreach ($idContactlists as $idContactlist) {
	
						$statcontactlist = Statcontactlist::findFirst(array(
							'conditions' => 'idContactlist = ?1 AND idMail = ?2',
							'bind' => array(1 => $idContactlist,
											2 => $idMail)
						));
						
						$statcontactlist->uniqueOpens += 1;
	
						if (!$statcontactlist->save()) {
							$this->log->log('No se guardó');
							foreach ($statcontactlist->getMessages() as $msg) {
								$this->log->log('Error : '. $msg);
							}
							throw new \InvalidArgumentException('Error while updating statcontactlist');
						}
					}
					$db->commit();
				}
				catch (InvalidArgumentException $e) {
					$db->rollback();
				}
			}
			else {
				$this->log->log('No es la primera apertura, ya se ha contabilizado');
				return false;
			}
		}
		else {
			$this->log->log('No existe mxc');
			return false;
		}
	}
	
	public function updateTrackClick()
	{
		
	}
}
