<?php
class Asset extends \Phalcon\Mvc\Model
{
	public $idAccount;
	
	public function initialize()
	{
		$this->belongsTo("idAccount", "Account", "idAccount");
		$this->useDynamicUpdate(true);
	}
			
	public function findAllAssetInAccount()
	{
		$assets = Asset::find(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $this->idAccount)
		));
		return $assets;
	}
}
