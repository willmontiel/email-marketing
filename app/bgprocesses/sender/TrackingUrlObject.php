<?php
class TrackingUrlObject
{
	public function __construct() 
	{
		
	}
	
	public function getOpenTrackingUrl($html, $idMail, $idContact)
	{
		$urlManager = Phalcon\DI::getDefault()->get('urlManager');
		$src = $urlManager->getBaseUri(true) . '/track/open/1_' . $idMail . '_' . $idContact;
		$md5 = md5($src . '_Sigmamovil_Rules');
		$img = '<img src="' . $src . '_' . $md5 . '" /></body>'; 
		
		$search = array('</body>');
		$replace = array($img);
		Phalcon\DI::getDefault()->get('logger')->log('Insertando link de track');
		$htmlWithTracking = str_replace($search, $replace, $html);
		Phalcon\DI::getDefault()->get('logger')->log('Html: ' . $htmlWithTracking);
		
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