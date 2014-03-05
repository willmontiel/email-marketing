<?php
class TrackingObject
{
	public $log;
	public $db;


	/**
	 * @var Mxc $mxc
	 */
	protected $mxc;
	protected $dirtyObjects;
	protected $contact;

	public function __construct()
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');

		$this->dirtyObjects = array();
	}

	/**
	 * Este metodo se utiliza para inicializar la identificacion unica de
	 * tracking. Esta identificacion se hace a traves de un ID de MAIL y un ID de CONTACTO
	 * @param int $idMail
	 * @param int $idContact
	 * @throws InvalidArgumentException cuando no se puede encontrar un registro MxC con esa combinacion
	 */
	public function setSendIdentification($idMail, $idContact)
	{
		$this->mxc = Mxc::findFirst(array(
			'conditions' => 'idMail = ?1 AND idContact = ?2',
			'bind' => array(1 => $idMail,
							2 => $idContact)
		));

		if (!$this->mxc) {
			throw new Exception("Couldn't find a matching email-contact pair for idMail={$idMail} and idContact={$idContact}");
		}
	}
	
	
	public function getMxC()
	{
		return $this->mxc;
	}
	/**
	 *
	 * @param UserAgentDetectorObj $userinfo
	 */
	public function trackOpenEvent(UserAgentDetectorObj $userinfo)
	{
		$time = time();// Tomar timestamp de ejecucion
		try {
			if ($this->canTrackOpenEvents()) { // Verificar que se puede hacer tracking de eventos open
				$this->log->log('Se puede realizar el tracking');
				$this->startTransaction();// Empezar transacción
				$this->log->log('Se ha iniciado una transacción');
				
				$this->mxc->opening = $time;// Actualizar marcador de apertura (timestamp)
				$this->addDirtyObject($this->mxc);//Se agregar el objeto mxc actualizado para su posterior grabación(esto se hace con todos los objetos)
				$this->log->log('Se ha ensuaciado Mxc');
				
				$mailObj = $this->findRelatedMailObject();//Se incrementa el atributo correspondiente llamando al siguiente método que está en Mail.php (Modelo)
				$mailObj->incrementUniqueOpens();//Se agregar el objeto Mail actualizado para su posterior grabación
//				$mailObj->uniqueOpens += 1;
				$this->log->log('Se ha incrementado el obj Mail');
				$this->addDirtyObject($mailObj);
				$this->log->log('Se ha ensuaciado Mxc');
				
				$statDbaseObj = $this->findRelatedDbaseStatObject();
				$statDbaseObj->incrementUniqueOpens();
//				$statDbaseObj->uniqueOpens += 1;
				$this->log->log('Se ha incrementado el obj Statdbase');
				$this->addDirtyObject($statDbaseObj);
				$this->log->log('Se ha ensuaciado el obj Statdbase');
				
				foreach ($this->findRelatedContactlistObjects() as $statListObj) {
					$statListObj->incrementUniqueOpens();
//					$statListObj->uniqueOpens += 1;
					$this->log->log('Se ha incrementado statlist');
					$this->addDirtyObject($statListObj);
					$this->log->log('Se ha ensuciado');
				}

				$this->log->log('Preparandose para iniciar guardo simultaneo');
				$this->flushChanges();//Se inicia proceso de grabado simultaneo de todos los objetos
			}
			$this->log->log('ya se contabilizó tracking');
		}
		catch (Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->rollbackTransaction();
		}
	}
	
	
	/**
	 * Este metodo determina si se puede hacer tracking o no de aperturas sobre
	 * el evento al cual hace referencia el objeto
	 * @return boolean se puede o no hacer tracking de aperturas
	 */
	protected function canTrackOpenEvents()
	{
		if ($this->mxc->opening == 0 &&
				  $this->mxc->bounced == 0 &&
				  $this->mxc->spam == 0 &&
				  $this->mxc->status == 'sent') {
			return true;
		}
		return false;
	}
	
	public function findRelatedMailObject()
	{
		$mailobject = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $this->mxc->idMail)
		));

		if (!$mailobject) {
			throw new Exception('Mail object not found!');
		}
		$this->log->log('Se encontró Mail');
		return $mailobject;
	}
	
	public function findRelatedDbaseStatObject()
	{
		$contact = Contact::findFirst(array(
			'conditions' => 'idContact = ?1',
			'bind' => array(1 => $this->mxc->idContact)
		));

		$statdbase = Statdbase::findFirst(array(
			'conditions' => 'idDbase = ?1 AND idMail = ?2',
			'bind' => array(1 => $contact->idDbase,
							2 => $this->mxc->idMail)
		));
		
		if (!$statdbase) {
			throw new Exception('Statdbase object not found!');
		}
		$this->log->log('Se encontró el obj Statdbase');
		return $statdbase;
	}
	
	public function findRelatedContactlistObjects()
	{
		$stats = array();
		$idContactlists = explode(",", $this->mxc->contactlists);
		foreach ($idContactlists as $idContactlist) {
			$statcontactlist = Statcontactlist::findFirst(array(
				'conditions' => 'idContactlist = ?1 AND idMail = ?2',
				'bind' => array(1 => $idContactlist,
								2 => $this->mxc->idMail)
			));
			if (!$statcontactlist) {
				throw new Exception('Statcontactlist object not found!');
			}
			$stats[] = $statcontactlist;
		}
		$this->log->log('Se encontrarón Statlist');
		return $stats;
	}
	
