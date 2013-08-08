<?php

/**
 * EmailElement (HTML5 email element)
 *
 * @author hectorlasso
 */

use Phalcon\Forms\Element;

class EmailElement extends Element 
{
	public function render($attributes = null)
	{
		$attributes = $this->prepareAttributes($attributes);
		
		return CustomTags::emailField($attributes);
	}
}
