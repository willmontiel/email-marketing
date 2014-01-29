<?php
class TrackingUrlObject
{
	protected $idMail;
	protected $idContact;
	protected $links;
	
	public function getTrackingUrl($html, $idMail, $idContact) 
	{
		$this->links = array();
		$this->idMail = $idMail;
		$this->idContact = $idContact;
		
		Phalcon\DI::getDefault()->get('logger')->log('Antes: ' . print_r($this->links, true));
		$this->getOpenTrackingUrl();
//		$this->getClicksTrackingUrl();
		
		Phalcon\DI::getDefault()->get('logger')->log('Empezando proceso de tracking');
		
		Phalcon\DI::getDefault()->get('logger')->log('Despúes: ' . print_r($this->links, true));
		$htmlWithTracking = str_replace($this->links['search'], $this->links['replace'], $html);
		
		return $htmlWithTracking;
	}
	
	public function getOpenTrackingUrl()
	{
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $urlManager->getBaseUri(true) . 'track/open/1_' . $this->idMail . '_' . $this->idContact;
		$md5 = md5($src . '_Sigmamovil_Rules');
		$img = '<img src="' . $src . '_' . $md5 . '" /></body>'; 
	
		$this->links['search'][] = '</body>';
		$this->links['replace'][] = $img;
//		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de track');
//		$htmlWithTracking = str_replace($search, $replace, $html);
//		
//		return $htmlWithTracking;
	}
	
	public function getClicksTrackingUrl()
	{
		
	}
	
	public function getBounceTrackingUrl()
	{
		
	}
	
	public function getSpamTrackingUrl()
	{
		
	}
}