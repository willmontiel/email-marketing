<?php
class Statdbase extends \Phalcon\Mvc\Model
{
	public $idMail;
	public $idDbase;

	public function initialize()
    {
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
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
	
	public function incrementSpam()
	{
		$this->spam += 1;
	}
	
	public function incrementUnsubscribed()
	{
		$this->unsubscribed += 1;
	}
}