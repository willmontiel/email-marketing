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
		
		$idSession = $di['session'];
		
		$role = User::findFirstByIdUser($idSession->get('userid'));
		
		$cache = $di['cache'];
		$cacheMap = $cache->get('controllermap-cache');
		$acl = $cache->get('acl-cache');
		
		if(!isset($cacheMap[$allow])){
			return 0;
		}
		
		else{
			$reg = $cacheMap[$allow];
			foreach($reg as $resources => $actions){
				foreach ($actions as $act) {
					if (!$acl->isAllowed($role->userrole, $resources, $act)) {
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