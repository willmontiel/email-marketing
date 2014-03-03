<?php
class Mxcxl extends \Phalcon\Mvc\Model
{
	public $idMail;
	public $idMailLink;
	public $idContact;
	
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idMailLink", "Maillink", "idMailLink", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idContact", "Contact", "idContact", array(
            "foreignKey" => true,
        ));
	}
}