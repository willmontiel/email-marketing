<?php
class VisualElements extends Phalcon\Mvc\User\Component implements Iterator
{	
	protected $controller;
	
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
	
	public function __construct() 
	{
		$this->controller =  $this->view->getControllerName();
	}
	
	
	public function get() 
	{
		return $this;
	}
	
	public function rewind()
    {
		 reset($this->_menu);
    }

    public function current()
    {
        $obj = new stdClass();
		
		$curr = current($this->_menu);
		
		$obj->title = $curr['title'];
		$obj->icon = $curr['icon'];
		$obj->url = $curr['url'];
		$obj->class = '';
		
		if (in_array($this->controller, $curr['controller'])) {
			$obj->class = 'active';
		}
		
        return $obj;
    }

    public function key()
    {
        return key($this->_menu);
    }

    public function next()
    {
        $var = next($this->_menu);
    }

    public function valid()
    {
        $key = key($this->_menu);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}
