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
	public function startCreatingSegmentProcess($contents, Account $account)
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
				return array('segment' => self::convertSegmentToJson($segment));
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
					$criteria->value = $typeField{"value"};
//					$criteria->relation = $typeField{"relations"};
					
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
	}
	 /**
	  * Funcion que convierte datos a Json para enviarlos a Ember
	  * @param Segment $segment
	  */
	public static function convertSegmentToJson($segment, $criteria)
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

	public function startDeletingSegmentProcess(Account $account, $idSegment)
	{
		$idDbase = Dbase::findByIdAccount($account->idAccount);
		$segment = Segment::findFirst(array(
				"conditions" => "idSegment = ?1 AND idDbase = ?2",
				"bind" => array(
					1 => $idSegment,
					2 => $idDbase
				)
			)
		);
		
		if (!$segment ) {
			throw new \Exception('El segmento no existe');
		}
		$deletedSegment = $this->deleteSegment($segment);
	}
	
	public function deleteSegment($segment)
	{
		
	}
	
	public function findSegments() 
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$queryTxt ="SELECT s.idSegment, s.name, s.description, s.criterion, c.relation, c.value, s.idDbase,
						IF (c.idCustomField IS NULL, c.internalValue, c.idCustomField) AS cfields
					FROM segment s JOIN criteria c ON s.idSegment = c.idSegment JOIN dbase d ON s.idDbase = d.idDbase
					WHERE d.idAccount = :idAccount:";
		
		$parameters = array('idAccount' => $this->account->idAccount);

		$query2 = $modelManager->createQuery($queryTxt);
        $segments = $query2->execute($parameters);
		
		$ids = array ();
		$criteria = array ();
		if ($segments) {
			$final = count($segments) - 1;
			$i = 0;
			foreach ($segments as $segment) {
				if ($i == $final) {
					$objectCrit = $this->createCriteria($segment);
					array_push($criteria, $objectCrit);
				}
				if ((!in_array($segment->idSegment, $ids) || $i == $final) && !empty($ids)) {
					$criteriaJson = json_encode($criteria);
					$result[] = $this->convertSegmentToJson($oldSegment, $criteriaJson);
					$criteria = array ();
				}
				array_push($ids, $segment->idSegment);
					
				$objectCrit = $this->createCriteria($segment);
				array_push($criteria, $objectCrit);
				$oldSegment = $segment;
				$i++;
			}
		}
		
		return $result;
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
