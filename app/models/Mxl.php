<?php
class Mxl extends \Phalcon\Mvc\Model
{
	public $idMail;
	public $idMailLink;
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idMailLink", "Maillink", "idMailLink", array(
            "foreignKey" => true,
        ));
	}
}