<?php
namespace EmailMarketing\SocialTracking;

class FacebookTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() {
		$this->mxc->open_fb += 1;
	}
}
