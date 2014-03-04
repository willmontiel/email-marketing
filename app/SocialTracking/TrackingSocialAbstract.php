<?php

namespace EmailMarketing\SocialTracking;


abstract class TrackingSocialAbstract
{
	public static function createInstanceTracking(Mxc $mxc, $social)
	{
		switch ($social) {
			case 'facebook':
				$instance = new FacebookTrackingObject($mxc);
				break;
			case 'twitter':
				$instance = new TwitterTrackingObject($mxc);
				break;
			case 'googleplus':
				$instance = new GooglePlusTrackingObject($mxc);
				break;
			case 'linkedin':
				$instance = new LinkedInTrackingObject($mxc);
				break;
			default :
				throw new Exception('');
				break;
		}
		return $instance;
	}
	
	public function __construct($mxc)
	{
		$this->mxc = $mxc;
	}
	abstract public function trackOpen();
	
	public function save()
	{
		Phalcon\DI::getDefault()->get('logger')->log('Saving');
		if(!$this->mxc->save()) {
			foreach ($this->mxc->getMessages() as $msg) {
				Phalcon\DI::getDefault()->get('logger')->log('Error: ' . $msg);
			}
			throw new Exception('Exception: Error while saving tracking open social in Mxc');
		}
	}
}