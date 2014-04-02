<?php
class DbaseWrapper extends BaseWrapper
{
	public static function getDbasesAsJSON(Account $account)
	{
		$bdjson = array();
		foreach ($account->dbases as $bd) {
			$bdjson[] = self::convertBDToJson($bd);
		}	
		return $bdjson;
	}
	
	protected function convertBDToJson($dbase)
	{
		$object = array();
		$object['id'] = $dbase->idDbase;
		$object['name'] = $dbase->name;
		$object['color'] = $dbase->color;
		return $object;
	}
	
	public function getColors()
	{
		$colors = array(
			0 => array('EBF7FE', 'E3F4FC', 'FFF8E4', 'FFEAE4', 'E6E8FC', 'F1F3FE'),
			1 => array('EEFEF0', 'E8FCEA', 'FFF5EB', 'FCF2FD', 'E7F6FA', 'FDFFF0'),
			2 => array('00FFFF', '00BFFF', '0080FF', '0040FF', '0000FF', '4000FF'),
			3 => array('8000FF', 'BF00FF', 'FF00FF', 'FF00BF', 'FF0080', 'FF0040')
		);
		
		return $colors;
	}
	
	public function deleteDBAsUser(Dbase $dbase)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$time = new \DateTime('-30 day');
		$time->setTime(0, 0, 0);
		
		$query = "DELETE CO
					FROM contact CO
						JOIN dbase BD ON CO.idDbase = BD.idDbase
					WHERE BD.idDbase = ?
					AND NOT EXISTS
						(SELECT MC.idContact
						FROM mxc MC
							JOIN mail MA ON MC.idMail = MA.idMail
						WHERE MC.idContact = CO.idContact
						AND MA.finishedon > {$time->getTimestamp()})";
		
		$query2  = "DELETE FROM dbase WHERE idDbase = ? AND ( SELECT COUNT(*) FROM contact WHERE idDbase = ? ) < 1";
		
		$db->begin();
		
		$cascadeDelete = $db->execute($query, array($dbase->idDbase));
		$dbDelete = $db->execute($query2, array($dbase->idDbase, $dbase->idDbase));
		
		if ($cascadeDelete == false || $dbDelete == false) {
			$db->rollback();
			throw new \Exception('Ha ocurrido un error, contacta al administrador !');
		}
		
		$db->commit();	
		
		$dbase->updateCountersInDbase();
		if(isset($dbase->idDbase)) {
			$lists = Contactlist::findByIdDbase($dbase->idDbase);
			foreach ($lists as $list) {
				$list->updateCountersInContactlist();
			}
		}
	}
}