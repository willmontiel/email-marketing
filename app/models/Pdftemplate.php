<?php

class Pdftemplate extends Modelbase
{
	public $idPdftemplate;
	public function initialize()
    {
        $this->hasMany("idPdftemplate", "Pdfbatch", "idPdftemplate");
	}
}