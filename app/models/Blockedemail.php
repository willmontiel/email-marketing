<?php
class Blockedemail extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		$this->belongsTo("idEmail", "Email", "idEmail", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
	}
	
	public function beforeCreate()
    {
        $this->blockedDate = time();
    }
}