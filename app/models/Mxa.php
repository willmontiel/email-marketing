<?php

class Mxa extends \Phalcon\Mvc\Model
{
	public $idAutoresponder;
	public $idMail;
	
	public function initialize()
	{
		$this->belongsTo("idAutoresponder", "Autoresponder", "idAutoresponder", array(
			"foreignKey" => true,
		));
		
		$this->belongsTo("idMail", "Mail", "idMail", array(
			"foreignKey" => true,
		));
	}
}
