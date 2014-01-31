<?php
class HtmlZone extends HtmlAbstract
{
	public $color;
	public $width;

	public function assignContent($zone)
	{
		$this->background_color = $zone->background_color;
		$this->border_width = $zone->border_width;
		$this->border_color = $zone->border_color;
		$this->border_style = $zone->border_style;
		$this->corner_top_left = $zone->corner_top_left;
		$this->corner_top_right = $zone->corner_top_right;
		$this->corner_bottom_left = $zone->corner_bottom_left;
		$this->corner_bottom_right = $zone->corner_bottom_right;
		$this->width = $zone->width;
		
		foreach ($zone->content as $content) {
			$row = new HtmlRow();
			$row->assignContent($content);
			$this->children[] = $row->render();
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
		
		return '<td style="width: ' . $width . '; vertical-align: top; padding:5px;"><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; table-layout: fixed; width:100%;" Width="100%" cellpadding="0"><tbody>';
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
