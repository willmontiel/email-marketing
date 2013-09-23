<?php
class Role extends \Phalcon\Mvc\Model
{
	public $idRole;
	
	public function initialize()
	{
		$this->hasMany("idRole", "Allowed", "idRole");
	}
}