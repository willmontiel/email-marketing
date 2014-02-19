<?php
class Statcontactlist extends \Phalcon\Mvc\Model
{
	public $idMail;
	public $idDbase;

	public function initialize()
    {
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
	}
	
	public function incrementUniqueOpens()
	{
		$this->uniqueOpens += 1;
	}
}