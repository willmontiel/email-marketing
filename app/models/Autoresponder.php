<?php

class Autoresponder extends \Phalcon\Mvc\Model
{
	public function initialize()
    {
		$this->hasMany("idAutoresponder", "Mxa", "idAutoresponder");
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
	}
}


