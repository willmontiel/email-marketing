<?php
namespace EmailMarketing\SocialTracking;

class LinkedInTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() 
	{
		$this->mxc->open_li += 1;
	}
	public function trackClick() 
	{
		$this->mxcxl->click_li += 1;
	}
}

