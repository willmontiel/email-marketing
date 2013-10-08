<?php
class Mxc extends \Phalcon\Mvc\Model
{
	public $idContact;
	public $idMail;
	
	public function initialize()
	{
		$this->belongsTo("idContact", "Contact", "idContact", array(
			"foreignKey" => true,
		));
		
		$this->belongsTo("idMail", "Mail", "idMail", array(
			"foreignKey" => true,
		));
	}
}
