<?php
class VisualElements extends Phalcon\Mvc\User\Component
{	
	private $_menu = array (
		"Dashboard" => array(
			"controller" => array("index"),
			"class" => "",
			"url" => "",
			"title" => "Dashboard",
			"icon" => "icon-dashboard"
		),
		"Contactos" => array(
			"controller" => array("contactlist", "dbase"),
			"class" => "",
			"url" => "contactlist#/lists",
			"title" => "Contactos",
			"icon" => "icon-user"
		),
		"Campanas" => array(
			"controller" => array(""),
			"class" => "",
			"url" => "",
			"title" => "Campañas",
			"icon" => "icon-envelope"
		),
		"Autorespuestas" => array(
			"controller" => array(""),
			"class" => "",
			"url" => "",
			"title" => "Autorespuestas",
			"icon" => "icon-edit"
		),
		"Estadisticas" => array(
			"controller" => array(""),
			"class" => "",
			"url" => "",
			"title" => "Estadísticas",
			"icon" => "icon-bar-chart"
		),
		"Herramientas" => array(
			"controller" => array("account", "user"),
			"class" => "",
			"url" => "",
			"title" => "Herramientas",
			"icon" => "icon-cog"
		)
	);
	
	public function getMenu()
	{
		$log = $this->logger;
		$controller = $this->view->getControllerName();
		$action = $this->view->getActionName();
		
		foreach ($this->_menu as $caption => $option) {
			foreach($option['controller'] as $c){
				if ($c == $controller) {
					$this->_menu[$caption]['class'] = "active";
				}
			}
        }
		return $this->_menu;
	}
}
