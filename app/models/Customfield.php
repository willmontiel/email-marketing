<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Customfield extends \Phalcon\Mvc\Model
{
	public $idBase;
	public $idCustomField;
	
	public function initialize()
    {
    
		$this->useDynamicUpdate(true);
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		/**
		 * Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
		 */
		$this->useDynamicUpdate(true);
		
    }
	public function beforeValidationOnCreate()
    {
        if (!isset($this->values) || $this->values == NULL) {
			$this->values = "";
        }
		
		if (!isset($this->required) || $this->required == NULL) {
			$this->required = 2;
        }
    }
	public function validation()
    {
		
		
		$this->validate(new PresenceOf(
				array(
					"field"   => "name",
					"message" => "Debes asignar un nombre al campo personalizado"
            )
        ));
		
		if ($this->validationHasFailed() == true) {
			return false;
        }
		
		
		
	}
	
	public static function findCustomfieldsForDbase(Dbase $db)
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT Customfield.* FROM Customfield WHERE idDbase = :iddbase:';
		$query = $mm->executeQuery($phql, array('iddbase' => $db->idDbase));
		
		return $query;
	}
	
	public static function findAllCustomfieldNamesInAccount(Account $account) 
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$phql = 'SELECT DISTINCT cf.name FROM Customfield cf JOIN Dbase db ON (cf.idDbase = db.idDbase) WHERE db.idAccount = ' . $account->idAccount;
		
		$query = $db->fetchAll($phql);

		return $query;
		
	}
}
