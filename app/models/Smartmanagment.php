<?php
class Smartmanagment extends Modelbase
{
	public $idSmartmanagment;
	
	public function initialize()
    {
		 $this->hasMany("idSmartmanagment", "Rule", "idSmartmanagment");
		 $this->hasMany("idSmartmanagment", "Scorehistory", "idSmartmanagment");
	}
}