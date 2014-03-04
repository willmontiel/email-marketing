<?php
namespace EmailMarketing\SocialTracking;

class TwitterTrackingObject extends TrackingSocialAbstract
{
	public function trackOpen() 
	{
		$this->mxc->open_tw += 1;
	}
	
	public function trackClick() 
	{
		$this->mxcxl->click_tw += 1;
	}
	
	public function trackShare() 
	{
		$this->mxc->share_tw += 1;
	}
}

