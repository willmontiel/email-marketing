<?php
namespace EmailMarketing\SocialTracking;

class LinkedInTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() 
	{
		$this->mxc->open_li += 1;
	}
}

