<?php
class Maillink extends \Phalcon\Mvc\Model
{
	public $idMailLink;
	public $idAccount;
	
	public function initialize()
	{
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		
		$this->hasMany("idMailLink", "Mxl", "idMailLink");
		$this->hasMany("idMailLink", "Mxcxl", "idMailLink");
	}
}