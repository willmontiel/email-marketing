<?php

class Fieldinstance extends \Phalcon\Mvc\Model
{
public function initialize()
	{
		$this->belongsTo("idCustomField", "Customfield", "idCustomField", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idContact", "Contact", "idContact", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
	}
}