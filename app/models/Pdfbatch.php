<?php

class Pdfbatch extends Modelbase
{
	public $idAccount;
	public $idPdftemplate;
	
	public function initialize()
    {
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
		
		$this->belongsTo("idPdftemplate", "Pdftemplate", "idPdftemplate",
			array("foreignKey" => true)
		);
	}
}