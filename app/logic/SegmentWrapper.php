<?php
class SegmentWrapper extends BaseWrapper
{
	/**
	 * Funcion que valida los datos del segmento y luego inicia el guardado
	 * @param json $contents
	 * @param Account $account
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function createSegment($contents, Account $account)
	{
		$dbaseExist = Dbase::findFirst(array(
			"conditions" => "idDbase = ?1 and idAccount = ?2",
			"bind" => array(1 => $contents->dbase,
							2 => $account->idAccount)
		));
		if (!$dbaseExist) {
			throw new InvalidArgumentException('La base de datos no existe por favor verifique la información');
			throw new \Exception('Base de datos inexistente');
		}
		else {
			$segment = Segment::findFirst(array(
				"conditions" => "name = ?1 and idDbase = ?2",
				"bind" => array(1 => $contents->name,
								2 => $dbaseExist->idDbase)
			));
			if ($segment) {
				throw new InvalidArgumentException('Ya existe un segmento con el nombre enviado, por favor verifique la información');
				throw new \Exception('Existe un segmento con el nombre enviado');
			}
			else {
				$segment = $this->saveSegmentAndCriteriaInDb($contents);
				return self::convertSegmentToJson($segment);
			}
		}
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
		$segment->description = $contents->description;
		$segment->criterion = $contents->criterion;
		$segment->createdon = time();
		
		if ($segment->save()) {
			try {
			
				$typeFields = json_decode($contents->criteria, true);

				foreach ($typeFields as $typeField) {
					$criteria = new Criteria();
					
					$criteria->idSegment = $segment->idSegment;
					$criteria->value = $typeField["value"];
					$criteria->relation = $typeField["relations"];
					
					$this->findType($typeField["cfields"], $criteria);
					
					$criteria->fieldName = $typeField["cfields"];
					
					
					if (!$criteria->save()) {
						$txt = implode(PHP_EOL,  $criteria->getMessages());
						Phalcon\DI::getDefault()->get('logger')->log($txt);
						throw new ErrorException('Ha ocurrido un error');
					}
				}
				$this->saveSxC($segment);
			}
			catch (Exception $e) {
				throw $e;
			}
		}
		else {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new ErrorException('Ha ocurrido un error');
		}
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
	public function updateSegment($contents, $idSegment,  Account $account)
	{
		$dbase = Dbase::findFirst(array(
			"conditions" => "idDbase = ?1 AND idAccount = ?2",
			"bind" => array(1 => $contents->dbase,
							2 => $account->idAccount)
		));
		
		if (!$dbase) {
			throw new \Exception('La base de datos no existe');
		}
		else {
			$segment = Segment::findFirst(array(
				"conditions" => "idSegment = ?1 AND idDbase = ?2",
				"bind" => array(1 => $idSegment,
								2 => $contents->dbase)
			));
			
			if (!$segment) {
				throw new \Exception('El segmento no existe');
			}
			$response = $this->updateSegmentData($contents, $segment);
			
			$allSxC = Sxc::findByIdSegment($idSegment);

			$allSxC->delete();

			$this->saveSxC($segment);
			
		}
		
		return $response;
	}
	
	protected function updateSegmentData($contents, Segment $segment)
	{
		$segment->name = $contents->name;
		$segment->description = $contents->description;
		$segment->criterion = $contents->criterion;
		
		if (!$segment->save()) {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new ErrorException('Ha ocurrido un error');
		}
		
		$this->updateCriteria($contents, $segment->idSegment);
		
		$response = $this->convertSegmentToJson($segment);
		
		return $response;	
	}

	protected function updateCriteria($contents, $idSegment)
	{
		$arrayFields = json_decode($contents->criteria, true);
		
		$objCriterias = Criteria::find(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $idSegment)
		));
		foreach ($objCriterias as $objcr) {
			$done = FALSE;
			foreach ($arrayFields as $key => $value) {
				if (!array_key_exists('idCriteria', $value)) {
					
					$newobj = new Criteria();
					$newobj->idSegment = $idSegment;
					$newobj->relation = $value['relations'];
					$newobj->value = $value['value'];
					$newobj->fieldName = $value['cfields'];
					
					$this->findType($value["cfields"], $newobj);
					
					$newobj->save();
					
					unset($arrayFields[$key]);
					
					$done = TRUE;
				}
				else if (($value['idCriteria'] == $objcr->idCriteria)) {
					$objcr->relation = $value['relations'];
					$objcr->value = $value['value'];
					$objcr->fieldName = $value['cfields'];
					
					$this->findType($value["cfields"], $objcr);				
					
					$objcr->save();
					
					$done = TRUE;
				} 
				else if(!$done){
					$objcr->delete();
					
					$done = TRUE;
				}
			}
		}
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
					throw new InvalidArgumentException('Error: invalid type');
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
			throw new \InvalidArgumentException('Fallo la eliminacion del segmento');
		}
		
		$db->commit();

		return $deletedSegment;

	}
	
	public function findSegments() 
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$queryTxt ="SELECT s.idSegment, s.name, s.description, s.criterion, c.idCriteria, c.relation, c.value, s.idDbase, c.fieldName AS cfields
					FROM segment s JOIN criteria c ON s.idSegment = c.idSegment JOIN dbase d ON s.idDbase = d.idDbase
					WHERE d.idAccount = :idAccount:
					ORDER BY s.idSegment";
		
		$parameters = array('idAccount' => $this->account->idAccount);

		$query = $modelManager->createQuery($queryTxt);
        $segments = $query->execute($parameters);
		
		$result = array();
		$ids = array ();
		$criteria = array ();
		if ($segments) {
			$final = count($segments) - 1;
			$i = 0;
			foreach ($segments as $segment) {
				if ((!in_array($segment->idSegment, $ids)) && !empty($ids)) {
					$criteriaJson = json_encode($criteria);
					$segmentTosend = (isset($oldSegment))?$oldSegment:$segment;
					$result[] = $this->convertSegmentToJson($segmentTosend, $criteriaJson);
					$criteria = array ();
				}
				if ($i == $final) {
					$objectCrit = $this->createCriteria($segment);
					array_push($criteria, $objectCrit);
					$criteriaJson = json_encode($criteria);
					$result[] = $this->convertSegmentToJson($segment, $criteriaJson);
				}
				array_push($ids, $segment->idSegment);
				$objectCrit = $this->createCriteria($segment);
				array_push($criteria, $objectCrit);
				$oldSegment = $segment;
				$i++;
			}
		}
		return array('segments' => $result, 
					 'meta' => $this->pager->getPaginationObject()
					);
	}
	
	protected function createCriteria($segment)
	{
		$objectCrit = new stdClass();
		
		$objectCrit->idCriteria = $segment->idCriteria;
		$objectCrit->relations = $segment->relation;
		$objectCrit->cfields = $segment->cfields;
		$objectCrit->value = $segment->value;

		return $objectCrit;
	}
	
	protected function saveSxC(Segment $segment) {
		$allcriterias = Criteria::findByIdSegment($segment->idSegment);
		$join = "";
		$conditions = "";
		$firstCondition = TRUE;
		$alreadyTable = FALSE;
		$multTables = ($segment->criterion == 'all')?TRUE:FALSE;
		$tablenumber = 1;
		
		foreach ($allcriterias as $criteria) {
			switch ($criteria->type) {
				case 'custom' :
					if($multTables) {
						$join.= "JOIN fieldinstance f$tablenumber ON (c.idContact = f$tablenumber.idContact) ";
						$value = "f$tablenumber.idCustomField = $criteria->idCustomField AND f$tablenumber.textValue ";
						$tablenumber++;
					}
					else {
						if (!$alreadyTable) {
							$join.= "JOIN fieldinstance f ON (c.idContact = f.idContact) ";
							$alreadyTable = TRUE;
						}
						$value = "f.idCustomField = $criteria->idCustomField AND f.textValue ";
					}
					break;
				case 'contact' :
					$value = "c.$criteria->fieldName ";
					break;
				case 'email' :
					$join.="JOIN email e ON (c.idEmail = e.idEmail) ";
					$value = "e.$criteria->fieldName ";
					break;
				case 'domain' :
					$join.="JOIN email em ON (c.idEmail = em.idEmail) JOIN domain d ON (em.idDomain = d.idDomain) ";
					$value = "d.name ";
					break;
			}
			
			switch ($criteria->relation) {
				case 'begins' :
					$relation = "LIKE '$criteria->value%'";
					break;
				case 'ends' :
					$relation = "LIKE '%$criteria->value'";
					break;
				case 'content' :
					$relation = "LIKE '%$criteria->value%'";
					break;
				case '!content' :
					$relation = "NOT LIKE '%$criteria->value%'";
					break;
				case 'greater' :
					$relation = "> ". $criteria->value;
					break;
				case 'less' :
					$relation = "< ". $criteria->value;
					break;
				case 'equals' :
					$relation = "= '$criteria->value'";
					break;
			}
			
			if($firstCondition) {
				$conditions.= $value . $relation;
				$firstCondition = FALSE;
			} 
			else {
				if($segment->criterion == 'any') {
					$conditions.= " OR " . $value . $relation;
				} 
				else {
					$conditions.= " AND " . $value . $relation;
				}
			}
		}
		
		$SQL = "INSERT INTO sxc (idContact, idSegment) SELECT DISTINCT c.idContact, $segment->idSegment FROM contact c " . $join . " WHERE c.idDbase = $segment->idDbase AND " . $conditions;
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
	
	public function findContactsInSegment(Segment $segment)
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$findQuery = "SELECT Contact.*, Email.* 
					FROM Contact JOIN Email JOIN Sxc 
					WHERE idSegment = :idsegment:";
		
		$query = $modelManager->createQuery($findQuery);
		
		$parameters['idsegment'] = $segment->idSegment;
		
		$contacts = $modelManager->executeQuery($findQuery, $parameters);
		
		$result = array();
		
		$cwrapper = new ContactWrapper();
		
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
				$meets = (stripos($field, $value) !== 0)?true:false;
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
	
}
