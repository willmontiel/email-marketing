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
		$zones = $content->layout->zones;

		$HtmlZone = new HtmlZone();
		
		foreach ($zones as $zone) {
			switch ($zone->name) {
				case 'preheader':
					$preheaderContent = $zones->dz->preheader->content;
					$color = $zones->dz->preheader->color;
					$this->preheader = $HtmlZone->assignData($preheaderContent, $color, '100');
					break;
				case 'header':
					$headerContent = $zones->dz->header->content;
					$color = $zones->dz->header->color;
					$this->header = $HtmlZone->assignData($headerContent, $color, '100');
					break;
				case 'body':
					$bodyContent = $zones->dz->body->content;
					$color = $zones->dz->body->color;
					$this->body = $HtmlZone->assignData($bodyContent, $color, '100');
					break;
				case 'column1':
					$column1Content = $zones->dz->column2->content;
					$color = $zones->dz->column1->color;
					$this->column1 = $HtmlZone->assignData($column1Content, $color, '100');
					break;
				case 'column2':
					$column2Content = $zones->dz->column2->content;
					$color = $zones->dz->column2->color;
					$this->column2 = $HtmlZone->assignData($column2Content, $color, '100');
					break;
				case 'column3':
					$column3Content = $zones->dz->column3->content;
					$color = $zones->dz->column3->color;
					$this->column3 = $HtmlZone->assignData($column3Content, $color, '100');
					break;
				case 'footer':
					$footerContent = $zones->dz->footer->content;
					$color = $zones->dz->footer->color;
					$this->footer = $HtmlZone->assignData($footerContent, $color, '100');
					break;
			}
		}
		
	}
	
	public function render()
	{
		
	}
}
