<?php
class Asset extends \Phalcon\Mvc\Model
{
	public $idAccount;
	
	public function initialize()
	{
		$this->belongsTo("idAccount", "Account", "idAccount");
		$this->useDynamicUpdate(true);
	}
			
	static public function findAllAssetsInAccount(Account $account)
	{
		$assets = self::find(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $account->idAccount)
		));
		return $assets;
	}
}
