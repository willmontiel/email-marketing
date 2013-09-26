<?php
class SegmentWrapper extends BaseWrapper
{
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
		$segment = new Segment();
		
		$segment->name = $contents->name;
		$segment->description = $contents->description;
		$segment->idDbase = $contents->dbase;
		$segment->createon = time();
		
		if (!$segment->save()) {
			$txt = implode(PHP_EOL,  $segment->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new InvalidArgumentException('Ha ocurrido un error');
			throw new Exception('Error al crear el segmento!');
		}
		
		return $segment;
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
