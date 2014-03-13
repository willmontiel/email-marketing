<?php
class Returnpath extends \Phalcon\Mvc\Model
{
	public $idReturnPath;
	public function initialize()
	{
		$this->hasMany("idReturnPath", "Account", "idReturnPath");
	}
}