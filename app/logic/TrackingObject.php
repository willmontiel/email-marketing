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
			if ($mxc->opening == 0 && $mxc->bounced == 0 && $mxc->spam == 0 && $mxc->status == 'sent') {
				$this->log->log('Es la primera apertura del contacto: ' . $idContact);
//				$this->log->log('So: ' . $so . 'Browser: ' . $browser);
				$db = Phalcon\DI::getDefault()->get('db');
				try {
					$db->begin();
					
					$mailXcontact = $this->updateMxcStat($mxc, 'openings');
					if (!$mailXcontact) {
						throw new \InvalidArgumentException('Error while updating mxc');
					}
					$this->log->log('Guardó mxc');
					
					$mail = $this->updateMailStat($idMail, 'openings');
					if (!$mail) {
						throw new \InvalidArgumentException('Error while updating mail');
					}
					$this->log->log('Guardó mail');
					
//					throw new \InvalidArgumentException('transaction test');
					
					$statdbase = $this->updateStatDbase($idContact, $idMail, 'openings');
					if (!$statdbase) {
						throw new \InvalidArgumentException('Error while updating statdbase');
					}
					$this->log->log('Se guardó statdbase');
					
					$statcontactlist = $this->updateStatContactLists($mxc, 'openings');
					if (!$statcontactlist) {
						throw new \InvalidArgumentException('Error while updating statcontactlist');
					}
					$this->log->log('Se guardó statcontactlist');
					
					$userAgent = $so . ', ' . $browser;
					$mailEvent = $this->saveMailEvent($idMail, $idContact, 'openings', $userAgent);
					if (!$mailEvent) {
						throw new \InvalidArgumentException('Error while updating event');
					}
					$this->log->log('Guardó event');
					
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
			$userAgent = $so . ', ' . $browser;
			
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
					
					$mxc = Mxc::findFirst(array(
						'conditions' => 'idMail = ?1 AND idContact = ?2',
						'bind' => array(1 => $idMail,
										2 => $idContact)
					));

					if ($mxc && ($mxc->opening == 0 && $mxc->bounced == 0 && $mxc->spam == 0 && $mxc->status == 'sent')) {
						
						$mailXcontact = $this->updateMxcStat($mxc, 'openingsxclick');
						if (!$mailXcontact) {
							throw new \InvalidArgumentException('Error while updating mxc');
						}
						$this->log->log('Click y apertura por click mxc');
						
						$mail = $this->updateMailStat($idMail, 'openingsxclick');
						if (!$mail) {
							throw new \InvalidArgumentException('Error while updating mail');
						}
						$this->log->log('Click y apertura por click en mail');

						$statdbase = $this->updateStatDbase($idContact, $idMail, 'openingsxclick');
						if (!$statdbase) {
							throw new \InvalidArgumentException('Error while updating statdbase');
						}
						$this->log->log('Click y apertura por click en statdbase');
						
//						throw new \InvalidArgumentException('transaction test');
						
						$statcontactlist = $this->updateStatContactLists($mxc, 'openingsxclick');
						if (!$statcontactlist) {
							throw new \InvalidArgumentException('Error while updating statcontactlist');
						}
						$this->log->log('Click y apertura por click en statcontactlist');
						
						$mailEvent1 = $this->saveMailEvent($idMail, $idContact, 'opening per click', $userAgent);
						if (!$mailEvent1) {
							throw new \InvalidArgumentException('Error while updating opening event');
						}
						$this->log->log('Se guardó evento de apertura por click');
					}
					else {
						$mailXcontact = $this->updateMxcStat($mxc, 'clicks');
						if (!$mailXcontact) {
							throw new \InvalidArgumentException('Error while updating mxc');
						}
						$this->log->log('click en mxc');
						
						$mail = $this->updateMailStat($idMail, 'clicks');
						if (!$mail) {
							throw new \InvalidArgumentException('Error while updating mail');
						}
						$this->log->log('click en mail');
						
						$statdbase = $this->updateStatDbase($idContact, $idMail, 'clicks');
						if (!$statdbase) {
							throw new \InvalidArgumentException('Error while updating statdbase');
						}
						$this->log->log('click contabilizado en statdbase');
						
//						throw new \InvalidArgumentException('transaction test');
						
						$statcontactlist = $this->updateStatContactLists($mxc, 'clicks');
						if (!$statcontactlist) {
							throw new \InvalidArgumentException('Error while updating statcontactlist');
						}
						$this->log->log('click contabilizado en statcontactlist');
					}
					
					$mailEvent2 = $this->saveMailEvent($idMail, $idContact, 'click', $userAgent);
					if (!$mailEvent2) {
						throw new \InvalidArgumentException('Error while updating click event');
					}
					$this->log->log('Guardó evento por click');
					/*========================================
					 * Hasta aqui se actualiza Mxc, Mail, stadbase, statcontactlist y mailevent
					 */
					$mxl->totalClicks += 1;

					if (!$mxl->save()) {
						foreach ($mxl->getMessages() as $msg) {
							$this->log->log('Error saving Mxl: ' . $msg);
						}
						throw new \InvalidArgumentException('Error while updating Mxl');
					}
//					throw new \InvalidArgumentException('transaction test');
					
					$this->log->log('Actualizado mxl');
					
					$mxcxl = new Mxcxl();

					$mxcxl->idMail = $idMail;
					$mxcxl->idMailLink = $idLink;
					$mxcxl->idContact = $idContact;
					$mxcxl->click = time(); 

					if (!$mxcxl->save()) {
						foreach ($mxcxl->getMessages() as $msg) {
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
	
	public function updateTrackBounced($idMail, $idContact)
	{
		$mxc = Mxc::findFirst(array(
			'conditions' => 'idMail = ?1 AND idContact = ?2',
			'bind' => array(1 => $idMail,
							2 => $idContact)
		));
		
		if ($mxc) {
			if ($mxc->opening == 0 && $mxc->bounced == 0 && $mxc->spam == 0 && $mxc->status == 'sent') {
				$db = Phalcon\DI::getDefault()->get('db');
				try {
					$type = true;

					$db->begin();
					$mailXcontact = $this->updateMxcStat($mxc, 'bounced');
					if (!$mailXcontact) {
						throw new \InvalidArgumentException('Error while updating mxc');
					}
					$this->logger->log('Se actualizó mxc');

					$mail = $this->updateMailStat($idMail, 'bounced');
					if (!$mail) {
						throw new \InvalidArgumentException('Error while updating mail');
					}
					$this->logger->log('Se actualizó Mail');

					$statdbase = $this->updateStatDbase($idContact, $idMail, 'bounced');
					if (!$statdbase) {
						throw new \InvalidArgumentException('Error while updating statdbase');
					}
					$this->logger->log('Se actualizó statDbase');

					$statcontactlist = $this->updateStatContactLists($mxc, 'bounced');
					if (!$statcontactlist) {
						throw new \InvalidArgumentException('Error while updating statcontactlist');
					}
					$this->logger->log('Se actualizó statContactlist');

					if ($type) {
						$date = time();
						
						$contact = Contact::findFirst(array(
							'conditions' => 'idContact = ?1',
							'bind' => array(1 => $idContact)
						));
						
						$sql = 'UPDATE email AS e JOIN contact AS c 
									ON (c.idEmail = e.idEmail)
									SET e.bounced = ' . $date . ', c.bounced = ' . $date . '
								WHERE e.idEmail = ?';
						$update = $db->execute($sql, array($contact->idEmail));

						if (!$update) {
							throw new \InvalidArgumentException('Error while updating contact and email');
						}
						$this->logger->log('Se actualizó contact y email');
					}
					$db->commit();
				}
				catch (InvalidArgumentException $e) {
					$this->log->log('Excepcion, realizando ROLLBACK: '. $e);
					$db->rollback();
				}
			}
			else {
				$this->logger->log('El contacto ya ha sido marcado como rebotado');
			}
		}
		else {
			$this->logger->log('No existe mxc, no hay registro del correo y el contacto');
		}
	}
	
	private function saveMailEvent($idMail, $idContact, $description, $userAgent = null, $location = null)
	{
		$event = new Mailevent();
		$event->idMail = $idMail;
		$event->idContact = $idContact;
		$event->description = $description;
		$event->userAgent = $userAgent;
		$event->location = $location;

		if (!$event->save()) {
			foreach ($event->getMessages() as $msg) {
				$this->log->log('Error : '. $msg);
			}
			return false;
		}
		return true;
	}
	
	private function updateStatContactLists(Mxc $mxc, $type)
	{
		$idContactlists = explode(",", $mxc->contactlists);
		
		foreach ($idContactlists as $idContactlist) {
			$statcontactlist = Statcontactlist::findFirst(array(
				'conditions' => 'idContactlist = ?1 AND idMail = ?2',
				'bind' => array(1 => $idContactlist,
								2 => $mxc->idMail)
			));
			
			switch ($type) {
				case 'openings':
					$statcontactlist->uniqueOpens += 1;
					break;
				
				case 'clicks':
					$statcontactlist->clicks += 1;
					break;
				
				case 'bounced':
					$statcontactlist->bounced += 1;
					break;
				
				case 'spam':
					$statcontactlist->spam += 1;
					break;
				
				case 'unsubscribed':
					$statcontactlist->unsubscribed += 1;
					break;
				
				case 'openingsxclick':
					$statcontactlist->uniqueOpens += 1;
					$statcontactlist->clicks += 1;
					break;
			}
//						$i++;
						$this->log->log('Se contabilizó apertura para la lista: ' . $idContactlist);
						$this->log->log('Contacto: ' . $mxc->idContact);
			if (!$statcontactlist->save()) {
				foreach ($statcontactlist->getMessages() as $msg) {
					$this->log->log('Error : '. $msg);
				}
				return false;
			}
			return true;
		}
	}
	
	private function updateStatDbase($idContact, $idMail, $type)
	{
		$contact = Contact::findFirst(array(
			'conditions' => 'idContact = ?1',
			'bind' => array(1 => $idContact)
		));

		$statdbase = Statdbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idMail = ?2',
			'bind' => array(1 => $contact->idDbase,
							2 => $idMail)
		));
		
		switch ($type) {
			case 'openings':
				$statdbase->uniqueOpens += 1;
				break;

			case 'clicks':
				$statdbase->clicks += 1;
				break;

			case 'bounced':
				$statdbase->bounced += 1;
				break;

			case 'spam':
				$statdbase->spam += 1;
				break;

			case 'unsubscribed':
				$statdbase->unsubscribed += 1;
				break;

			case 'openingsxclick':
				$statdbase->uniqueOpens += 1;
				$statdbase->clicks += 1;
				break;
		}

		if (!$statdbase->save()) {
			foreach ($statdbase->getMessages() as $msg) {
				$this->log->log('Error : '. $msg);
			}
			return false;
		}
		return true;
	}
	
	private function updateMxcStat(Mxc $mxc, $type)
	{
		switch ($type) {
			case 'openings':
				$mxc->opening = time();
				break;

			case 'clicks':
				$mxc->clicks = time();
				break;

			case 'bounced':
				$mxc->bounced = time();
				break;

			case 'spam':
				$mxc->spam = time();
				break;

			case 'openingsxclick':
				$mxc->opening = time();
				$mxc->clicks = time();
				break;
		}

		if (!$mxc->save()) {
			foreach ($mxc->getMessages() as $msg) {
				$this->log->log('Error : '. $msg);
			}
			return false;
		}
		return true;
	}

	private function updateMailStat($idMail, $type)
	{
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		switch ($type) {
			case 'openings':
				$mail->uniqueOpens += 1;
				break;

			case 'clicks':
				$mail->clicks += 1;
				break;

			case 'bounced':
				$mail->bounced += 1;
				break;

			case 'spam':
				$mail->spam += 1;
				break;

			case 'unsubscribed':
				$mail->unsubscribed += 1;
				break;

			case 'openingsxclick':
				$mail->uniqueOpens += 1;
				$mail->clicks += 1;
				break;
		}

		if (!$mail->save()) {
			foreach ($mail->getMessages() as $msg) {
				$this->log->log('Error : '. $msg);
			}
			return false;
		}
		return true;
	}
}
