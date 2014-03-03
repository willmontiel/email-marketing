<?php
class Bouncedcode extends \Phalcon\Mvc\Model
{
	public $idBouncedCode;

    public function initialize()
    {
        $this->hasMany("idBouncedCode", "Mxc", "idBouncedCode");
    }
}
