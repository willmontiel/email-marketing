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
			
	function __construct($method, $uri, $data)
	{
		$this->method = $method;
		$this->uri = $uri;
		$this->data = $data;
	}

	
	public function verifyHeader()
	{
		$header = getallheaders();
		
		if ( isset($header['Authorization']) ) {
			$this->header = $header['Authorization'];
			return true;
		}
		
		return false;
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
		
		return false;
	}
	
	public function checkUserPWD()
	{
		$msg = $this->method . '|' . $this->uri . '|' . $this->data;
		$key = '123456789';
		$hash = hash_hmac('sha1', $msg, $key);
		if($hash == $this->pwd) {
			return true;
		} 
		
		return false;
	}
	
	public function getAuthUser()
	{
		return $this->user;
	}
	
}

?>
