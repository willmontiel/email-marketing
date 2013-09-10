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
	
	/**
	 * 
	 * @param array $contactids lista de ids de contactos para busqueda de customfields
	 * @return \Phalcon\Mvc\Model\ResultsetInterface
	 */
	public static function findInstancesForMultipleContacts($contactids)
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT Fieldinstance.* FROM Fieldinstance WHERE idContact IN (' . implode(',', $contactids). ')';
		$query = $mm->executeQuery($phql);
		
		return $query;
	}
}