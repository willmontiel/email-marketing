<?php

class Attachment extends \Phalcon\Mvc\Model
{
	public $idMail;
	
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail",
			array("foreignKey" => true)
		);
		
		$this->useDynamicUpdate(true);
	}
}