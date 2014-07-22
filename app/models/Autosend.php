<?php

class Autosend extends \Phalcon\Mvc\Model
{
	public function initialize()
    {
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
	}
}


