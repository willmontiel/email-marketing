<?php

namespace EmailMarketing\General\Authorization;

interface AuthHeader
{
	public function verifyHeader();
	public function processHeader();
	public function checkUserPWD();
	public function getAuthUser();
}