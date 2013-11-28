<?php
class Template extends \Phalcon\Mvc\Model
{
	public $idAccount;
	public function initialize()
	{
//		$this->belongsTo("idAccount", "Account", "idAccount", array(
//            "foreignKey" => true,
//        ));
		$this->useDynamicUpdate(true);
	}

	public static function findGlobalsAndPrivateTemplates(Account $account)
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT * FROM Template WHERE idAccount IS NULL OR idAccount = :idAccount:';
		$query =  $mm->executeQuery($phql, array('idAccount' => $account->idAccount));
	
		return $query;
	}
	
	public static function findPrivateCategoryTemplates(Account $account)
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT category FROM Template WHERE idAccount = :idAccount: GROUP BY category';
		$query =  $mm->executeQuery($phql, array('idAccount' => $account->idAccount));
	
		return $query;
	}
	
	public static function findGlobalCategoryTemplates()
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT category FROM Template WHERE idAccount IS NULL GROUP BY category';
		$query =  $mm->executeQuery($phql);
	
		return $query;
	}
	
}