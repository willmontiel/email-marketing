<?php
require_once '../bootstrap/phbootstrap.php';

$memCache = new MemcacheAdminObject();
$memCache->clenMemcache();

class MemcacheAdminObject
{
	public function __construct() 
	{
		$this->cache = Phalcon\DI::getDefault()->get('cache');
		$this->log = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function clenMemcache()
	{
		echo 'Cleaning memcache metadata' . PHP_EOL;
		$this->cache->delete('controllermap-cache');
		$this->cache->delete('acl-cache');
		$this->log->log('Memcache metadata cleaned');
		echo 'Memcache metadata cleaned' . PHP_EOL;
	}
}
