<?php

namespace EmailMarketing\SocialTracking;


abstract class TrackingSocialAbstract
{
	public static function createInstanceTracking($social)
	{
		switch ($social) {
			case 'facebook':
				$instance = new FacebookTrackingObject();
				break;
			case 'twitter':
				$instance = new TwitterTrackingObject();
				break;
			case 'googleplus':
				$instance = new GooglePlusTrackingObject();
				break;
			case 'linkedin':
				$instance = new LinkedInTrackingObject();
				break;
			default :
				throw new Exception('Red social desconocida: ' . $social);
				break;
		}
		return $instance;
	}
	public function setMxc(\Mxc $mxc)
	{
		$this->mxc = $mxc;
	}
	
	public function setMxcxl(\Mxcxl $mxcxl)
	{
		$this->mxcxl = $mxcxl;
	}
	
	abstract public function trackOpen();
	abstract public function trackClick();
	abstract public function trackShare();
	
	public function save()
	{
		\Phalcon\DI::getDefault()->get('logger')->log('Saving');
		if(!$this->mxc->save()) {
			foreach ($this->mxc->getMessages() as $msg) {
				\Phalcon\DI::getDefault()->get('logger')->log('Error: ' . $msg);
			}
			throw new \Exception('Exception: Error while saving tracking open social in Mxc');
		}
	}
	
	public function saveClick()
	{
		\Phalcon\DI::getDefault()->get('logger')->log('Saving');
		if(!$this->mxcxl->save()) {
			foreach ($this->mxcxl->getMessages() as $msg) {
				\Phalcon\DI::getDefault()->get('logger')->log('Error: ' . $msg);
			}
			throw new \Exception('Exception: Error while saving tracking open social in Mxcxl');
		}
	}
}