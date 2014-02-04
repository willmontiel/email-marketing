<?php
class HtmlRow extends HtmlAbstract
{
	public function assignContent($row) {
		
		$this->background_color = $row->background_color;
		$this->border_width = $row->border_width;
		$this->border_color = $row->border_color;
		$this->border_style = $row->border_style;
		$this->corner_top_left = $row->corner_top_left;
		$this->corner_top_right = $row->corner_top_right;
		$this->corner_bottom_left = $row->corner_bottom_left;
		$this->corner_bottom_right = $row->corner_bottom_right;
		$this->margin_top = $row->margin_top;
		$this->margin_bottom = $row->margin_bottom;
		$this->margin_left = $row->margin_left;
		$this->margin_right = $row->margin_right;
		
		foreach ($row->content as $content) {
			$content->amount = $row->amount;
			$block = HtmlBlockObj::createBlock($content->type);
			$block->assignContent($content);
			$this->children[] = $block->render();
		}
	}
	
	public function renderObjPrefix() {
		return '<td style="padding-left: ' . $this->margin_left . 'px; padding-right: ' . $this->margin_right . 'px;"><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; width:100%; border-spacing: 0px" cellpadding="0" width="100%"><tr>';
	}

	public function renderChildPrefix($i) {
		return '';
	}
	
	public function renderChildPostfix($i) {
		return '';
	}

	public function renderObjPostfix() {
		return '</tr></table></td>';
	}	
}