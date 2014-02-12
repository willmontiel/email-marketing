<?php
class Socialnetwork extends \Phalcon\Mvc\Model
{
	public $idSocialnetwork;
	public function initialize()
	{
		$this->belongsTo("idUser", "User", "idUser", array(
            "foreignKey" => true,
        ));
	}
}