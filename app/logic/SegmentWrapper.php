<?php
class SegmentWrapper extends BaseWrapper
{
	public $segment;
	public $dbase;
	
	public function setSegment(Segment $segment)
	{
		$this->segment = $segment;
	}
	
	public function setDbase(Dbase $dbase)
	{
		$this->dbase = $dbase;
	}
	
	/**
	 * Funcion que valida los datos del segmento y luego inicia el guardado
	 * @param json $contents
	 * @param Account $account
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function createSegment($contents)
	{
		if(!isset($contents->name) || trim($contents->name) == '' || $contents->name == null) {
			$this->addFieldError('segmentname', 'El segmento debe tener nombre');
			throw new \InvalidArgumentException('El segmento debe tener nombre');
		}
		
		$segment = $this->saveSegmentAndCriteriaInDb($contents);
		return self::convertSegmentToJson($segment);
	}
	/**
	 * Esta funcion agrega los datos del segmento, nombre, descripción, etc, y los datos del criterio, en cada tabla respectivamente
	 * @param Json $contents
	 */
	public function saveSegmentAndCriteriaInDb($contents) 
	{
		$segment = new Segment();
		
		$segment->idDbase = $contents->dbase;
		$segment->name = $contents->name;
		$segment->description = ($contents->description)?$contents->description:"Sin Descripcion";
		$segment->criterion = $contents->criterion;
		$segment->createdon = time();
		
		$typeFields = json_decode($contents->criteria, true);
		$c = array();
		
		foreach ($typeFields as $typeField) {
			
			if(!isset($typeField["value"]) || trim($typeField["value"]) == '' || $typeField["value"] == null) {
				$this->addFieldError('segment', 'La condicion debe tener un valor de comparacion');
				throw new \InvalidArgumentException('La condicion debe tener un valor de comparacion');
			}
			
			$criteria = new Criteria();

			$this->findType($typeField["cfields"], $criteria);
			$criteria->value = $typeField["value"];
			$criteria->relation = $typeField["relations"];
			$criteria->fieldName = $typeField["cfields"];
			
			$c[] = $criteria;
		}
		
		$segment->criteria = $c;
		
		if ( !$segment->save() ) {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new ErrorException('Ha ocurrido un error');
		}
		$this->saveSxC($segment);
		return $segment;
	}
	 /**
	  * Funcion que convierte datos a Json para enviarlos a Ember
	  * @param Segment $segment
	  */
	public static function convertSegmentToJson($segment, $criteria = null)
	{
		$object = array();
		
		$object['id'] = intval($segment->idSegment);
		$object['name'] = $segment->name;
		$object['description'] = $segment->description;
		$object['criterion'] = $segment->criterion;
		$object['criteria'] = $criteria;
		$object['dbase'] = $segment->idDbase;
		
		return $object;
	}
	
	/**
	 * Function que empieza el proceso de actualización de un segmento (validaciones, etc)
	 * @param type $contents
	 * @param Account $account
	 */
	public function updateSegment($contents)
	{
		if(!isset($contents->name) || trim($contents->name) == '' || $contents->name == null) {
			$this->addFieldError('segmentname', 'El segmento debe tener nombre');
			throw new \InvalidArgumentException('El segmento debe tener nombre');
		}
		
		$sxc = Sxc::findByIdSegment($this->segment->idSegment);
		$sxc->delete();
		$this->saveSxC($this->segment, $contents);
		
		$response = $this->updateSegmentData($contents);
		
		return $response;
	}
	
	protected function updateSegmentData($contents)
	{
		$this->segment->name = $contents->name;
		$this->segment->description = $contents->description;
		$this->segment->criterion = $contents->criterion;
		
		$criteria = $this->updateCriteria($contents);
		
		$this->segment->criteria = $criteria;
		
		if (!$this->segment->save()) {
			$txt = implode(PHP_EOL,  $this->segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new ErrorException('Ha ocurrido un error');
		}
		
		$response = $this->convertSegmentToJson($this->segment);
		
		return $response;	
	}

	protected function updateCriteria($contents)
	{
		$arrayFields = json_decode($contents->criteria, true);
		$c = array();
		
		$objCriterias = Criteria::find(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $this->segment->idSegment)
		));
		foreach ($objCriterias as $objcr) {
			$done = FALSE;
			foreach ($arrayFields as $key => $value) {
				if (!array_key_exists('idCriteria', $value)) {
					
					$newobj = new Criteria();

					$newobj->relation = $value['relations'];
					$newobj->value = $value['value'];
					$newobj->fieldName = $value['cfields'];
					
					$this->findType($value["cfields"], $newobj);
					
					$c[] = $newobj;
					
					unset($arrayFields[$key]);
					
					$done = TRUE;
				}
				else if (($value['idCriteria'] == $objcr->idCriteria)) {
					$objcr->relation = $value['relations'];
					$objcr->value = $value['value'];
					$objcr->fieldName = $value['cfields'];
					
					$this->findType($value["cfields"], $objcr);				
					
					$c[] = $objcr;
					
					$done = TRUE;
				} 
				else if(!$done){
					$objcr->delete();
					
					$done = TRUE;
				}
			}
		}
		
