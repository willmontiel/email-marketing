<?php

class Scorehistory extends Modelbase
{
	public $idAccount;
	public $idSmartmanagment;
	public $idMail;
	
	public function initialize()
    {
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
		
		$this->belongsTo("idSmartmanagment", "Smartmanagment", "idSmartmanagment",
			array("foreignKey" => true)
		);
		
		$this->belongsTo("idMail", "Mail", "idMail",
			array("foreignKey" => true)
		);
		
	}
}