//	public function createNewMailEvent($cod = null)
//	{
//		$event = new Mailevent();
//		$event->idMail = $this->mxc->idMail;
//		$event->idContact = $this->mxc->idContact;
//		$event->idBouncedCode = $cod;
//		$this->log->log('Se ha creado Mailevent');
//		return $event;
//	}
	
	protected function addDirtyObject($object)
	{
		if (!in_array($object, $this->dirtyObjects)) {
			$this->dirtyObjects[] = $object;
			$this->log->log('Se agregó obj sucio');
		}
	}

	protected function flushChanges()
	{
		$i = 0;
		$this->log->log('Total dirty Obj: ' . count($this->dirtyObjects));
		foreach ($this->dirtyObjects as $object) {
			if (!$object->save()) {
				foreach ($object->getMessages() as $msg) {
					$this->log->log('Error saving Object: ' . $msg);
				}
				throw new Exception('Error while saving changes to objects!');
			}
			$this->log->log('Se guardó el objeto: ' . $i);
			$i++;
		}
		$this->log->log('Inicio de commit');
		$this->commitTransaction();
		$this->dirtyObjects = array();
		$this->log->log('Obj Dirty destruido');
	}
	
	/**
	 * =====================================================================================
	 * Inicio de tracking de Clicks
	 * =====================================================================================
	 */
	
	/**
	 *
	 * @param int $idLink
	 * @param UserAgentDetectorObj $userinfo
	 */
	public function trackClickEvent($idLink, UserAgentDetectorObj $userinfo)
	{
		$time = time();// Tomar timestamp de ejecucion
		try {
			$mxcxl = $this->getMxCxL($idLink);
			if (!$mxcxl || $mxcxl->click == 0) {
				$this->log->log('No se ha contabilizado');
				$this->startTransaction();
				
				$this->mxc->clicks += 1;
				
				$mailObj = $this->findRelatedMailObject();
				$mailObj->incrementClicks();
				
				$statDbaseObj = $this->findRelatedDbaseStatObject();
				$statDbaseObj->incrementClicks();

				list($mxl, $ml) = $this->locateRelatedLinkRecords($idLink);
				$mxl->incrementClicks();
				
				//Esta función valida si el usuario no hecho apertura, y si no es asi se marca apertura por click 
				if ($this->validateOpenForClick()) {
					$this->log->log('Apertura por click');
					$this->mxc->opening = $time;
					
					$mailObj->incrementUniqueOpens();
					$statDbaseObj->incrementUniqueOpens();
					
					foreach ($this->findRelatedContactlistObjects() as $statListObj) {
						$statListObj->incrementUniqueOpens();
						$statListObj->incrementClicks();
						$this->addDirtyObject($statListObj);
					}
				}
				else {
					$this->log->log('Contablización de click sin apertura');
					foreach ($this->findRelatedContactlistObjects() as $statListObj) {
						$statListObj->incrementClicks();
						$this->addDirtyObject($statListObj);
					}
				}
				$this->addDirtyObject($this->mxc);
				$this->addDirtyObject($mxl);
				$this->addDirtyObject($mailObj);
				$this->addDirtyObject($statDbaseObj);
				
				$mxcxl = $this->createNewMxcxl($idLink, $time);
				$this->addDirtyObject($mxcxl);
				
				$this->flushChanges();
				
				return $this->insertGoogleAnalyticsUrl($ml->link);
			}
			else {
				$this->log->log('Ya se contabilizó');
				return $this->getLinkToRedirect($idLink);
			}
		}
		catch (InvalidArgumentException $e) {
			$this->log->log('Exception: [' . $e . ']');
			$this->rollbackTransaction();
		}
	}
	
	
	public function getMxCxL($idLink)
	{
		$mxcxl = Mxcxl::findFirst(array(
			'conditions' => 'idMail = ?1 AND idMailLink = ?2 AND idContact = ?3',
			'bind' => array(1 => $this->mxc->idMail,
							2 => $idLink,
							3 => $this->mxc->idContact)
		));
		return $mxcxl;
	}
	
	private function validateOpenForClick()
	{
		if ($this->mxc->opening == 0 && $this->mxc->bounced == 0 && $this->mxc->spam == 0 && $this->mxc->status == 'sent') {
			return true;
		}
		else if ($this->mxc->opening !== 0 && $this->mxc->bounced == 0 && $this->mxc->spam == 0 && $this->mxc->status == 'sent') {
			return false;
		}
	}
	
	private function locateRelatedLinkRecords($idLink)
	{
		$records = array();

		$mxl = Mxl::findFirst(array(
			'conditions' => 'idMail = ?1 AND idMailLink = ?2',
			'bind' => array(1 => $this->mxc->idMail,
							2 => $idLink)
		));
		
		if (!$mxl) {
			throw new Exception('Mxl object not found!');
		}

		$records[] = $mxl;
		
		$ml = Maillink::findFirst(array(
			'conditions' => 'idMailLink = ?1',
			'bind' => array(1 => $idLink)
		));
		
		if (!$ml) {
			throw new Exception('Ml object not found!');
		}
		
		$records[] = $ml;
		
		return $records;
	}
	
	public function getLinkToRedirect($idLink)
	{
		$ml = Maillink::findFirst(array(
			'conditions' => 'idMailLink = ?1',
			'bind' => array(1 => $idLink)
		));
		
		if (!$ml) {
			throw new Exception('Ml object not found!');
		}
		
		return $this->insertGoogleAnalyticsUrl($ml->link);
	}
	
	
	public function createNewMxcxl($idLink, $date)
	{
		$mxcxl = new Mxcxl();
		$mxcxl->idMail = $this->mxc->idMail;
		$mxcxl->idMailLink = $idLink;
		$mxcxl->idContact = $this->mxc->idContact;
		$mxcxl->click = $date;
		$mxcxl->click_fb = 0;
		$mxcxl->click_tw = 0;
		$mxcxl->click_gp = 0;
		$mxcxl->click_li = 0;

		return $mxcxl;
	}
	/**
	 * Inicio tracking de bounced
	 * ==========================================================================================
	 */
	private function canTrackSoftBounceEvent()
	{
		if ($this->mxc->opening == 0 && 
				$this->mxc->bounced == 0 && 
				$this->mxc->spam == 0 && 
				$this->mxc->status == 'sent') {
			return true;
			$this->log->log('Validación aceptada');
		}
		$this->log->log('Validacion declinada');
		return false;
	}
	
	private function canTrackHardBounceEvent()
	{
		if ($this->mxc->opening == 0 && 
				$this->mxc->bounced > 0 && 
				$this->mxc->spam == 0 && 
				$this->mxc->status == 'sent') {
				$this->log->log('Validación aceptada');
			return true;
		}
		$this->log->log('Validación declinada');
		return false;
	}
	
	private function canTrackSpamEvent()
	{
		if ($this->mxc->bounced == 0 && 
				$this->mxc->spam == 0 && 
				$this->mxc->status == 'sent') {
			$this->log->log('Validación aceptada');
			return true;
		}
		$this->log->log('Validación declinada');
		return false;
	}
	
	
	public function trackSoftBounceEvent($cod, $date = null)
	{
		if ($date == null) {
			$date = time();
		}
		
		try {
			if ($this->canTrackSoftBounceEvent()) {
				$this->startTransaction();
				$this->mxc->idBouncedCode = $cod;
				$this->mxc->bounced = $date;
				$this->addDirtyObject($this->mxc);
				$this->log->log('Se agregó mxc');
				
				$mailObj = $this->findRelatedMailObject();
				$mailObj->incrementBounced();
				$this->addDirtyObject($mailObj);
				$this->log->log('Se agregó mail');
				
				$statDbaseObj = $this->findRelatedDbaseStatObject();
				$statDbaseObj->incrementBounced();
				$this->addDirtyObject($statDbaseObj);
				$this->log->log('Se agregó statDbase');
				
				foreach ($this->findRelatedContactlistObjects() as $statListObj) {
					$statListObj->incrementBounced();
					$this->addDirtyObject($statListObj);
					$this->log->log('Se agregó un statContactlist');
				}
				
				$this->log->log('Preparandose para guardar');
				$this->flushChanges();
				$this->log->log('Se guardó con exito');
			}
			$this->log->log('ya se contabilizó bounced tracking');
		}
		catch (Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->rollbackTransaction();
		}
	}

	public function trackHardBounceEvent($date = null)
	{
		if ($date == null) {
			$date = time();
		}
		
		$this->log->log('Inicio de tracking de rebote duro');
		if ($this->canTrackHardBounceEvent()) {
			$this->log->log('Es válido');
//			$this->startTransaction();
			$this->contact = $this->mxc->contact;
			if (!$this->contact) {
				$this->rollbackTransaction();
				throw new Exception('contact not found!');
			}
//			
//			$contact->bounced = $date;
//			$contact->email->bounced = $date;
////
//			$this->addDirtyObject($contact);
//			$this->addDirtyObject($contact->email);
			
			$sql = 'UPDATE email AS e JOIN contact AS c
						ON (c.idEmail = e.idEmail)
						SET e.bounced = ' . $date . ', c.bounced = ' . $date . '
					WHERE e.idEmail = ?';
			
			$db = Phalcon\DI::getDefault()->get('db');
			$update = $db->execute($sql, array($this->contact->idEmail));
//			
			if (!$update) {
				$this->rollbackTransaction();
				throw new Exception('Error while updating contact and email');
			}
			$this->log->log('Se marcó como rebotado email y contact');
			
			$this->log->log('Preparandose para actualizar contadores de bases de datos y listas de contactos');
			$this->updateCounters();
			
//			$this->flushChanges();
			$this->log->log('Se actualizó rebote duro');
		}
	}
	
	public function trackSpamEvent($cod, $date = null)
	{
		if ($date == null) {
			$date = time();
		}
		try {
			if ($this->canTrackSpamEvent()) {
				$this->startTransaction();
				$this->mxc->idBouncedCode = $cod;
				$this->mxc->spam = $date;
				
				$mailObj = $this->findRelatedMailObject();
				$mailObj->incrementSpam();
				
				$statDbaseObj = $this->findRelatedDbaseStatObject();
				$statDbaseObj->incrementSpam();
				
				$statListObjs = $this->findRelatedContactlistObjects();
				
				foreach ($statListObjs as $statListObj) {
					$statListObj->incrementSpam();
				}
				
				if ($this->canTrackOpenEvents()) {
					$this->mxc->opening = $date;// Actualizar marcador de apertura (timestamp)
					$mailObj->incrementUniqueOpens();//Se agregar el objeto Mail actualizado para su posterior grabación
					$statDbaseObj->incrementUniqueOpens();

					foreach ($statListObjs as $statListObj) {
						$statListObj->incrementUniqueOpens();
					}
				}
				
				$this->addDirtyObject($this->mxc);
				$this->log->log('Se agregó mxc');
				
				$this->addDirtyObject($mailObj);
				$this->log->log('Se agregó mail');
				
				$this->addDirtyObject($statDbaseObj);
				$this->log->log('Se agregó statDbase');
				
				foreach ($statListObjs as $statListObj) {
					$this->addDirtyObject($statListObj);
					$this->log->log('Se agregó un statContactlist');
				}
				
				$this->log->log('Preparandose para guardar');
				$this->flushChanges();
				$this->log->log('Se guardó con exito');
				
				$db = Phalcon\DI::getDefault()->get('db');
				
				$this->contact = $this->mxc->contact;
				
				if (!$this->contact) {
					throw new Exception('contact not found!');
				}	
				
				$sql = 'UPDATE email AS e JOIN contact AS c
						ON (c.idEmail = e.idEmail)
						SET e.spam = ' . $date . ', c.spam = ' . $date . ', c.unsubscribed = ' . $date . '
					WHERE e.idEmail = ?';
				
				$update = $db->execute($sql, array($this->contact->idEmail));
				
				if (!$update) {
					throw new Exception('Error while updating spam in contact and email');
				}

				$this->log->log('Se actualizó contact y email');
				$this->log->log('Preparandose para actualizar contadores');
				$this->updateCounters();
				$this->log->log('Se actualizó spam');
			}
		}
		catch (Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->rollbackTransaction();
		}
	}
	
	private function updateCounters()
	{
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1',
			'bind' => array(1 => $this->contact->idDbase)
		));
