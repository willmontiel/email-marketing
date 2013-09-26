<?php
class SegmentWrapper extends BaseWrapper
{
	/**
	 * Funcion encarga de validar el tipo de campo (propio o personalizado) para guardarlo en criteria
	 * @param json $contents
	 */
	protected function saveCriteria($contents)
	{
		$criteria1 = new Criteria();
		$criteria = new Criteria();
		
		$typeFields = json_decode($contents->criteria, true);
			
		foreach ($typeFields as $typeField) {
			if ($typeField{"cfields"} == 'email' || $typeField{"cfields"} == 'name' || $typeField{"cfields"} == 'lastName') {
				Phalcon\DI::getDefault()->get('logger')->log($typeField{"cfields"}. " este es el campo");
				Phalcon\DI::getDefault()->get('logger')->log($typeField{"value"}. " este es el valor");
				Phalcon\DI::getDefault()->get('logger')->log($typeField{"relations"}. " esta es la relación");
				$criteria1->idSegment = $segment->idSegment;
				$criteria1->type = 4;
				$criteria1->value = $typeField{"value"};
				$criteria1->relation = $typeField{"relations"};
				$criteria1->fieldName = $typeField{"cfields"};
				Phalcon\DI::getDefault()->get('logger')->log("la relación es: ". $typeField{"relations"});
				if (!$criteria1->save()) {
					$txt = implode(PHP_EOL,  $criteria->getMessages());
					Phalcon\DI::getDefault()->get('logger')->log($txt);
					throw new InvalidArgumentException('shit! Ha ocurrido un error');
					throw new Exception('Error al crear el criterio!');
				}
				Phalcon\DI::getDefault()->get('logger')->log("Se guardo un campo propio");
			}
			else {
				Phalcon\DI::getDefault()->get('logger')->log($typeField{"cfields"}. " este es el campo");
				Phalcon\DI::getDefault()->get('logger')->log($typeField{"value"}. " este es el valor");
				Phalcon\DI::getDefault()->get('logger')->log($typeField{"relations"}. " esta es la relación");
				
				$customField = $typeField{"cfields"};
				$idCustomField = preg_split("/_/", $customField);
				Phalcon\DI::getDefault()->get('logger')->log($idCustomField[1]. " este es el id");
				$criteria->idSegment = $segment->idSegment;
				$criteria->type = 3;
				$criteria->relation = $typeField{"relations"};
				$criteria->value = $typeField{"value"};
				$criteria->idCustomField = $idCustomField[1];
				if (!$criteria->save()) {
					$txt = implode(PHP_EOL,  $criteria->getMessages());
					Phalcon\DI::getDefault()->get('logger')->log($txt);
					throw new InvalidArgumentException('shit! Ha ocurrido un error');
					throw new Exception('Error al crear el criterio!');
				}
				Phalcon\DI::getDefault()->get('logger')->log("Se guardo un campo personalizado");
			}
		}
	}
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
	public static function convertSegmentToJson($segment)
	{
		$object = array();
		
		$object['id'] = intval($segment->idSegment);
		$object['name'] = $segment->name;
		$object['description'] = $segment->description;
		
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
	
}
