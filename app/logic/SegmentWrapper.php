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
		$db = Phalcon\DI::getDefault()->get('db');
		$db->begin();
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
					
					switch ($typeField["cfields"]) {
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
							if (substr($typeField["cfields"], 0, 3) == 'cf_') {
								$type = 'custom';
								$criteria->idCustomField = substr($typeField["cfields"], 3);
							}
							else {
								throw new InvalidArgumentException('Error: invalid type');
							}
							break;
					}
					$criteria->type = $type;
					$criteria->fieldName = $typeField["cfields"];
					
					
					if (!$criteria->save()) {
						$txt = implode(PHP_EOL,  $criteria->getMessages());
						Phalcon\DI::getDefault()->get('logger')->log($txt);
						throw new ErrorException('Ha ocurrido un error');
					}
				}
				$db->commit();
			}
			catch (Exception $e) {
				$db->rollback();
				throw $e;
			}
		}
		else {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			$db->rollback();
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
			$this->updateSegmentData($contents, $segment);
		}
	}
	
	public function updateSegmentData($contents, Segment $segment)
	{
		$segment->name = $contents->name;
		$segment->description = $contents->description;
		
		if (!$segment->save()) {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new ErrorException('Ha ocurrido un error');
		}
		
		$objCriteria = Criteria::findFirst(array(
			"conditions" => "idSegment = ?1",
			"bind" => array(1 => $segment->idSegment)
		));
		
		$arrayFields = json_decode($contents->criteria, true);
		
		for ($i = 0; $i < count($objCriteria); $i++) {
			
			$criteria->value = $typeField["value"];
			$criteria->relation = $typeField["relations"];

			switch ($typeField["cfields"]) {
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
					if (substr($typeField["cfields"], 0, 3) == 'cf_') {
						$type = 'custom';
						$criteria->idCustomField = substr($typeField["cfields"], 3);
					}
					else {
						throw new InvalidArgumentException('Error: invalid type');
					}
					break;
			}
			$criteria->type = $type;
			$criteria->fieldName = $typeField["cfields"];


			if (!$criteria->save()) {
				$txt = implode(PHP_EOL,  $criteria->getMessages());
				Phalcon\DI::getDefault()->get('logger')->log($txt);
				throw new ErrorException('Ha ocurrido un error');
			}
		}
		return $segment;
//		$criteria = Criteria::find()
	}


	public function deleteSegment(Account $account, $idSegment)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		Phalcon\DI::getDefault()->get('logger')->log("POR ACA");

		Phalcon\DI::getDefault()->get('logger')->log("POR AQUI");
		$query = "	DELETE c, s 
					FROM segment s 
						JOIN criteria c ON s.idSegment = c.idSegment 
					WHERE s.idSegment = ?";
		
		$db->begin();
		
		$deletedSegment = $db->execute($query, array($idSegment));
		
		if (!$deletedSegment) {
			$db->rollback();
			throw new \InvalidArgumentException('Fallo la eliminacion del segmento');
		}
		
		$db->commit();
		Phalcon\DI::getDefault()->get('logger')->log("Elimino");
		return $deletedSegment;

	}
	
	public function findSegments() 
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$queryTxt ="SELECT s.idSegment, s.name, s.description, s.criterion, c.relation, c.value, s.idDbase, c.fieldName AS cfields
					FROM segment s JOIN criteria c ON s.idSegment = c.idSegment JOIN dbase d ON s.idDbase = d.idDbase
					WHERE d.idAccount = :idAccount:";
		
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
		$objectCrit->relations = $segment->relation;
		$objectCrit->cfields = $segment->cfields;
		$objectCrit->value = $segment->value;

		return $objectCrit;
	}
	
}
