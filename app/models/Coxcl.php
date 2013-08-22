<?php
class Coxcl extends \Phalcon\Mvc\Model
{
public function initialize()
	{
		$this->belongsTo("idList", "Contactlist", "idList", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idContact", "Contact", "idContact", array(
            "foreignKey" => true,
        ));
	}
	public function beforeCreate()
    {
        $this->createdon = time();
    }	
}