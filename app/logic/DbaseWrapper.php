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
	
	public static function getColors()
	{
		$colors = array(
			0 => array('9e0b0f', 'a0410d', 'a3620a', 'aba000', '598527', '197b30'),
			1 => array('603913', '754c24', '8c6239', 'a67c52', 'c69c6d', '362f2d'),
			2 => array('00FFFF', '00BFFF', '0080FF', '0072bc', '0054a6', '003471'),
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
						AND MA.startedon > {$time->getTimestamp()}
						AND ( MA.finishedon = 0 OR MA.finishedon > {$time->getTimestamp()} ) )";
		
		$query2  = "DELETE FROM dbase WHERE idDbase = ? AND ( SELECT COUNT(*) FROM contact WHERE idDbase = ? ) < 1";
		
		$db->begin();
		
		$cascadeDelete = $db->execute($query, array($dbase->idDbase));
		$dbDelete = $db->execute($query2, array($dbase->idDbase, $dbase->idDbase));
		
		if ($cascadeDelete == false || $dbDelete == false) {
			$db->rollback();
			throw new \Exception('Ha ocurrido un error, contacta al administrador !');
		}
		
		$db->commit();	
		
		$chkdbase = Dbase::findFirstByIdDbase($dbase->idDbase);
		if($chkdbase) {
			$chkdbase->updateCountersInDbase();
			$lists = Contactlist::findByIdDbase($dbase->idDbase);
			foreach ($lists as $list) {
				$list->updateCountersInContactlist();
			}
			throw new \Exception('La base de datos no se pudo eliminar ya que aun contiene contactos');
		}
	}
}