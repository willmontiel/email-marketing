<?php
class TrackingUrlObject
{
	protected $idMail;
	protected $idContact;
	protected $links;
	protected $html;
	protected $urls;
	protected $urlManager;
	protected $unsubscribe_link;

	public function __construct() 
	{
		$this->urlManager = Phalcon\DI::getDefault()->get('urlManager');
	}
	
	public function getTrackingUrl($html, $idMail, $idContact, $urls) 
	{
		$this->links = array();
		$this->idMail = $idMail;
		$this->idContact = $idContact;
		$this->html = $html;
		$this->urls = $urls;
		
//		Phalcon\DI::getDefault()->get('logger')->log('Empezando proceso de tracking');
		
//		Phalcon\DI::getDefault()->get('logger')->log('Antes: ' . print_r($this->links, true));
		$this->getOpenTrackingUrl();
		$this->getClicksTrackingUrl();
		$this->getWebVersionTrack();
		$this->getSocialMediaShare();
		$this->getUnsubscribeTracking();
		
//		Phalcon\DI::getDefault()->get('logger')->log('Despúes: ' . print_r($this->links, true));
		$htmlWithTracking = str_replace($this->links['search'], $this->links['replace'], $html);
		
		return $htmlWithTracking;
	}
	
	public function getSocialTrackingUrl($html, $idMail, $idContact, $urls, $social) 
	{
		$this->links = array();
		$this->idMail = $idMail;
		$this->idContact = $idContact;
		$this->html = $html;
		$this->urls = $urls;
		
		$this->getOpenTrackingUrl($social);
		$this->getClicksTrackingUrl($social);
		$this->getSocialMediaShare();
		
//		Phalcon\DI::getDefault()->get('logger')->log('Despúes: ' . print_r($this->links, true));
		$htmlWithTracking = str_replace($this->links['search'], $this->links['replace'], $html);
		
		return $htmlWithTracking;
	}
	
	public function getOpenTrackingUrl($social = false)
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		if ($social !== false) {
			$action = 'track/opensocial';
			$parameters = array(1, $this->idMail, $this->idContact, $social);
		}
		else {
			$action = 'track/open';
			$parameters = array(1, $this->idMail, $this->idContact);
		}
		
		$url = $linkdecoder->encodeLink($action, $parameters);
		$img = '<img src="' . $url . '" />'; 
	
		$this->links['search'][] = '$$$_open_track_$$$';
		$this->links['replace'][] = $img;
//		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de track apertura: ' . $img);
	}
	
	public function getWebVersionTrack()
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		$parameters = array(1, $this->idMail, $this->idContact);
		$url = $linkdecoder->encodeLink('webversion/show', $parameters);
		
		$this->links['search'][] = '$$$_webversion_track_$$$';
		$this->links['replace'][] = $url;
//		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de version web: ' . $url);
	}
	
	public function getSocialMediaShare()
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		$parameters = array(1, $this->idMail, $this->idContact);
		$url = $linkdecoder->encodeLink('socialmedia/share', $parameters);
		
		$this->links['search'][] = '$$$_social_media_share_$$$';
		$this->links['replace'][] = $url . '-';
//		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de version web: ' . $url);
	}
	
	public function getUnsubscribeTracking()
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		$parameters = array(1, $this->idMail, $this->idContact);
		$url = $linkdecoder->encodeLink('unsubscribe/contact', $parameters);
		
		$this->links['search'][] = '$$$_unsubscribe_track_$$$';
		$this->links['replace'][] = $url;
		
		$this->unsubscribe_link = $url;
//		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de desuscripcion: ' . $url);
	}
	
	public function getClicksTrackingUrl($social = false)
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		if (count($this->urls) !== 0) {
			while ($true = current($this->urls)) {
				$this->links['search'][] = key($this->urls);
				$idMailLink = current($this->urls);
				
				if ($social !== false) {
					$action = 'track/clicksocial';
					$parameters = array(1, $idMailLink, $this->idMail, $this->idContact, $social);
				}
				else {
					$action = 'track/click';
					$parameters = array(1, $idMailLink, $this->idMail, $this->idContact);
				}
				
				$url = $linkdecoder->encodeLink($action, $parameters);
				
				$this->links['replace'][] = $url;
//				Phalcon\DI::getDefault()->get('logger')->log('Insertando link de click: ' . $url);
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
	
	public function getUnsubscribeLink()
	{
		return $this->unsubscribe_link;
	}
}