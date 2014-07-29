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
		$keys = $this->cache->queryKeys();
		foreach ($keys as $key) {
			$this->cache->delete($key);
		}
		$this->log->log('Memcache metadata cleaned');
		echo 'Memcache metadata cleaned' . PHP_EOL;
	}
}
