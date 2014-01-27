<?php
class UrlManagerObject
{
	protected $urlManager;
	protected $protocol;
	protected $host;
	protected $port;
	protected $appbase;
	protected $api_v1;
	protected $api_v1_2;
	protected $assets;
	protected $protocol_mail;
	protected $host_mail;
	protected $host_assets;

	public function __construct($config)
	{	
		
		if (isset($config->urlmanager)) {
			$this->protocol = $config->urlmanager->protocol;
			$this->host = $config->urlmanager->host;
			$this->port = $config->urlmanager->port;
			$this->appbase = $config->urlmanager->appbase;
			$this->api_v1 = $config->urlmanager->api_v1;
			$this->api_v1_2 = $config->urlmanager->api_v1_2;
			$this->assets = $config->urlmanager->assets;
			$this->protocol_mail = $config->urlmanager->protocol_mail;
			$this->host_mail = $config->urlmanager->host_mail;
			$this->host_assets = $config->urlmanager->host_assets;;
		}
		else {
			$this->protocol = "http";
			$this->host = "localhost";
			$this->port = 80;
			$this->appbase = "emarketing";
			$this->api_v1 = "api";
			$this->api_v1_2 = "apistatistics";
			$this->assets = "assets";
			$this->protocol_mail = "http";
			$this->host_mail = "nmailer.sigmamovil.com";
			$this->host_assets = "files.sigmamovil.com";
		}
		
	}
	
	/**
	 * Returns the url base ex: "emarketing"
	 * @return type
	 */
	public function getBaseUri($full = false)
	{
		return $this->appbase;
	}
	
	/**
	 * Return full url with protocol and host ex: "http://localhost/emarketing"
	 * @param type $full
	 * @return string
	 */
	public function getAppUrlBase($full = false)
	{
		$url = $this->protocol . '://' .$this->host .'/' . $this->appbase;
		return $url;
	}
	
	/**
	 * Return full or relative assets url ex: "http://localhost/emarketing/assets", "emarketing/assets"
	 * @param boolean $full
	 * @return URL string
	 */
	public function getAppUrlAsset($full = false)
	{
		$url = $this->full . $this->appbase . '/' .$this->assets;	
		return $url;
	}
	
	/**
	 * Return uri for ember comunication (API_v1) ex: "emarketing/api"
	 * @return URL string
	 */
	public function getApi_v1Url($full = false)
	{
		$url = $this->appbase . '/' .$this->api_v1;
		return $url;
	}
	
	/**
	 * Return uri for ember comunication (API_v1_2) ex: "emarketing/apistatistics"
	 * @return URL string
	 */
	public function getApi_v1_2Url($full = false)
	{
		$url = $this->appbase . '/' .$this->api_v1_2;
		return $url;
	}
}
