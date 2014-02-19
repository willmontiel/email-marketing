<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
class Contactlist extends Modelbase 
{
	public $idDbase;
	public $idContactlist;
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		
		$this->hasMany("idContactlist", "Statcontactlist", "idContactlist");
	}
	
	public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "name",
				"message" => "Debes ingresar un nombre para la lista"
            )
        ));
	}
	
	public function beforeSave()
    {
        if ($this->description == NULL) {
			$this->description = "Sin Descripcion";
        }
    }
	
	public static function findContactListsInAccount(Account $account, $conditions = null, $bind = null, $limits = null) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT Contactlist.* FROM Contactlist JOIN Dbase WHERE idAccount = :idaccount:';
		if ($conditions != null) {
			$phql .= ' AND ' . $conditions;
			$options = $bind;
		}
		if ($limits != null && is_array($limits) && isset($limits['number']) ) {
			$number = $limits['number'];
			$start = isset($limits['offset'])?$limits['offset']:0;
			$phql .= ' LIMIT ' . $number . ' OFFSET ' . $start;
		}
		$options['idaccount'] = $account->idAccount;
		$query = $mm->executeQuery($phql, $options);
		
		return $query;
		
	}
	
	public static function countContactListsInAccount(Account $account, $conditions = null, $bind = null ) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT COUNT(*) cnt FROM Contactlist JOIN Dbase WHERE idAccount = :idaccount:';
		if ($conditions != null) {
			$phql .= ' AND ' . $conditions;
			$options = $bind;
		}
		$options['idaccount'] = $account->idAccount;
		$query = $mm->executeQuery($phql, $options);
		
		if ($query) {
			return $query[0]->cnt;
		}
		return 0;
		
	}

	public function getInactiveContacts()
	{
		return $this->Ctotal - ($this->Cactive + $this->Cunsubscribed + $this->Cbounced + $this->Cspam);
	}
	
	public function updateCountersInContactlist()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql = "CALL update_counters_list($this->idContactlist)";

		$db->execute($sql);
	}
}