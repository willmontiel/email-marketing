<?php
class Socialnetwork extends \Phalcon\Mvc\Model
{
	public $idSocialnetwork;
	public function initialize()
	{
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
	}
}