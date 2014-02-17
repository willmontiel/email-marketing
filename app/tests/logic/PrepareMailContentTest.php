<?php
require_once '../../logic/PrepareMailContent.php';

class PrepareMailContentTest extends PHPUnit_Framework_TestCase
{
    protected $object;

	public function __construct() {

	}
    protected function setUp()
    {
    }
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testEmptyHtmlContentNullIsNotAllow()
	{
		$lnkMockup = $this->getMock('LinkService');
		$imgMockup = $this->getMock('ImageService');
		
		$obj = new PrepareMailContent($lnkMockup, $imgMockup);
		$obj->processContent(null);
	}
	
	public function testImageUrlReplace()
	{
		$htmlOrigen = '<html><head></head><body><img src="http://stage.sigmamovil.com/asset/show/123"/><a href="http://stage.sigmamovil.com/service/calendar.html">Link!</a></body></html>';
		$htmlFinal  = $this->wrap_html_dom(
					  '<html><head></head><body><img src="http://stage.sigmamovil.com/assets/2/images/123.jpg"><a href="$$$10_sigma_url_$$$">Link!</a>$$$_open_track_$$$</body></html>'
		);
		
		$array = array('$$$10_sigma_url_$$$' => 'http://stage.sigmamovil.com/track/click/1-10');
		
		$lnkMockup = $this->getMock('LinkService', array('getPlatformUrl', 'getUrlMappings'));
		$imgMockup = $this->getMock('ImageService', array('transformImageUrl'));
		
		$lnkMockup->expects($this->any())
				  ->method('getPlatformUrl')
				  ->will($this->returnValue('$$$10_sigma_url_$$$'));
		
		$lnkMockup->expects($this->any())
				  ->method('getUrlMappings')
				  ->will($this->returnValue($array));
		
		$imgMockup->expects($this->any())
				  ->method('transformImageUrl')
				  ->will($this->returnValue('http://stage.sigmamovil.com/assets/2/images/123.jpg'));
		
		$obj = new PrepareMailContent($lnkMockup, $imgMockup);
		$result = $obj->processContent($htmlOrigen);
		
		$this->assertEquals($htmlFinal, $result[0], 'Reemplazo basico de imagen');
		$this->assertEquals($array, $result[1], 'Links acoplados');
	}
	
	public function testMultipleImageUrlReplace()
	{
		$htmlOrigen = '<html><head></head><body><img src="http://stage.sigmamovil.com/asset/show/123"/><img src="http://stage.sigmamovil.com/asset/show/124"/><img src="http://stage.sigmamovil.com/asset/show/125"/><a href="http://stage.sigmamovil.com/service/calendar1.html">Link1!</a><a href="http://stage.sigmamovil.com/service/calendar2.html">Link2!</a><a href="http://stage.sigmamovil.com/service/calendar3.html">Link3!</a><a href="http://stage.sigmamovil.com/service/calendar4.html">Link4!</a><a href="http://www.facebook.com/hondafanpage">Face!</a></body></html>';
		$htmlFinal  = $this->wrap_html_dom(
					  '<html><head></head><body><img src="http://stage.sigmamovil.com/assets/2/images/123.jpg"><img src="http://stage.sigmamovil.com/assets/2/images/124.jpg"><img src="http://stage.sigmamovil.com/assets/2/images/125.jpg"><a href="$$$10_sigma_url_$$$">Link1!</a><a href="$$$11_sigma_url_$$$">Link2!</a><a href="$$$12_sigma_url_$$$">Link3!</a><a href="$$$13_sigma_url_$$$">Link4!</a><a href="http://www.facebook.com/hondafanpage">Face!</a>$$$_open_track_$$$</body></html>'
		);
		
		$array = array('$$$10_sigma_url_$$$' => 'http://stage.sigmamovil.com/track/click/1-10',
					   '$$$11_sigma_url_$$$' => 'http://stage.sigmamovil.com/track/click/1-11',
					   '$$$12_sigma_url_$$$' => 'http://stage.sigmamovil.com/track/click/1-12',
			           '$$$13_sigma_url_$$$' => 'http://stage.sigmamovil.com/track/click/1-13');
		
		$lnkMockup = $this->getMock('LinkService', array('getPlatformUrl', 'getUrlMappings'));
		$imgMockup = $this->getMock('ImageService', array('transformImageUrl'));
		
		$lnkMockup->expects($this->any())
				  ->method('getPlatformUrl')
				  ->will($this->onConsecutiveCalls('$$$10_sigma_url_$$$', '$$$11_sigma_url_$$$', '$$$12_sigma_url_$$$', '$$$13_sigma_url_$$$'));
		
		$lnkMockup->expects($this->any())
				  ->method('getUrlMappings')
				  ->will($this->returnValue($array));
		
		$imgMockup->expects($this->any())
				  ->method('transformImageUrl')
				  ->will($this->onConsecutiveCalls('http://stage.sigmamovil.com/assets/2/images/123.jpg', 'http://stage.sigmamovil.com/assets/2/images/124.jpg', 'http://stage.sigmamovil.com/assets/2/images/125.jpg'));
		
		$obj = new PrepareMailContent($lnkMockup, $imgMockup);
		$result = $obj->processContent($htmlOrigen);
		
		$this->assertEquals($htmlFinal, $result[0], 'Reemplazo de multiples imagénes y links');
		$this->assertEquals($array, $result[1], 'Links acoplados');
	}
	
	public function testNoImageUrlReplace()
	{
		$htmlOrigen = '<html><head></head><body></body></html>';
		$htmlFinal  = $this->wrap_html_dom(
					  '<html><head></head><body>$$$_open_track_$$$</body></html>'
		);
		
		$lnkMockup = $this->getMock('LinkService');
		$imgMockup = $this->getMock('ImageService');
		
		$obj = new PrepareMailContent($lnkMockup, $imgMockup);
		$result = $obj->processContent($htmlOrigen);
		
		$this->assertEquals($htmlFinal, $result[0], 'Prueba de contenido sin links y sin imagénes');
		$this->assertEquals(null, $result[1], 'Links acoplados');
	}
	
	protected function strip_wrong_crlf($str)
	{
		return str_replace("\r", "", $str);
	}
	
	protected function wrap_html_dom($str)
	{
		return $this->strip_wrong_crlf('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">'
			. PHP_EOL . $str . PHP_EOL);
	}
}