<?php

require_once '../../../general/Links/ParametersEncoder.php';
use EmailMarketing\General\Links\ParametersEncoder;

class ParametersEncoderTest extends PHPUnit_Framework_TestCase 
{
	protected function setUp() 
	{
		$this->object = new ParametersEncoder();
	}
	
	public function testEncodeSimpleLink() {
		$uri = 'http://test.com/123/';
		
		$this->object->setBaseUri($uri);
		$link = $this->object->encodeLink('test/abc', array(1, 123, 456));
		$expected = $uri . 'test/abc/1-123-456';
		$expected .= md5($expected . '-Sigmamovil_Rules');
		$this->assertEquals($link, $expected, 'Generacion de enlace simple');
	}
	
	
}