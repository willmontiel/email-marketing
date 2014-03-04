<?php
namespace EmailMarketing\SocialTracking;

class TwitterTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() 
	{
		$this->mxc->open_tw += 1;
	}
}

