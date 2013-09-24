<?php
class CreateAclEmber
{
	protected static function di()
    {
        return \Phalcon\DI\FactoryDefault::getDefault();
    }
	
	public static function getAclToEmber($allow )
	{
		$di = self::di();
		
		$role = 'ROLE_SUDO';
		$cache = $di['cache'];
		$cacheAcl = $cache->get('acl-cache');
		$cacheMap = $cache->get('controllermap-cache');
		
		if(!isset($cacheMap[$allow])){
			return 0;
		}
		
		else{
			$reg = $cacheMap[$allow];
			foreach($reg as $resources => $actions){
				foreach ($actions as $act) {
					if (!$cacheAcl->isAllowed($role, $resources, $act)) {
						return 0;
					}
					else {
						return true;
					}
				}
			}
		}
	}
}