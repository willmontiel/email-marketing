<?php
class VisualElements extends Phalcon\Mvc\User\Component
{	
	private $_menu = array (
		"Dashboard" => array(
			"controller" => "index",
			"action" => "index",
			"class" => "",
			"url" => "",
			"title" => "Dashboard",
			"icon" => "icon-dashboard",
			"any" => true
		),
		"Contactos" => array(
			"controller" => "contactlist",
			"action" => "index",
			"class" => "",
			"url" => "contactlist#/lists",
			"title" => "Contactos",
			"icon" => "icon-user",
			"any" => true
		)
	);
	
	public function getMenu()
	{
		$log = $this->logger;
		$controller = $this->view->getControllerName();
		$action = $this->view->getActionName();
		
		foreach ($this->_menu as $caption => $option) {
            if ($option['controller'] == $controller && ($option['action'] == $action || $option['any'])) {
				$this->_menu[$caption]['class'] = "active";
            }
        }
		
		$log->log("este es el arreglo: ". print_r($this->_menu, true));
		return $this->_menu;
	}
}
