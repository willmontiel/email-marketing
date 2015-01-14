<?php

class Pdfbatch extends Modelbase
{
	public $idAccount;
	
	public function initialize()
    {
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
	}
}