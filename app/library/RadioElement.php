<?php

/**
 * RadioElement (HTML5 radio element)
 *
 * @author hectorlasso
 */

use Phalcon\Forms\Element;

class RadioElement extends Element 
{
	public function render($attributes = null)
	{
		$attributes = $this->prepareAttributes($attributes);
		
		return CustomTags::radioField($attributes);
	}
}
