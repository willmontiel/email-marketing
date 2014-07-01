<?php

class Apikey extends Modelbase
{
	public $idUser;
	
	public function initialize()
	{
		$this->belongsTo("idUser", "User", "idUser",
			array("foreignKey" => true)
		);
	}
}
