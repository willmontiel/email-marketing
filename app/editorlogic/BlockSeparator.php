<?php
class BlockSeparator extends HtmlAbstract
{
	public function assignContent($data)
	{
		$this->background_color = $data->background_color;
		$this->border_width = $data->border_width;
		$this->border_color = $data->border_color;
		$this->border_style = $data->border_style;
		$this->corner_top_left = $data->corner_top_left;
		$this->corner_top_right = $data->corner_top_right;
		$this->corner_bottom_left = $data->corner_bottom_left;
		$this->corner_bottom_right = $data->corner_bottom_right;
		$this->margin_top = $data->margin_top;
		$this->margin_bottom = $data->margin_bottom;
		$this->margin_left = $data->margin_left;
		$this->margin_right = $data->margin_right;
		
		$this->children[] ='<hr />';
	}
	public function renderObjPrefix()
	{
		return '<td><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; margin-left: ' . $this->margin_left . 'px; margin-right: ' . $this->margin_right . 'px; width:100%;" cellpadding="0" width="100%"><tr><td>';
	}
	public function renderChildPrefix($i)
	{
		return '';
	}
	public function renderChildPostfix($i)
	{
		return '';
	}
	public function renderObjPostfix()
	{
		return '</td></tr></table></td>';
	}
}
