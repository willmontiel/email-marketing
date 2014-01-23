<?php
class TrackingUrlObject
{
	public function __construct() 
	{
		
	}
	
	public function getOpenTrackingUrl($html, $idMail, $idContact)
	{
		$urlManager = new UrlManagerObject(true);
		$src = $urlManager->getAppUrlBase() . '/tracking/open/1_' . $idMail . '_' . $idContact;
		$md5 = md5($src . '_Sigmamovil_Rules');
		$img = '<img src="' . $src . '_' . $md5 . '" /></body>'; 
		
		$search = array('</body>');
		$replace = array($img);
		
		$htmlWithTracking = str_replace($search, $replace, $html);
		
		return $htmlWithTracking;
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