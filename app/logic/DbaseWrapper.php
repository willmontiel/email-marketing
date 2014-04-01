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
}