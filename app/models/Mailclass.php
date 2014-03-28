<?php
class Mailclass extends \Phalcon\Mvc\Model
{
	public $idMailClass;
	public function initialize()
	{
		$this->hasMany("idMailClass", "Account", "idMailClass");
	}
}