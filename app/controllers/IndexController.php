<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$this->view->setVar('confAccount', $this->user->account);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
		
		
		
		$db = Phalcon\DI::getDefault()->get('db');
			
			$sql = "SELECT resource.name AS resource, roxre.action AS action 
					FROM roxre
						JOIN resource ON ( roxre.idResource = resource.idResource ) ";
			
			$results = $db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC);
			
			$resources = array();
			foreach ($results as $key) {
				$k = $key['resource'];
				$v = $key['action'];
				
				if(!isset($resources[$k])){
					$resources[$k];
				}
				$resources[$k][] = $v;
			}
			
			$this->view->setVar("resources", $resources);
			
			
			$sql2 ="SELECT resource.name AS resource, roxre.action AS action 
					FROM allowed
						JOIN roxre ON ( allowed.idRoxre = roxre.idRoxre ) 
						JOIN resource ON ( roxre.idResource = resource.idResource ) 
					WHERE allowed.idRole =1";
			
			$allowedResources = $db->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC);
			
			//Grant acess to private area to ROLE_ADMIN
			$allow = array();
			
			foreach($allowedResources as $allowedResource){
				if(!isset($allow[$allowedResource['resource']])){
					$allow[$allowedResource['resource']];
				}
				$allow[$allowedResource['resource']][] = $allowedResource['action'];
			}
			$this->view->setVar("allow", $allow);
		
    }
}