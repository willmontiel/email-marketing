<?php
class Statcontactlist extends \Phalcon\Mvc\Model
{
	public $idMail;
	public $idContactlist;

	public function initialize()
    {
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idContactlist", "Contactlist", "idContactlist", array(
            "foreignKey" => true,
        ));
	}
	
	public function incrementUniqueOpens()
	{
		$this->uniqueOpens += 1;
	}
	
	public function incrementClicks()
	{
		$this->clicks += 1;
	}
	
	public function incrementBounced()
	{
		$this->bounced += 1;
	}
}