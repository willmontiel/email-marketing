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
		$img = '<img src="' . $src . '-' . $md5 . '" /></body>'; 
	
		$this->links['search'][] = '</body>';
		$this->links['replace'][] = $img;
		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de track: ' . $img);
//		$htmlWithTracking = str_replace($search, $replace, $html);
//		
//		return $htmlWithTracking;
	}
	
	public function getClicksTrackingUrl()
	{
		if (count($this->urls) !== 0) {
//			Phalcon\DI::getDefault()->get('logger')->log('Hay links');
			$urlManager = Phalcon\DI::getDefault()->get('urlManager');
			$brands = array_keys($this->urls);
			
			foreach ($brands as $brand) {
				$this->links['search'][] = $brand;
			}
			foreach ($this->urls as $url) {
				$href = $url . '-' . $this->idMail . '-' . $this->idContact;
				$md5 = md5($href . '-Sigmamovil_Rules');
				$link = $href . '-' . $md5;
				Phalcon\DI::getDefault()->get('logger')->log('Insertando: ' . $link);
				$this->links['replace'][] = $link;
			}
		}
	}
	
	public function searchDomainsAndProtocols($html)
	{
		$imgTag = new DOMDocument();
		@$imgTag->loadHTML($html);

		$hrefs = $imgTag->getElementsByTagName('a');
		
		$urls = array();
		if ($hrefs->length !== 0) {
			foreach ($hrefs as $href) {
				$link = $href->getAttribute('href');
				$parts = parse_url($link);
				Phalcon\DI::getDefault()->get('logger')->log('Dominio: ' . $parts['host']);
				if (isset($parts['host'])) {
					if ($parts['host'] !== null && !preg_match('/[^\/]*\.*facebook.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*twitter.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*linkedin.com.*$/', $parts['host']) && !preg_match('/[^\/]*\.*plus.google.com.*$/', $parts['host'])) {
						if (!in_array($parts['scheme'] . '://' . $parts['host'], $urls)) {
							$urls[] = $parts['scheme'] . '://' . $parts['host'];
						}
					}
				}
			}
		}
		return $urls;
	}
}