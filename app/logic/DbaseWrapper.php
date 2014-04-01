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
}