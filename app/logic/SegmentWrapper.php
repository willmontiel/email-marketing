<?php
class SegmentWrapper extends BaseWrapper
{
	/**
	 * Esta funcion valida si un campo enviado desde un segmento es propio o personalizado
	 * @param Json $contents
	 */
	protected function validateTypeField($contents)
	{
		$arrayTypeFields = array('email','name', 'lastname');
		$typeFields[] = $contents->criteria;
		for ($i = 0; $i < count($typeFields); $i++) {
			if(in_array($typeFields[$i]['cfields'], $arrayTypeFields)) {
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
	public function saveSegmentAndCriteriaInDb ($contents) 
	{
//		$this->validateTypeField($contents);
		
		$segment = new Segment();
		
		$segment->idDbase = $contents->dbase;
		$segment->name = $contents->name;
		$segment->description = $contents->description;
		$segment->criterion = $contents->criterion;
		$segment->createdon = time();
		
		if (!$segment->save()) {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new InvalidArgumentException('Ha ocurrido un error');
			throw new Exception('Error al crear el segmento!');
		}
		else {
			$criteria = new Criteria();

			$typeFields = json_decode($contents->criteria, true);
			Phalcon\DI::getDefault()->get('logger')->log($typeFields[0]. " este es el array");
			foreach ($typeFields as $typeField) {
				if ($typeField{"cfields"} == 'email' || $typeField{"cfields"} == 'name' || $typeField{"cfields"} == 'lastName') {
					Phalcon\DI::getDefault()->get('logger')->log($typeField{"cfields"}. " este es el campo");
					Phalcon\DI::getDefault()->get('logger')->log($typeField{"value"}. " este es el valor");
					Phalcon\DI::getDefault()->get('logger')->log($typeField{"relations"}. " esta es la relación");
					$criteria->idSegment = $segment->idSegment;
					$criteria->type = 4;
					$criteria->value = $typeField{"value"};
					$criteria->relation = $typeField{"relations"};
					$criteria->fieldName = $typeField{"cfields"};
					Phalcon\DI::getDefault()->get('logger')->log("la relación es: ". $typeField{"relations"});
				}
				else {
					Phalcon\DI::getDefault()->get('logger')->log($typeField{"cfields"}. " este es el campo");
					Phalcon\DI::getDefault()->get('logger')->log($typeField{"value"}. " este es el valor");
					Phalcon\DI::getDefault()->get('logger')->log($typeField{"relations"}. " esta es la relación");
					//$customField = explode("_", $typeFields{"cfields"});
					$customField = preg_split("_", $typeFields{"cfields"});
					Phalcon\DI::getDefault()->get('logger')->log($customField[0]. " este es el id");
					$criteria->idSegment = $segment->idSegment;
					$criteria->type = 3;
					$criteria->relation = $typeFields{"relations"};
					$criteria->value = $typeFields{"value"};
					$criteria->idCustomField = $customField[1];
				}
				if (!$criteria->save()) {
					$txt = implode(PHP_EOL,  $criteria->getMessages());
					Phalcon\DI::getDefault()->get('logger')->log($txt);
					throw new InvalidArgumentException('shit! Ha ocurrido un error');
					throw new Exception('Error al crear el criterio!');
				}
			}
			return $segment;
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
