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
	protected $templates;
	protected $footers;
	protected $protocol_mail;
	protected $host_mail;
	protected $host_assets;

	public function __construct($config)
	{	
		
		if (isset($config->urlmanager)) {
			$this->protocol = $config->urlmanager->protocol;
			$this->host = $config->urlmanager->host;
			$this->port = $config->urlmanager->port;
			if ($config->urlmanager->appbase != '') {
				$this->appbase = $config->urlmanager->appbase . '/';
			}
			else {
				$this->appbase = $config->urlmanager->appbase;
			}
			$this->api_v1 = $config->urlmanager->api_v1;
			$this->api_v1_2 = $config->urlmanager->api_v1_2;
			$this->assets = $config->urlmanager->assets;
			$this->templates = $config->urlmanager->templates;
			$this->footers = $config->urlmanager->footers;
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
			$this->assets = "asset";
			$this->templates = "template";
			$this->footers = "footer";
			$this->protocol_mail = "http";
			$this->host_mail = "nmailer.sigmamovil.com";
			$this->host_assets = "files.sigmamovil.com";
		}
		$this->appbaseTrailing = $this->appbase . (($this->appbase != '')?'/':'');
	}
	
	protected function getPrefix($full)
	{
		if ($full) {
			$prefix = $this->protocol . '://' .$this->host . '/';
		}
		else {
			$prefix = '';
		}
		return $prefix;
	}
	
	/**
	 * Returns the url base ex: "emarketing" and url full ex: "http://localhost/emarketing"
	 * @return type
	 */
	public function getBaseUri($full = false)
	{
		return $this->getPrefix($full) . $this->appbase;
	}
	
	/**
	 * Return full or relative assets url ex: "http://localhost/emarketing/assets", "emarketing/assets"
	 * @param boolean $full
	 * @return URL string
	 */
	public function getAppUrlAsset($full = false)
	{
		return $this->getPrefix($full) . $this->appbaseTrailing . $this->assets;
	}
	
	public function getUrlAsset()
	{
		return $this->assets;
	}
	
	public function getAppUrlTemplate($full = false)
	{
		return $this->getPrefix($full) . $this->appbaseTrailing . $this->templates;
	}
	
	public function getUrlTemplate()
	{
		return $this->templates;
	}
	
	public function getUrlFooter()
	{
		return $this->footers;
	}
	
	/**
	 * Return uri for ember comunication (API_v1) ex: "emarketing/api"
	 * @return URL string
	 */
	public function getApi_v1Url($full = false)
	{
		return $this->getPrefix($full) . $this->appbaseTrailing . $this->api_v1;
	}
	
	/**
	 * Return uri for ember comunication (API_v1_2) ex: "emarketing/apistatistics"
	 * @return URL string
	 */
	public function getApi_v1_2Url($full = false)
	{
		return $this->getPrefix($full) . $this->appbaseTrailing . $this->api_v1_2;
	}
        
        /**
	 * Return uri for ember comunication (API_v1_2) ex: "emarketing/apistatistics"
	 * @return URL string
	 */
	public function getApi_v1_3Url($full = false)
	{
		return $this->getPrefix($full) . $this->appbaseTrailing . 'dbaseapi';
	}
}
