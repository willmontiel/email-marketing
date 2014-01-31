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
				$db = Phalcon\DI::getDefault()->get('db');
				try {
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
					$event->opening = time();
					$event->userAgent = $so . ', ' . $browser;
					$event->location = ' ';

					if (!$event->save()) {
						foreach ($event->getMessages() as $msg) {
							$this->log->log('Error : '. $msg);
						}
						throw new \InvalidArgumentException('Error while updating event');
					}
					$this->log->log('Guardó event');
					
//					throw new \InvalidArgumentException('Tiene que hacer rollback!');
					
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
					$this->log->log('Excepcion, realizando ROLLBACK: '. $e);
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
	
	public function updateTrackClick($idLink, $idMail, $idContact, $so = null, $browser = null)
	{
//		$this->log->log('Inicio de tracking de clicks');
		$mxl = Mxl::findFirst(array(
			'conditions' => 'idMail = ?1 AND idMailLink = ?2',
			'bind' => array(1 => $idMail,
							2 => $idLink)
		));
		
		if ($mxl) {
//			$this->log->log('Existe Mxl');
			$mxcxl = Mxcxl::findFirst(array(
				'conditions' => 'idMail = ?1 AND idMailLink = ?2 AND idContact = ?3',
				'bind' => array(1 => $idMail,
								2 => $idLink,
								3 => $idContact)
			));
			$Maillink = Maillink::findFirst(array(
				'conditions' => 'idMailLink = ?1',
				'bind' => array(1 => $idLink)
			));
			
			if (!$mxcxl) {
//				$this->log->log('No Existe Mxcxl');
				$mail = Mail::findFirst(array(
					'conditions' => 'idMail = ?1',
					'bind' => array(1 => $idMail)
				));
//				$this->log->log('Existe mail');
				$mxc = Mxc::findFirst(array(
					'conditions' => 'idMail = ?1 AND idContact = ?2',
					'bind' => array(1 => $idMail,
									2 => $idContact)
				));
//				$this->log->log('Existe Mxc');
				$event = Mailevent::findFirst(array(
					'conditions' => 'idMail = ?1 AND idContact = ?2',
					'bind' => array(1 => $idMail,
									2 => $idContact)
				));
//				$this->log->log('Verificando Event');
				if (!$event) {
//					$this->log->log('No existe Event');
					unset($event);
					$event = new Mailevent();
					$event->idMail = $idMail;
					$event->idContact = $idContact;
					$event->opening = time();
					$event->click = time();
					$event->userAgent = $so . ', ' . $browser;
					$event->location = ' ';
					
					$mxc->opening = time();
					$mxc->clicks = time();
					
					$mail->uniqueOpens += 1;
					$mail->clicks += 1;
				}
				else {
					$this->log->log('Si existe event');
					$event->click = time();
					$mxc->clicks = time();
					
					$mail->clicks += 1;
				}
				
				if (!$event->save()) {
					foreach ($event->getMessages() as $msg) {
						$this->log->log('Error saving Mailevent: ' . $msg);
					}
					throw new \InvalidArgumentException('Error while updating MailEvent');
				}
				
				if (!$mxc->save()) {
					foreach ($mxc->getMessages() as $msg) {
						$this->log->log('Error saving Mailevent: ' . $msg);
					}
					throw new \InvalidArgumentException('Error while updating MailEvent');
				}
				
				if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->log->log('Error saving Mailevent: ' . $msg);
					}
					throw new \InvalidArgumentException('Error while updating MailEvent');
				}
				/*========================================
				 * Hasta aqui se actualiza Mailevent, Mxc y Mail
				 */
				$mxl->totalClicks += 1;
				
				if (!$mxl->save()) {
					foreach ($mxl->getMessages() as $msg) {
						$this->log->log('Error saving Mxl: ' . $msg);
					}
					throw new \InvalidArgumentException('Error while updating Mxl');
				}
				
				$mxCxL = new Mxcxl();
				
				$mxCxL->idMail = $idMail;
				$mxCxL->idMailLink = $idLink;
				$mxCxL->idContact = $idContact;
				$mxCxL->click = time(); 
			
				if (!$mxCxL->save()) {
					foreach ($mxCxL->getMessages() as $msg) {
						$this->log->log('Error saving Mxl: ' . $msg);
					}
					throw new \InvalidArgumentException('Error while updating Mxcxl');
				}
				
				return $Maillink->link;
			}
			else {
				$this->log->log('Este usuario ya ha sido contabilizado');
				return $Maillink->link;
			}
		}
		else {
			$this->log->log('No existe registro del link ni del correo');
			return false;
		}
	}
}
