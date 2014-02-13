<?php
class Socialmail extends \Phalcon\Mvc\Model
{
	public $idSocialmail;
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail", array(
            "foreignKey" => true,
        ));
	}
}