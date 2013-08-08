<?php


/**
 * CustomTags for PhalconPHP
 *
 * @author hectorlasso
 */
class CustomTags extends \Phalcon\Tag
{
	/**
	 * Generates a widget to show a HTML5 input[type="email"] tag
	 *
	 * @param array
	 * @return string
	 */
	static public function emailField($parameters)
	{
		return self::_inputField('email', $parameters);   
	}
}
