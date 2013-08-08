<?php
class Contact extends \Phalcon\Mvc\Model
{
	
	public function initialize()
    {
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idEmail", "Email", "idEmail", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
    }	
}
