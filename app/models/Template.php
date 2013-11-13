<?php
class Template extends \Phalcon\Mvc\Model
{
	public static function findGlobalsAndPrivateTemplates(Account $account)
	{
		$mm = Phalcon\DI::getDefault()->get('modelsManager');
		
		$phql = 'SELECT * FROM Template WHERE idAccount IS NULL OR idAccount = :idAccount:';
		$query =  $mm->executeQuery($phql, array('idAccount' => $account->idAccount));
	
		return $query;
	}
}