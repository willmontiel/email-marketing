<?php
class HtmlZone extends HtmlAbstract
{
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	}
	
	public function assignData($content, $color, $width)
	{
		return $this->createZone($content, $color, $width);
	}
	
	public function createZone($zoneContent, $color, $width)
	{
		$zoneContent = '<table style="background-color: ' . $color . '; width:' . $width . '%; height: 100%;" width="' . $width . '%" height="100%"><tbody>';
		foreach ($zoneContent as $data) {
			$zoneContent .= '<tr>';
			HtmlBlockObj::createBlock($data);
			$zoneContent .= HtmlBlockObj::componentBlock();
			$zoneContent .= '</tr>';	
		}
		$zoneContent .= '</tbody></table>';	
		
		return $zoneContent;
	}
	
}
