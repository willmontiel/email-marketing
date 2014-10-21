<?php

class Exportfile extends \Phalcon\Mvc\Model
{
	public $idAccount;
	public function initialize()
	{
		$this->belongsTo("idAccount", "Account", "idAccount");
		$this->useDynamicUpdate(true);
	}

	public function beforeCreate()
    {
        $this->createdon = time();
    }
}