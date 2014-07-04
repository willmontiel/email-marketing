<?php

namespace EmailMarketing\General\Authorization;

class AuthHmacHeader implements \EmailMarketing\General\Authorization\AuthHeader
{
	protected $user;
	protected $pwd;
	protected $header;
	protected $method;
	protected $uri;
	protected $data;
	protected $permissions;
			
	function __construct($method, $uri, $data)
	{
		$this->method = $method;
		$this->uri = $uri;
		$this->data = $data;
		$this->permissions = array('apiversionone', 'api');
	}

	
	public function verifyHeader()
	{
//		Metodo getallheaders() no funciona con la version del servidor, encontrar solucion PERO YA!!
		$header = getallheaders();
		
		if ( isset($header['Authorization']) ) {
			$this->header = $header['Authorization'];
			return true;
		}
		
		throw new \InvalidArgumentException("Autenticación Invalida");
	}
	
	public function processHeader()
	{
		$header_data = explode(" ", $this->header);

		if(strtolower($header_data[0]) === 'hmac') {
			$auth = explode(":", base64_decode($header_data[1]));
			
			if(isset($auth[0]) && isset($auth[1])) {
				$this->user = ( isset($auth[0]) ) ? $auth[0] : null ;
				$this->pwd = ( isset($auth[1]) ) ? $auth[1] : null ;
				return true;
			}
		}
		
		throw new \Exception("Autenticación Invalida");
	}
	
	public function checkPermissions($controller, $action)
	{
		if(in_array($controller, $this->permissions)) {
			return true;
		}
		
		throw new \Exception("No tiene permisos para acceder a este recurso");
	}

	public function checkUserPWD(\Apikey $apikey)
	{
		$msg = $this->method . '|' . $this->uri . '|' . $this->data;
		$hash = hash_hmac('sha1', $msg, $apikey->secret);
		if($hash == $this->pwd && $apikey->status == 'Enable' ) {
			return true;
		} 
		
		throw new \Exception("HMAC Invalido");
	}
	
	public function getAuthUser()
	{
		return $this->user;
	}
		
}

?>
