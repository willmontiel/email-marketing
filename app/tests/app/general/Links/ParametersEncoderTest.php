<?php

namespace EmailMarketing\General\Links;


/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-03-04 at 16:23:58.
 */
class ParametersEncoderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ParametersEncoder
	 */
	protected $object;


	protected function setUp() 
	{
		$this->object = new ParametersEncoder();
	}
	
	public function testEncodeSimpleLink() {
		$uri = 'http://test.com/123/';
		
		$this->object->setBaseUri($uri);
		$link = $this->object->encodeLink('test/abc', array(1, 123, 456));
		$expected = $uri . 'test/abc/1-123-456';
		$expected .= '-' . md5($expected . '-Sigmamovil_Rules');
		$this->assertEquals($expected, $link, 'Generacion de enlace simple');
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testDecodeFails()
	{
		$str = '1-123-456-INAVLID';
		
		$this->object->setBaseUri('http://test.com/123/');
		
		$this->object->decodeLink('test/abc', $str);

	}
	
	public function testDecodeLink()
	{
		$parameters = array(
			1,
			123,
			456
		);
		$action = 'test/abc';
		$uri = 'http://abc.com/123/';
		
		$this->object->setBaseUri($uri);
		$encoded = $this->object->encodeLink($action, $parameters);
		
		$paramstr = str_replace($uri . $action . '/', '', $encoded);
		
		$result = $this->object->decodeLink($action, $paramstr);
		
		$this->assertEquals($parameters, $result);
		
	}
	
}