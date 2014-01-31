<?php
class Maillink extends \Phalcon\Mvc\Model
{
	public $idMailLink;
	public function initialize()
	{
		$this->hasMany("idMailLink", "Mxl", "idMailLink");
	}
}