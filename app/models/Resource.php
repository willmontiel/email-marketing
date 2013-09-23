<?php
class Resource extends \Phalcon\Mvc\Model
{
	public $idResource;
	
	public function initialize()
    {
        $this->hasMany("idResource", "Action", "idResource");
    }
}