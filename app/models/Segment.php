<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Segment extends \Phalcon\Mvc\Model
{
	public $idDbase;
	public $idSegment;
	
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		
		$this->hasMany("idSegment", "Sxc", "idSegment");
		
		$this->hasMany("idSegment", "Criteria", "idSegment");
	}
	public function validation()
	{
		$this->validate(new PresenceOf(
            array(
                "field"   => "name",
				"message" => "Debes ingresar un nombre para el segmento"
            )
        ));
	}
	
	public function beforeCreate()
    {
        $this->createdon = time();
		if ($this->description == null) {
			$this->description = "Sin descripción";
		}
    }
	
	public static function countSegmentsInAccount(Account $account, $conditions = null, $bind = null ) 
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT COUNT(*) cnt FROM Segment s JOIN Dbase d ON s.idDbase = d.idDbase WHERE d.idAccount = :idaccount:';
		
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
}
