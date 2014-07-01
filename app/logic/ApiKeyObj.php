<?php

class ApiKeyObj
{
	protected $user;
	
	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function createAPIKey()
	{
		$key = new Apikey();
		$key->idUser = $this->user->idUser;
		$key->apikey = 12345;
		$key->secret = 'abcd12ef';
		$key->status = 'Enable';
		$key->createdon = time();
		
		if (!$key->save()) {
			throw new InvalidArgumentException('No se pudo crear la API Key, por favor contacte al administrador');
		}
		
		return $key;
	}
	
	public function updateAPIKey()
	{
		
	}
}
