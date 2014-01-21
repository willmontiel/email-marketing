
<?php
class HtmlBlockObj
{	
	public static function createBlock($type)
	{
		switch ($type) {
			case 'module-text-only':
				$block = new BlockTextOnly();
				break;
			case 'module-text-boxed':
				$block = new BlockTextBoxed();
				break;
			case 'module-image-only':
				$block = new BlockImageOnly();
				break;
			case 'module-image-text':
				$block = new BlockCompound();
				break;
			case 'module-text-image':
				$block = new BlockCompound();
				break;
			case 'module-separator':
				$block = new BlockSeparator();
				break;
			case 'module-button':
				$block = new BlockButton();
				break;
			case 'module-social-follow':
				$block = new BlockSocialFollow();
				break;
			case 'module-social-share':
				$block = new BlockSocialShare();
				break;
			case 'module-text-mult':
				$block = new BlockMultText();
				break;
		}
		
		return $block;
	}
}
