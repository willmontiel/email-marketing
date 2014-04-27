<?php
class Form  extends \Phalcon\Mvc\Model
{
	public $idDbase;
	
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase");
	}
}

?>
