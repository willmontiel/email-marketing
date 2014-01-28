<?php
class UserAgentDetectorObj
{
	public $info;
	public function __construct() 
	{
		
	}
	
	public function setInfo($info)
	{
		$this->info = $info;
	}
	
	public function getOperativeSystem()
	{
		if (preg_match('/linux/i', $this->info)) {
			$so = 'Linux';
		}
		else if (preg_match('/macintosh|mac os x/i', $this->info)) {
			$so = 'MacOs';
		}
		else if (preg_match('/windows|win32/i', $this->info)) {
			$so = 'Windows';
		}
		else if (strpos('Android', $this->info)) {
			$so = 'Android';
		}
		else {
			$so = 'Otro';
		}
		
		return $so;
	}
	
	public function getBrowser()
	{
		if((preg_match('/MSIE/i',$this->info) || preg_match('/WOW64/i',$this->info)) && !preg_match('/Opera/i',$this->info)){
			$browser = 'Internet Explorer';
		}
		elseif(preg_match('/Firefox/i',$this->info)){
			$browser = 'Mozilla Firefox';
		}
		elseif(preg_match('/Chrome/i',$this->info) && (!preg_match('/Opera/i',$this->info)) || !preg_match('/OPR/i',$this->info)){
			$browser = 'Google Chrome';
		}
		elseif(preg_match('/Opera/i',$this->info) || preg_match('/OPR/i',$this->info)){
			$browser = 'Opera';
		}
		elseif(preg_match('/Safari/i',$this->info)){
			$browser = 'Apple Safari';
		}
		elseif(preg_match('/Netscape/i',$this->info)){
			$browser = 'Netscape';
		}
		else {
			$browser = 'Otro';
		}
		
		return $browser;
	}
}