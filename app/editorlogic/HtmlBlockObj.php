
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
			case 'module-image-text':
				$block = new BlockCompound();
				break;
			case 'module-text-image':
				$block = new BlockCompound();
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
			case 'module-text-mult':
				$block = new BlockMultText();
				break;
		}
		
		return $block;
	}
}
