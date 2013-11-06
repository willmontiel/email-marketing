<?php
class EditorObj
{	
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	}
	
	public function convertEditorObjToHtml($content)
	{
		$editorObj = json_decode($content);
		
		$zones = $editorObj->layout->zones;
		
		foreach ($zones as $zone) {
			switch ($zone->name) {
				case 'preheader':
					$preHeaderContent = $editorObj->dz->preheader->content[0];
					$preHeader = $this->createPreHeader($preHeaderContent->contentData, $editorObj->dz->preheader->color);
					break;
				case 'header':
					$headerContent = $editorObj->dz->header->content[0];
					$header = $this->createHeader($headerContent, $editorObj->dz->header->color);
					break;
			}
		}
		$this->log->log("Editor Obj: " . print_r($editorObj, true));
		$this->log->log("Preheader: " . $preHeader);
//		$this->log->log("---------------------------------------------------------------------------------");
//		$this->log->log("Preheader: " . print_r($editorObj->dz->preheader, true));
//		$this->log->log("---------------------------------------------------------------------------------");
//		$this->log->log("Preheader color : " . print_r($editorObj->dz->preheader->color, true));
//		$this->log->log("---------------------------------------------------------------------------------");
//		$this->log->log("Preheader content : " . print_r($editorObj->dz->preheader->content, true));
//		$this->log->log("---------------------------------------------------------------------------------");
//		$x = $editorObj->dz->preheader->content[0];
//		$this->log->log("Preheader into content : " . print_r($x, true));
//		$this->log->log("---------------------------------------------------------------------------------");
//		$this->log->log("Preheader into content : " . print_r($x->contentData, true));
	}
	
	protected function createPreHeader($preHeaderData, $color)
	{
		$preHeader = '<tr width="100%" style="background-color: ' . $color . ';"><td colspan="2">';
		$preHeader .= $preHeaderData;
		$preHeader .= '</td></tr>';
		
		return $preHeader;
	}
	
	protected function createHeader($headerData, $color)
	{
		$headerData
		return $header;
	}
}
