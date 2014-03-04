<?php

namespace EmailMarketing\SocialTracking;

class GooglePlusTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() 
	{
		$this->mxc->open_gp += 1;
	}
	public function trackClick() 
	{
		$this->mxcxl->click_gp += 1;
	}
	public function trackShare() 
	{
		$this->mxc->share_gp += 1;
	}
}

