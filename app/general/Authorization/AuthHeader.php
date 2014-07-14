<?php

namespace EmailMarketing\General\Authorization;

interface AuthHeader
{
	public function verifyHeader();
	public function checkPermissions($controller, $action);
	public function processHeader();
	public function checkUserPWD(\Apikey $apikey);
	public function getAuthUser();
}