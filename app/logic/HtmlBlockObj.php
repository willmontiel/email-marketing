<?php
class HtmlBlockObj extends HtmlAbstract
{	
	public static function createBlock($data)
	{
		switch ($data->type) {
			case 'module-text-only':
				$block = self::createTextBlock($data->contentData->text);
				break;
			case 'module-image-text':
				$t = self::createImageTextBlock($data->contentData->image);
				$i = self::createTextOnlyBlock($data->contentData->text);
				$block = $i . $t;
				break;
			case 'module-separator':
				$block = self::createSeparator();
				break;
			case 'module-text-image':
				$t = self::createImageTextBlock($data->contentData->image);
				$i = self::createTextOnlyBlock($data->contentData->text);
				$block = $t . $i;
				break;
		}
		
		return $block;
	}
	
	public static function componentBlock($t, $i)
	{
		$block = $t . $i; 
	}
	
	public static function createTextBlock($data)
	{
		$textOnly = '<td>';
		$textOnly .= $data;
		$textOnly .= '</td>';
		
		return $textOnly;
	}
	
	public static function createImageBlock($data)
	{
		$buscar = array('data-toggle="modal" href="#images" class="media-object"');
		$reemplazar = array ('');
		
		$imageData = str_replace($buscar,$reemplazar, $data);
		
		$imageText = '<td>';
		$imageText .= $imageData;
		$imageText .= '</td>';
		
		return $imageText;
	}
	
	public static function createSeparator()
	{
		$separator = '<td><hr style="" /></td>';
		return $separator;
	}
}
