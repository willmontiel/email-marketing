<?php

class Pdfmail extends Modelbase
{
	public $idMail;
	
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail",
			array("foreignKey" => true)
		);
	}
}