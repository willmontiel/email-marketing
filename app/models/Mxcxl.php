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
		
		$this->click_fb = 0;
		$this->click_tw = 0;
		$this->click_gp = 0;
		$this->click_li = 0;
	}
}