<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$this->view->setVar('confAccount', $this->user->account);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();

//		$products = $this->cache->get('controllermap-cache');
//		$x = $this->cache->get("acl-cache");
//		
//		if ($products === null){
//			$this->flashSession->success('Se ha borrado el caché');
//			return $this->response->redirect("error/index");
//		}
//		
//		$this->view->setVar('map', $products);
//		$this->view->setVar('map2', $x);
//		
//		
//		$this->cache->queryKeys("acl-cache");
//		// Delete all items from the cache
//		$keys = $this->cache->queryKeys();
//		foreach ($keys as $key) {
//			$ok = $this->cache->delete($key);
//		}
//		if ($ok) {
//			$lala = 'ok se borró el caché';
//			$this->view->setVar('map2', $lala);
//		}
    }
}