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
//					throw new \InvalidArgumentException('transaction test');
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
//					$i = 0;
					foreach ($idContactlists as $idContactlist) {
						$statcontactlist = Statcontactlist::findFirst(array(
							'conditions' => 'idContactlist = ?1 AND idMail = ?2',
							'bind' => array(1 => $idContactlist,
											2 => $idMail)
						));
						
						$statcontactlist->uniqueOpens += 1;
//						$i++;
//						$this->log->log('Se contabilizó apertura para la lista: ' . $idContactlist);
						if (!$statcontactlist->save()) {
							$this->log->log('No se guardó');
							foreach ($statcontactlist->getMessages() as $msg) {
								$this->log->log('Error : '. $msg);
							}
							throw new \InvalidArgumentException('Error while updating statcontactlist');
						}
					}
//					$this->log->log('Se contabilizarón: ' . $i . ' En este request');
					$this->log->log('Se guardó statcontactlist');
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
				$db = Phalcon\DI::getDefault()->get('db');
				try {
					$db->begin();
					
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

					$contact = Contact::findFirst(array(
						'conditions' => 'idContact = ?1',
						'bind' => array(1 => $idContact)
					));

					$statdbase = Statdbase::findFirst(array(
						'conditions' => 'idDbase = ?1 AND idMail = ?2',
						'bind' => array(1 => $contact->idDbase,
										2 => $idMail)
					));

					$idContactlists = explode(",", $mxc->contactlists);

	//				$this->log->log('Verificando Event');				

					if ($mxc->opening == 0 && $mxc->bounced !== 0 && $mxc->spam !== 0 && $mxc->status == 'sent') {
						$event1 = new Mailevent();
						$event1->idMail = $idMail;
						$event1->idContact = $idContact;
						$event1->description = 'opening per click';
						$event1->userAgent = $so . ', ' . $browser;
						$event1->location = ' ';

						if (!$event1->save()) {
							foreach ($event1->getMessages() as $msg) {
								$this->log->log('Error while saving event: ' . $msg);
							}
							throw new \InvalidArgumentException('Error while saving Mailevent');
						}
						$this->log->log('Se guardó evento de apertura por click');
						
						$event2 = new Mailevent();
						$event2->idMail = $idMail;
						$event2->idContact = $idContact;
						$event2->description = 'click';
						$event2->userAgent = $so . ', ' . $browser;
						$event2->location = ' ';

						$mxc->opening = time();
						$mxc->clicks = time();

						$mail->uniqueOpens += 1;
						$mail->clicks += 1;

						$statdbase->uniqueOpens += 1;
						$statdbase->clicks += 1;

						foreach ($idContactlists as $idContactlist) {
							$statcontactlist = Statcontactlist::findFirst(array(
								'conditions' => 'idContactlist = ?1 AND idMail = ?2',
								'bind' => array(1 => $idContactlist,
												2 => $idMail)
							));

							$statcontactlist->uniqueOpens += 1;
							$statcontactlist->clicks += 1;

							if (!$statcontactlist->save()) {
								$this->log->log('No se guardó');
								foreach ($statcontactlist->getMessages() as $msg) {
									$this->log->log('Error : '. $msg);
								}
								throw new \InvalidArgumentException('Error while updating statcontactlist');
							}
							$this->log->log('Apertura contablizada por click y tambien click contabilizado en statcontactlist');
						}
					}
					else {
						$event2 = new Mailevent();
						$event2->idMail = $idMail;
						$event2->idContact = $idContact;
						$event2->description = 'click';
						$event2->userAgent = $so . ', ' . $browser;
						$event2->location = ' ';

						$mxc->clicks = time();

						$mail->clicks += 1;

						$statdbase->clicks += 1;

						foreach ($idContactlists as $idContactlist) {
							$statcontactlist = Statcontactlist::findFirst(array(
								'conditions' => 'idContactlist = ?1 AND idMail = ?2',
								'bind' => array(1 => $idContactlist,
												2 => $idMail)
							));

							$statcontactlist->clicks += 1;

							if (!$statcontactlist->save()) {
								$this->log->log('No se guardó');
								foreach ($statcontactlist->getMessages() as $msg) {
									$this->log->log('Error : '. $msg);
								}
								throw new \InvalidArgumentException('Error while updating statcontactlist');
							}
							$this->log->log('click contabilizado en statcontactlist');
						}
					}

					if (!$event2->save()) {
						foreach ($event2->getMessages() as $msg) {
							$this->log->log('Error saving Mailevent: ' . $msg);
						}
						throw new \InvalidArgumentException('Error while updating MailEvent');
					}
					
					$this->log->log('Guardó evento por click');

					if (!$mxc->save()) {
						foreach ($mxc->getMessages() as $msg) {
							$this->log->log('Error saving Mailevent: ' . $msg);
						}
						throw new \InvalidArgumentException('Error while updating MailEvent');
					}
					$this->log->log('Actualizado mxc');
					if (!$mail->save()) {
						foreach ($mail->getMessages() as $msg) {
							$this->log->log('Error saving Mailevent: ' . $msg);
						}
						throw new \InvalidArgumentException('Error while updating MailEvent');
					}
					$this->log->log('Actualizado mail');
					if (!$statdbase->save()) {
						foreach ($statdbase->getMessages() as $msg) {
							$this->log->log('Error saving Statdbase: ' . $msg);
						}
						throw new \InvalidArgumentException('Error while updating MailEvent');
					}
					$this->log->log('Actualizado statdbase');
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
					$this->log->log('Actualizado mxl');
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
					$this->log->log('Inserción en mxcxl');
					$db->commit();
					
					return $Maillink->link;
				}
				catch (InvalidArgumentException $e) {
					$this->log->log('Excepcion, realizando ROLLBACK: '. $e);
					$db->rollback();
				}
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
