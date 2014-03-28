<?php
class Urldomain extends \Phalcon\Mvc\Model
{
	public $idUrlDomain;
	public function initialize()
	{
		$this->hasMany("idUrlDomain", "Account", "idUrlDomain");
	}
}