		return $c;
	}
	
	protected function findType($value, $objcr)
	{
		switch ($value) {
			case 'name':
			case 'lastName':
				$type = 'contact';
				break;
			case 'email':
				$type = 'email';
				break;
			case 'domain':
				$type = 'domain';
				break;
			default:
				if (substr($value, 0, 3) == 'cf_') {
					$type = 'custom';
					$objcr->idCustomField = substr($value, 3);
				}
				else {
					$this->addFieldError('segment', 'Debe seleccionar una campo por cada condición');
					throw new \InvalidArgumentException('Debe seleccionar una campo por cada condición');
				}
				break;
		}
		$objcr->type = $type;
	}

	public function deleteSegment(Account $account, $idSegment)
	{
		$db = Phalcon\DI::getDefault()->get('db');

		$query = "	DELETE s
					FROM segment s 
					WHERE s.idSegment = ?";
		
		$db->begin();
		Phalcon\DI::getDefault()->get('logger')->log($query);
		$deletedSegment = $db->execute($query, array($idSegment));
		
		if (!$deletedSegment) {
			$db->rollback();
			$this->addFieldError('segment', 'Falló la eliminacion del segmento');
			throw new \InvalidArgumentException('Falló la eliminacion del segmento');
		}
		
		$db->commit();

		return $deletedSegment;

	}
	
	public function findSegments($idDbase = 0) 
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$number = $this->pager->getRowsPerPage();
		
		$this->pager->setTotalRecords(Segment::countSegmentsInAccount($this->account));
		
		$queryTxt ="SELECT s.*
					FROM Segment s JOIN Dbase d ON s.idDbase = d.idDbase
					WHERE d.idAccount = :idaccount:";
		
		if($idDbase != 0) {
			$queryTxt.=" AND d.idDbase = :iddbase:";
			$parameters = array('idaccount' => $this->account->idAccount, 'iddbase' => $idDbase);
		}
		else {
			$parameters = array('idaccount' => $this->account->idAccount);
		}
		Phalcon\DI::getDefault()->get('logger')->log($idDbase);
		Phalcon\DI::getDefault()->get('logger')->log($queryTxt);
		if (isset($number) ) {
			$offset = $this->pager->getStartIndex();
			$start = isset($offset)?$offset:0;
			$queryTxt .= ' LIMIT ' . $number . ' OFFSET ' . $start;
		}
		

		$query = $modelManager->createQuery($queryTxt);
		$segments = $query->execute($parameters);
		
		$result = array();
		$criteria = array ();
		
		if ($segments) {
			
			foreach ($segments as $segment) {
				
				$criterias = Criteria::findByIdSegment($segment->idSegment);
				
				foreach ($criterias as $cr) {
					
					$cleanCr = new stdClass();
					
					$cleanCr->idCriteria = $cr->idCriteria;
					$cleanCr->relations = $cr->relation;
					$cleanCr->cfields = $cr->fieldName;
					$cleanCr->value = $cr->value;
					
					array_push($criteria, $cleanCr);
				}
				
				$criteriaJson = json_encode($criteria);
				
				$result[] = $this->convertSegmentToJson($segment, $criteriaJson);
				
				$criteria = array ();
			}
		}
		
		$this->pager->setRowsInCurrentPage(count($result));

		return array('segments' => $result,
					 'dbase' => DbaseWrapper::getDbasesAsJSON($this->account),
					 'meta' => $this->pager->getPaginationObject()
					);
	}
	
	protected function saveSxC(Segment $segment, $contents = null)
	{
		if ($contents != null) {
			$crit = $contents->criterion;
		}
		else {
			$crit = $segment->criterion;
		}
		
		
		$allcriterias = Criteria::findByIdSegment($segment->idSegment);
		$join = "";
		$conditions = "";
		$firstCondition = TRUE;
		$alreadyTable = FALSE;
		$multTables = ($segment->criterion == 'all')?TRUE:FALSE;
		$tablenumber = 1;
		
		$types = array();
		
		if ($allcriterias) {
			foreach ($allcriterias as $criteria) {
				switch ($criteria->type) {
					case 'custom' :
						if($multTables) {
							$join.= "JOIN fieldinstance f$tablenumber ON (c.idContact = f$tablenumber.idContact) ";
							$value = "( f$tablenumber.idCustomField = $criteria->idCustomField AND f$tablenumber.textValue ";
							$tablenumber++;
						}
						else {
							if (!$alreadyTable) {
								$join.= "JOIN fieldinstance f ON (c.idContact = f.idContact) ";
								$alreadyTable = TRUE;
							}
							$value = "( f.idCustomField = $criteria->idCustomField AND f.textValue ";
						}
						$types[] = 'custom';
						break;
						
					case 'contact' :
						$value = "( c.$criteria->fieldName ";
						$types[] = 'contact';
						break;
					
					case 'email' :
						if (!in_array('email', $types)) {
							$join.="JOIN email e ON (c.idEmail = e.idEmail) ";
							$types[] = 'email';
						}
						$value = "( e.$criteria->fieldName ";
						break;
						
					case 'domain' :
						if (!in_array('domain', $types)) {
							$join.="JOIN email em ON (c.idEmail = em.idEmail) JOIN domain d ON (em.idDomain = d.idDomain) ";
							$types[] = 'domain';
						}
						$value = "( d.name ";
						break;
				}

				switch ($criteria->relation) {
					case 'begins' :
						$relation = "LIKE '$criteria->value%' )";
						break;
					case 'ends' :
						$relation = "LIKE '%$criteria->value' )";
						break;
					case 'content' :
						$relation = "LIKE '%$criteria->value%' )";
						break;
					case '!content' :
						$relation = "NOT LIKE '%$criteria->value%' )";
						break;
					case 'greater' :
						$relation = "> ". $criteria->value . " )";
						break;
					case 'less' :
						$relation = "< ". $criteria->value . " )";
						break;
					case 'equals' :
						$relation = "= '$criteria->value' )";
						break;
				}

				if($firstCondition) {
					$conditions.= $value . $relation;
					$firstCondition = FALSE;
				} 
				else {
					if($crit == 'any') {
						$conditions.= " OR " . $value . $relation;
					} 
					else {
						$conditions.= " AND " . $value . $relation;
					}
				}
			}

			$SQL = "INSERT INTO sxc (idContact, idSegment) SELECT DISTINCT c.idContact, $segment->idSegment FROM contact c " . $join . " WHERE c.idDbase = $segment->idDbase AND ( " . $conditions . " )";
			Phalcon\DI::getDefault()->get('logger')->log($SQL);
			$db = Phalcon\DI::getDefault()->get('db');

			$db->begin();

			$result = $db->execute($SQL);

			if(!$result) {
				$db->rollback();
				throw new \InvalidArgumentException('Error al crear la asociacion del segmento y los contactos');
			}

			$db->commit();
		
		}
	}
	
	protected function deleteSxC(Segment $segment)
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$query = "DELETE FROM Sxc WHERE idSegment = :idsegment:";
		
		$parameters['idsegment'] = $segment->idSegment;
		
		$modelManager->executeQuery($query, $parameters);
		
	}


	public function findContactsInSegment(Segment $segment)
	{
		
		$this->pager->setTotalRecords(Contact::countContactsInSegment($segment));
				
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$findQuery = "SELECT Contact.*, Email.* 
					FROM Contact JOIN Email JOIN Sxc 
					WHERE idSegment = :idsegment:";
		
		$number = $this->pager->getRowsPerPage();
		
		if (isset($number) ) {
			$offset = $this->pager->getStartIndex();
			$start = isset($offset)?$offset:0;
			$findQuery .= ' LIMIT ' . $number . ' OFFSET ' . $start;
		}
		
		$parameters['idsegment'] = $segment->idSegment;
		
		$contacts = $modelManager->executeQuery($findQuery, $parameters);
		
		$result = array();
		
		$cwrapper = new ContactWrapper();
		$mailhistory = new \EmailMarketing\General\ModelAccess\ContactMailHistory();
		$cwrapper->setContactMailHistory($mailhistory);
		
		$ids = array();
		foreach ($contacts as $c) {
			$ids[] = $c->contact->idContact;
		}
		if(!empty($ids)) {
			
			// Consultar la lista de campos personalizados para esos contactos
			$finstancesO = Fieldinstance::findInstancesForMultipleContacts($ids);

			// Consultar lista de campos personalizados de la base de datos
			$cfieldsO = Customfield::findCustomfieldsForDbase($segment->dbase);


			// Convertir la lista de campos personalizados y de instancias a arreglos
			$cfields = array();
			foreach ($cfieldsO as $cf) {
				$cfields[$cf->idCustomField] = array('id' => $cf->idCustomField, 'type' => $cf->type, 'name' => 'campo' . $cf->idCustomField);
			}
			unset($cfieldsO);

			$finstances = $cwrapper->createFieldInstanceMap($finstancesO);

			foreach ($contacts as $contact) {
				$result[] = $cwrapper->convertCompleteContactToJson($contact, $cfields, $finstances);
			}
			
		}
		$this->pager->setRowsInCurrentPage(count($result));
		return array('contacts' => $result, 'meta' => $this->pager->getPaginationObject() );
		
	}
	
	public function contactCreatedOrUpdated(Contact $contact)
	{
//		Phalcon\DI::getDefault()->get('logger')->log("");

		$db = Phalcon\DI::getDefault()->get('db');
		
		$segments = Segment::findByIdDbase($contact->idDbase);

		foreach ( $segments as $segment ) {
			
			if ( $segment->criterion == 'any' ) { 
				$belongs = false;
			}
			else {
				$belongs = true;
			}
			
			foreach ( $segment->criteria as $criteria ) {
							
				switch ($criteria->type) {	
					case 'contact' :
						$value = $criteria->fieldName;
						$contact->$value;
						$field = $contact->$value;
						break;
					case 'email' :
						$field = $contact->email->email;
						break;
					case 'domain' :
						$field = $contact->email->domain->name;
						break;
					case 'custom' :
						$fd = Fieldinstance::findFirst(array(
														"conditions" => ("idCustomField = ?1 AND idContact = ?2"),
														"bind" => array (1 => $criteria->idCustomField,
																		 2 => $contact->idContact)
														));
						$field = $fd->textValue;
						break;
				}
					
				$meets = $this->compareCriteria($field, $criteria->relation, $criteria->value);
				
				if ( $segment->criterion == 'any' ) { 
					if ($meets) {
						$belongs = true;
					}
				}
				else {
					if(!$meets) {
						$belongs = false;
					} 
				}
			}
			if ($belongs) {
				$sql = "INSERT IGNORE INTO sxc (idSegment, idContact) VALUES ($segment->idSegment, $contact->idContact)";
				Phalcon\DI::getDefault()->get('logger')->log($sql);
				$db->execute($sql);
			} 
			else {
				$exist = Sxc::findFirst(array(
										"conditions" => ("idSegment = ?1 AND idContact = ?2"),
										"bind" => array (1 => $segment->idSegment,
														 2 => $contact->idContact)
										));
				if($exist) {
					$exist->delete();
				}
			}
		}
	}
	
	protected function compareCriteria($field, $operator, $value)
	{
		switch ($operator) {
			case 'begins' :
				$meets = (stripos($field, $value) === 0)?true:false;
				break;
			case 'ends' :
				$meets = (substr($field, -strlen($value)) === $value)?true:false;
				break;
			case 'content' :
				$meets = (stripos($field, $value))?true:false;
				break;
			case '!content' :
				$meets = (stripos($field, $value))?false:true;
				break;
			case 'greater' :
				$meets = ($field > $value)?true:false;
				break;
			case 'less' :
				$meets = ($field < $value)?true:false;
				break;
			case 'equals' :
				$meets = ($field == $value)?true:false;
				break;
		}
		
		return $meets;
	}
	
	public function recreateSegmentsInDbase($idDbase)
	{
		$segments = Segment::findByIdDbase($idDbase);
		
		foreach ($segments as $segment) {
			
			$this->deleteSxC($segment);
			
			$this->saveSxC($segment);	
			
		}
		
	}
	
}
