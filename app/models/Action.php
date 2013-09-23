<?php
class Action extends \Phalcon\Mvc\Model
{
	public $idResource;
	public $idAction;

    public function initialize()
    {
        $this->belongsTo("idResource", "Resource", "idResource", array(
            "foreignKey" => true,
        ));
		
		$this->hasMany("idAction", "Allowed", "idAction");
    }
}