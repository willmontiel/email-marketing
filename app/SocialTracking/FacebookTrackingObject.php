<?php
namespace EmailMarketing\SocialTracking;

class FacebookTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen()
	{
		\Phalcon\DI::getDefault()->get('logger')->log('ANTES Mxc->open_fb= ' . $this->mxc->open_fb);
		$this->mxc->open_fb += 1;
	}
	public function trackClick() 
	{
		\Phalcon\DI::getDefault()->get('logger')->log('ANTES Mxcxl->click_fb= ' . $this->mxcxl->click_fb);
		$this->mxcxl->click_fb += 1;
		\Phalcon\DI::getDefault()->get('logger')->log('DESPUES Mxcxl->click_fb= ' . $this->mxcxl->click_fb);
	}
}
