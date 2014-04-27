<?php

class HtmlFormBlock
{
	public static function createBlock($type)
	{
		switch ($type) {
			case 'Text':
			case 'Email':
				$block = new BlockTextForm();
				break;
			case 'TextArea':
				$block = new BlockTextAreaForm();
				break;
			case 'Select':
				$block = new BlockSelectForm();
				break;
			case 'MultiSelect':
				$block = new BlockMultiSelectForm();
				break;
			case 'Date':
				$block = new BlockDateForm();
				break;
		}
		
		return $block;
	}
}

?>
