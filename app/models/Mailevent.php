<?php
class Mailevent extends \Phalcon\Mvc\Model
{
public $idMail;
public $idContact;
public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idContact", "Contact", "idContact", array(
            "foreignKey" => true,
        ));
	}	
}