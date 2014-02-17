<?php
class PrepareMailContent
{
	protected $linkService;
	protected $imageService;

	public function __construct(LinkService $linkService, ImageService $imageService) 
	{
		$this->linkService = $linkService;
		$this->imageService = $imageService;
	}
	
	public function processContent($html)
	{
		if (trim($html) === '') {
			throw new \InvalidArgumentException("Error mail's content is empty");
		}
		
		$htmlObj = new DOMDocument();
		@$htmlObj->loadHTML($html);
		
		$images = $htmlObj->getElementsByTagName('img');
		$links = $htmlObj->getElementsByTagName('a');
		
		if ($images->length !== 0) {
			foreach ($images as $image) {
				$imageSrc = $image->getAttribute('src');
				$newSrc = $this->imageService->transformImageUrl($imageSrc);
				$image->setAttribute('src', $newSrc);
			}
		}
		
		if ($links->length !== 0) {
			foreach ($links as $link) {
				$linkHref = $link->getAttribute('href');
				echo 'This is the link'. $linkHref . PHP_EOL;
				$mark = $this->linkService->getPlatformUrl($linkHref);
				echo 'This is the mark'. $mark . PHP_EOL;
				if ($mark) {
					$link->setAttribute('href', $mark);
				}
			}
			$marks = $this->linkService->getUrlMappings();
		}

		$html = $htmlObj->saveHTML();
		
		$html1 = str_replace('%24%24%24', '$$$', $html);
		$html2 = str_ireplace('</body>', '$$$_open_track_$$$</body>', $html1);
		$result[] = $html2;
		$result[] = $marks;
//		
/*		return  array('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">'
				      . PHP_EOL . 
				      '<html><head></head><body><img src="http://stage.sigmamovil.com/assets/2/images/123.jpg"></body></html>'
					  . PHP_EOL);
*/		
		return $result;
	}
}