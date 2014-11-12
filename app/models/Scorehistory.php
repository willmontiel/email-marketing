<?php

class Scorehistory extends Modelbase
{
	public $idAccount;
	public $idSmartmanagment;
	
	public function initialize()
    {
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
		
		$this->belongsTo("idSmartmanagment", "Smartmanagment", "idSmartmanagment",
			array("foreignKey" => true)
		);
		
	}
}