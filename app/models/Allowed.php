<?php
class Allowed extends \Phalcon\Mvc\Model
{
	public $idAction;
	public $idRole;
			
	public function initialize()
    {
        $this->belongsTo("idAction", "Action", "idAction", array(
            "foreignKey" => true,
        ));
		
		$this->belongsTo("idRole", "Role", "idRole", array(
			"foreignKey" => true,
		));
    }
}