<?php
class HtmlZone extends HtmlAbstract
{
	public $color;
	public $width;

	public function assignContent($zone)
	{
		$this->color = $zone->color;
		$this->width = $zone->width;
		
		foreach ($zone->content as $content) {
			$block = HtmlBlockObj::createBlock($content->type);
			$block->assignContent($content);
			$this->children[] = $block->render();
		}
	}
	
	public function renderObjPrefix()
	{
		switch ($this->width) {
			case 'full-width':
				$width = '100%';
				break;
			case 'half-width':
				$width = '50%';
				break;
			case 'third-width':
				$width = '33.5%';
				break;
			case 'twothird-width':
				$width = '66.5%';
				break;
		}
		
		return '<td style="width: ' . $width . ';"><table style="background-color: ' . $this->color . '; width: 100%"><tbody>';
	}
	public function renderChildPrefix($i)
	{
		return '<tr>';
	}
	public function renderChildPostfix($i)
	{
		return '</tr>';
	}
	public function renderObjPostfix()
	{
		return '</tbody></table></td>';
	}
	
}
