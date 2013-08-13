<?php
class ContactlistController extends ControllerBase
{
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;
	}
}


