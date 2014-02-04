<?php
class TrackingUrlObject
{
	protected $idMail;
	protected $idContact;
	protected $links;
	protected $html;
	
	public function getTrackingUrl($html, $idMail, $idContact) 
	{
		$this->links = array();
		$this->idMail = $idMail;
		$this->idContact = $idContact;
		$this->html = $html;
		
		Phalcon\DI::getDefault()->get('logger')->log('Empezando proceso de tracking');
		
//		Phalcon\DI::getDefault()->get('logger')->log('Antes: ' . print_r($this->links, true));
		$this->getOpenTrackingUrl();
		$this->getClicksTrackingUrl();
		
		
//		Phalcon\DI::getDefault()->get('logger')->log('Despúes: ' . print_r($this->links, true));
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
		$links = Mxl::find(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $this->idMail)
		));
		
		if (count($links) != 0) {
//			Phalcon\DI::getDefault()->get('logger')->log('Hay links');
			$urlManager = Phalcon\DI::getDefault()->get('urlManager');
			
			foreach ($links as $l) {
				$this->links['search'][] = $l->idMailLink . '_sigma_url_$$$';
		
				$href = $urlManager->getBaseUri(true) . 'track/click/1-' . $l->idMailLink . '-' . $this->idMail . '-' . $this->idContact;
				$md5 = md5($href . '-Sigmamovil_Rules');
				$link = $href . '-' . $md5;
				Phalcon\DI::getDefault()->get('logger')->log('Insertando: ' . $link);
				$this->links['replace'][] = $link;
			}
		}
	}
	
	public function getBounceTrackingUrl()
	{
		
	}
	
	public function getSpamTrackingUrl()
	{
		
	}
}