<?php
class TrackingUrlObject
{
	protected $idMail;
	protected $idContact;
	protected $links;
	protected $html;
	protected $urls;


	public function getTrackingUrl($html, $idMail, $idContact, $urls) 
	{
		$this->links = array();
		$this->idMail = $idMail;
		$this->idContact = $idContact;
		$this->html = $html;
		$this->urls = $urls;
		
		Phalcon\DI::getDefault()->get('logger')->log('Empezando proceso de tracking');
		
		Phalcon\DI::getDefault()->get('logger')->log('Antes: ' . print_r($this->links, true));
		$this->getOpenTrackingUrl();
		$this->getClicksTrackingUrl();
		
		
		Phalcon\DI::getDefault()->get('logger')->log('Despúes: ' . print_r($this->links, true));
		$htmlWithTracking = str_replace($this->links['search'], $this->links['replace'], $html);
		
		return $htmlWithTracking;
	}
	
	public function getOpenTrackingUrl()
	{
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $urlManager->getBaseUri(true) . 'track/open/1-' . $this->idMail . '-' . $this->idContact;
		$md5 = md5($src . '-Sigmamovil_Rules');
		$img = '<img src="' . $src . '-' . $md5 . '" />'; 
	
		$this->links['search'][] = '$$$_open_track_$$$';
		$this->links['replace'][] = $img;
		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de track apertura: ' . $img);
	}
	
	public function getClicksTrackingUrl()
	{
		if (count($this->urls) !== 0) {
			while ($true = current($this->urls)) {
				$this->links['search'][] = key($this->urls);
				$value = current($this->urls);
				$href = $value . '-' . $this->idMail . '-' . $this->idContact;
				$md5 = md5($href . '-Sigmamovil_Rules');
				$link = $href . '-' . $md5;
				$this->links['replace'][] = $link;
				Phalcon\DI::getDefault()->get('logger')->log('Insertando link de click: ' . $link);
				next($this->urls);
			}
		}
	}
	
	public function searchDomainsAndProtocols($html, $text)
	{
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		
		$imgTag = new DOMDocument();
		@$imgTag->loadHTML($html);

		$hrefs = $imgTag->getElementsByTagName('a');
		
		$urls = array();
		if ($hrefs->length !== 0) {
			foreach ($hrefs as $href) {
				$link = $href->getAttribute('href');
				$domain = $this->validateDomain($link);
				if ($domain !== false && !in_array($domain, $urls)) {
					$urls[] = $domain;
					Phalcon\DI::getDefault()->get('logger')->log('Dominio html: ' . $domain);
				}
			}
		}
		
		if(preg_match_all($reg_exUrl, $text, $u)) {
			$links = $u[0];
			foreach ($links as $link) {
				$domain = $this->validateDomain($link);
				if ($domain !== false && !in_array($domain, $urls)) {
					$urls[] = $domain;
					Phalcon\DI::getDefault()->get('logger')->log('Dominio text: ' . $domain);
				}
			}
		}
		
		return $urls;
	}
	
	private function validateDomain($link)
	{
		$invalidDomains = array(
			'facebook' => '/[^\/]*\.*facebook.com.*$/',
			'twiter' => '/[^\/]*\.*twitter.com.*$/',
			'linkedin' => '/[^\/]*\.*linkedin.com.*$/',
			'google-plus' => '/[^\/]*\.*plus.google.com.*$/'
		);
		
		$parts = parse_url($link);
		foreach ($invalidDomains as $domain) {
			if (!isset($parts['host']) || empty($parts['host']) || preg_match($domain, $link)) {
				return false;
			}
		}
		
		return $parts['scheme'] . '://' . $parts['host'];
	}		
	
}