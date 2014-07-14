<?php

class ApiKeyObj
{
	protected $user;
	
	public function setUser(User $user)
	{
		$this->user = $user;
	}
	
	protected function APIKeyGenerator()
	{
		$key = uniqid('', true);
		return $this->user->account->idAccount . '-' . $this->user->idUser . '-' . $key;
	}
	
	protected function SecretGenerator()
	{
		return sha1($this->user->account->companyName . $this->user->username . time());
	}

	public function createAPIKey()
	{
		$key = new Apikey();
		$key->idUser = $this->user->idUser;
		$key->apikey = $this->APIKeyGenerator();
		$key->secret = $this->SecretGenerator();
		$key->status = 'Enable';
		$key->createdon = time();
		
		if (!$key->save()) {
			throw new InvalidArgumentException('No se pudo crear la API Key, por favor contacte al administrador');
		}
		
		return $key;
	}
	
	public function updateAPIKey()
	{
		$this->user->apikey->apikey = $this->APIKeyGenerator();
		$this->user->apikey->secret = $this->SecretGenerator();
		$this->user->apikey->status = 'Enable';
		
		if (!$this->user->apikey->save()) {
			throw new InvalidArgumentException('No se pudo crear la API Key, por favor contacte al administrador');
		}
		
		return $this->user->apikey;
	}
	
	public function updateAPIKeyStatus($status)
	{
		$this->user->apikey->status = ($status === 'true') ? 'Enable' : 'Disable' ;
		
		if (!$this->user->apikey->save()) {
			throw new InvalidArgumentException('No se pudo crear la API Key, por favor contacte al administrador');
		}
		
		return $this->user->apikey;
	}
	
	public function deleteAPIKey()
	{
		if(!$this->user->apikey->delete()){
			throw new InvalidArgumentException('No se pudo eliminar la API Key, por favor contacte al administrador');
		}
	}
}
