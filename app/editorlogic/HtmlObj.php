<?php
class HtmlObj extends HtmlAbstract
{	
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	}
	
	public function assignContent($content)
	{		
		$this->layout = $content->layout;
		$this->backgroundColor = isset($content->editorColor) ? $content->editorColor : '#ffffff';
		
		foreach ($content->dz as $key => $values) {
			$HtmlZone = new HtmlZone();
			$HtmlZone->assignContent($content->dz->$key);
			$this->children[] = $HtmlZone->render();
		}
	}
	
	public function renderObjPrefix()
	{
		return '<table style="background-color: '. $this->backgroundColor . '; width: 100%;"><tr><td style="padding: 20px;"><center><table style="width: 550px;" width="550px" cellspacing="0" cellpadding="0"><tbody>';
	}
	public function renderChildPrefix($i)
	{
		if(strpos($this->layout->name, 'sidebar')) {
			if($i == 4) {
				return '';
			}
			else if($i == 3) {
				return '<tr><td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tbody><tr>';
			}
		}
		elseif (strpos($this->layout->name, 'columns')) {
			if($i == 4) {
				return '<tr><td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tbody><tr>';
			}
			if($i == 5) {
				return '';
			}
			else if(strpos($this->layout->name, 'three') && $i == 6) {
				return '';
			}
		}
		return '<tr>';
	}
	public function renderChildPostfix($i)
	{
		if(strpos($this->layout->name, 'sidebar')) {
			if($i == 3) {
				return '';
			}
			else if($i == 4) {
				return '</tr></tbody></table></td></tr>';
			}
		}
		elseif (strpos($this->layout->name, 'columns')) {
			if($i == 4) {
				return '';
			}
			else if(strpos($this->layout->name, 'three') && $i == 5) {
				return '';
			}
			else if(strpos($this->layout->name, 'three') && $i == 6) {
				return '</tr></tbody></table></td></tr>';
			}
			else if(strpos($this->layout->name, 'two') && $i == 5) {
				return '</tr></tbody></table></td></tr>';
			}
		}
		return '</tr>';
	}
	public function renderObjPostfix()
	{
		return '</tbody></table></center></td></tr></table>';
	}
}
