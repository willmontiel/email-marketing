<?php

namespace EmailMarketing\General\Authorization;

interface AuthHeader
{
	public function verifyHeader();
	public function processHeader();
	public function checkUserPWD(\Apikey $apikey);
	public function getAuthUser();
}