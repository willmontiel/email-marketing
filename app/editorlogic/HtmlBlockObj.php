
<?php
class HtmlBlockObj
{	
	public static function createBlock($type)
	{
		switch ($type) {
			case 'Text':
				$block = new BlockText();
				break;
			case 'Image':
				$block = new BlockImage();
				break;
			case 'Separator':
				$block = new BlockSeparator();
				break;
			case 'Button':
				$block = new BlockButton();
				break;
			case 'Social-Follow':
				$block = new BlockSocialFollow();
				break;
			case 'Social-Share':
				$block = new BlockSocialShare();
				break;
		}
		
		return $block;
	}
}
