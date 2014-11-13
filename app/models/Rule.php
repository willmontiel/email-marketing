<?php

class Rule extends Modelbase
{
	public $idSmartmanagment;
	
	public function initialize()
    {
		$this->belongsTo("idSmartmanagment", "Smartmanagment", "idSmartmanagment",
			array("foreignKey" => true)
		);
		
	}
}
