<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$this->view->setVar('confAccount', $this->user->account);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
	
//		$db = Phalcon\DI::getDefault()->get('db');
//			
//			$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
//			
//			$sql = "SELECT resource.name AS resource, roxre.action AS action 
//					FROM roxre
//						JOIN resource ON ( roxre.idResource = resource.idResource )";
//			
//			$query = $modelManager->createQuery($sql);
//			$results = $query->execute($sql);
//			
//			$resources = array();
//			foreach ($results as $key) {
//				$k = $key['resource'];
//				$v = $key['action'];
//				
//				if(!isset($resources[$k])){
//					$resources[$k];
//				}
//				$resources[$k][] = $v;
//			}
//			
//			$this->view->setVar("resources", $resources);
//			
//			
//			$sql2 ="SELECT resource.name AS resource, roxre.action AS action 
//					FROM allowed
//						JOIN roxre ON ( allowed.idRoxre = roxre.idRoxre ) 
//						JOIN resource ON ( roxre.idResource = resource.idResource ) 
//					WHERE allowed.idRole =1";
//			
//			$allowedResources = $db->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC);
//			
//			//Grant acess to private area to ROLE_ADMIN
//			$allow = array();
//			
//			foreach($allowedResources as $allowedResource){
//				if(!isset($allow[$allowedResource['resource']])){
//					$allow[$allowedResource['resource']];
//				}
//				$allow[$allowedResource['resource']][] = $allowedResource['action'];
//			}
//			$this->view->setVar("allow", $allow);
//			
//			$map = array(
//				'account::index' => array('account' => array('read')),
//				'account::new' => array('account' => array('create', 'read')),
//				'account::edit' => array('account' => array ('read', 'update')),
//				'account::show' => array('user' => array ('read')),
//				'account::delete' => array('account' => array ('read', 'delete')),
//				'account::newuser' => array('user' => array ('read', 'create')),
//				'account::edituser' => array('user' => array ('read','edit')),
//				'account::deleteuser' => array('user' => array ('read', 'delete'))
//			);
//			$controller = 'account';
//			$action = 'new';
//			
//			$reg = $map[$controller .'::'. $action];
//			
//			$x = array();
//			foreach($reg as $resources => $actions){
//				$x = $resources;
//				$k = $actions;
//			}
//			$this->view->setVar('map', $resources);
		
		
//		$frontCache = new Phalcon\Cache\Frontend\Data();
//		$cache = new Phalcon\Cache\Backend\Memcache($frontCache, array(
//				"host" => "localhost",
//				"port" => "11211"
//			));
//		$cache->queryKeys("userandroles.cache");
//
//		// Delete all items from the cache
//		$keys = $cache->queryKeys();
//		foreach ($keys as $key) {
//			$cache->delete($key);
//		}
//		$products = $cache->get("roles.cache");
//		$x = $cache->get("resources.cache");
//		$j = $cache->get("userandroles.cache");
//		if ($products === null){
//			$this->flashSession->success('Se ha borrado el caché');
//			return $this->response->redirect("index/index");
//		}
//		$this->view->setVar('map', $products);
//		$this->view->setVar('map2', $x);
//		$this->view->setVar('map3', $j);
//		
    }
}