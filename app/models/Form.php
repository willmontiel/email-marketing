<?php
class Form  extends \Phalcon\Mvc\Model
{
	public $idDbase;
	
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase");
	}
	
	public static function findAllFormsInAccount(Account $account) 
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$phql = 'SELECT f.idForm, f.name FROM form f JOIN dbase db ON (f.idDbase = db.idDbase) WHERE f.type = "Updating" AND db.idAccount = ' . $account->idAccount;

		$query = $db->fetchAll($phql);

		return $query;
		
	}
}

?>
