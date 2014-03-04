<?php
namespace EmailMarketing\SocialTracking;

class FacebookTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() {
		\Phalcon\DI::getDefault()->get('logger')->log('ANTES Mxc->open_fb= ' . $this->mxc->open_fb);
		$this->mxc->open_fb += 1;
		\Phalcon\DI::getDefault()->get('logger')->log('DESPUES Mxc->open_fb= ' . $this->mxc->open_fb);
	}
}