//			
		if (!$dbase) {
			throw new Exception('dbase not found!');
		}
		
		$dbase->updateCountersInDbase();
		$this->log->log('Se actualizarón contadores de Dbase');

		foreach ($this->findContactlistObjects() as $contactList) {
			$contactList->updateCountersInContactlist();
			$this->log->log('Se actualizarón contadores de Contactlist: ');
		}
	}
	
	private function findContactlistObjects()
	{
		$lists = array();
		$idContactlists = explode(",", $this->mxc->contactlists);
		foreach ($idContactlists as $idContactlist) {
			$contactlist = Contactlist::findFirst(array(
				'conditions' => 'idContactlist = ?1',
				'bind' => array(1 => $idContactlist)
			));
			
			if (!$contactlist) {
				throw new Exception('Contactlist object not found!');
			}
			$lists[] = $contactlist;
		}
		$this->log->log('Se encontrarón Contactlists');
		return $lists;
	}
	
	public function canTrackUnsubscribedEvents()
	{
		if ($this->mxc->unsubscribe == 0) {
			return true;
		}
		return false;
	}
	
	public function trackUnsubscribedEvent()
	{
		$date = time();
		try {
			$this->contact = $this->mxc->contact;
			if($this->contact->unsubscribed == 0) {
				$this->startTransaction();

				$this->contact->unsubscribed = $date;
				$this->addDirtyObject($this->contact);

				if ($this->canTrackUnsubscribedEvents()) {
					$this->log->log('Se puede realizar el tracking de des-suscripción');				
					$this->mxc->unsubscribe = $date;
					$this->addDirtyObject($this->mxc);

					$mailObj = $this->findRelatedMailObject();
					$mailObj->incrementUnsubscribed();
					$this->addDirtyObject($mailObj);
				}

				$statDbaseObj = $this->findRelatedDbaseStatObject();
				$statDbaseObj->incrementUnsubscribed();
				$this->addDirtyObject($statDbaseObj);

				foreach ($this->findRelatedContactlistObjects() as $statListObj) {
					$statListObj->incrementUnsubscribed();
					$this->addDirtyObject($statListObj);
				}

				$this->log->log('Preparandose para iniciar guardado simultaneo');
				$this->flushChanges();

				$this->log->log('Preparandose para actualizar contadores');
				$this->updateCounters();
			}
		}
		catch (Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			$this->rollbackTransaction();
		}
	}
	
	public function insertGoogleAnalyticsUrl($link)
	{
		$content = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $this->mxc->idMail)
		));

		if (!$content || trim($content->googleAnalytics) === '' || $content->googleAnalytics == null) {
			return $link;
		}
		$path = parse_url($link);

		if (in_array($path['scheme'] . '://' . $path['host'], json_decode($content->googleAnalytics))) {
			$googleAnalytics = Phalcon\DI::getDefault()->get('googleAnalytics');
			$this->log->log('Preparandose para insertar google analytics');

			$source = '?utm_source=' . urlencode($googleAnalytics->utm_source);
			$medium = '&utm_medium=' . urlencode($googleAnalytics->utm_medium);
			$campaign = '&utm_campaign=' . urlencode($content->campaignName);
			$newUrl = $link . $source . $medium . $campaign;

			$this->log->log('Url Analytics: ' . $newUrl);
			return $newUrl;
		}
		else {
			$this->log->log('Redirigiendo a: ' . $link);
			return $link;
		}
	}
	
	protected function startTransaction()
	{
		Phalcon\DI::getDefault()->get('db')->begin();
	}
	
	protected function commitTransaction()
	{
		Phalcon\DI::getDefault()->get('db')->commit();
	}
	
	protected function rollbackTransaction()
	{
		Phalcon\DI::getDefault()->get('db')->rollback();
	}
}
