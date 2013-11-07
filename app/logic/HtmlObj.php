<?php
class HtmlObj
{	
	public function __construct() 
	{
		$this->log = new Phalcon\Logger\Adapter\File('../app/logs/debug.log');
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	}
	
	public function assignContent($content)
	{
		$editorObj = json_decode($content);
		
		$zones = $editorObj->layout->zones;
		
		foreach ($zones as $zone) {
			switch ($zone->name) {
//				case 'preheader':
//					$preHeaderContent = $editorObj->dz->preheader->content;
//					$preHeader = $this->createPreHeader($preHeaderContent->contentData, $editorObj->dz->preheader->color);
//					break;
//				case 'header':
//					$headerContent = $editorObj->dz->header->content;
//					$header = $this->createHeader($headerContent, $editorObj->dz->header->color);
//					break;
//				case 'body':
//					$bodyContent = $editorObj->dz->body->content;
//					$body = $this->createBody($headerContent, $editorObj->dz->body->color);
//					break;
				case 'footer':
					$footerContent = $editorObj->dz->footer->content;
					$footer = $this->createHeader($footerContent, $editorObj->dz->footer->color);
					break;
			}
		}
//		$this->log->log("Editor Obj: " . print_r($editorObj, true));
//		$this->log->log("Preheader: " . $preHeader);
//		$this->log->log("Header: " . $header);
		$this->log->log("Footer: " . $footer);
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
	
	private function convertUrlPrivateToPublic($imgUrl)
	{
		return $imgUrl;
	}

	private function excludeUnnecessaryHtml()
	{
		$buscar = array('data-toggle="modal" href="#images" class="media-object"', 'src="' . $imgUrl .'"');
		$reemplazar = array ('', 'src="' . $publicUrl .'"');
		
		$content = str_replace($buscar,$reemplazar, $contentData);
		
	}
	
	protected function createPreHeader($preHeaderData, $color)
	{
		$preHeader = '<table style="background-color: ' . $color . ';">';
		foreach ($preHeaderData as $data) {
			$preHeader .= '<tr>';
			foreach ($data->contentData as $content) {
				$preHeader .= '<td>';
				$preHeader .= $content;	
				$preHeader .= '</td>';
			}
			$preHeader .= '</td>';	
		}
		$preHeader .= '</table>';
		return $preHeader;
	}
	
	protected function createHeader($headerData, $color)
	{
		$header = '<table style="background-color: ' . $color . '; width: 100%; height: 100%;" width="100%" height="100%"><tbody>';
		foreach ($headerData as $data) {
			$header .= '<tr>';
			
			if (!isset($data->contentData) || $data->contentData == null || $data->contentData == '') {
					$header .= '<td style="width: 100%" width="100%"><hr /></td>';
					$header .= '<td style="width: 100%" width="100%"><hr /></td>';
			}
			else{
				foreach ($data->contentData as $content) {
					$header .= '<td>';
					if ($data->contentData->image == $content) {
						$header .= $content;
					}
					else if ($data->contentData->text == $content) {
						$header .= $content;
					}
					else {
						$header .= $content;
					}
					$header .= '</td>';
				}
			}
			$header .= '</tr>';	
		}
		$header .= '</tbody></table>';
		
//		$contentData = $headerData->contentData;
//		$imgUrl = $headerData->displayer->imagesrc;
		
//		$publicUrl = $this->convertUrlPrivateToPublic($imgUrl);

		
		return $header;
	}
	
	protected function createBody($bodyData, $color)
	{
		$contentData = $headerData->contentData;
		$imgUrl = $headerData->displayer->imagesrc;
	}




//	private function resizeImage($imgUrl, $newWidth, $newHeight)
//	{
//		$ext = pathinfo($imgUrl, PATHINFO_EXTENSION);
//		
//		switch ($ext) {
//			case 'jpg':
//			case 'jpeg':
//				$img = @imagecreatefromjpeg($imgUrl);
//				break;
//
//			case 'png':
//				$img = @imagecreatefrompng($imgUrl);
//				break;
//
//			case 'gif':
//				$img = @imagecreatefromgif($imgUrl);
//				break;
//		}
//
//		$width = imagesx($img);
//		$height =imagesy($img);
//		
//		$image = imagecreatetruecolor($newWidth ,$newHeight);
//		
//		ImageCopyResized($image, $img, $thumbnailInfo['x'], $thumbnailInfo['y'], 0, 0, $thumbnailInfo['newWidth'], $thumbnailInfo['newHeight'], $width, $height);
//
//		if (!imagepng ($thumb, $dir)) {
//			throw new InvalidArgumentException('thumb could not be saved on the server');
//		}	
//	}
}
