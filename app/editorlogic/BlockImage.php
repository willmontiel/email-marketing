<?php
class BlockImage extends HtmlAbstract
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
		$this->column_width = 100/$data->amount;
		
		$src = $data->imgsrc;
		$alt = $data->imgalt;
		$link = (isset($data->imglink)) ? trim($data->imglink) : '';
		
		$this->align = $data->align;
		$height = $data->height;
		$this->width = $data->width;
		$this->vertalign = $data->vertalign;
		$maxwidth = ($data->widthval/$data->amount) - ( $data->margin_left + $data->margin_right + $data->border_width*2 );
		$imgwidth = ( $maxwidth > $data->width) ? $data->width : $maxwidth;
		$imgwidth = ($imgwidth < 0) ? 0 : $imgwidth;
				
		$image = '<img src="' . $src . '" alt="' . $alt . '" style="height: ' . $height . 'px; width: ' . $imgwidth . 'px;" height="' . $height . '" width="' . $imgwidth . '">';
		
		$this->children[] = (!empty($link)) ? '<a href= "http://' . $link . '">' . $image . '</a>' : $image;;
	}
	
	public function renderObjPrefix()
	{
		return '<td style="width: ' . $this->column_width . '%; vertical-align: ' . $this->vertalign . '; padding-left: ' . $this->margin_left . 'px; padding-right: ' . $this->margin_right . 'px;" width="' . $this->column_width . '%"><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; width: 100%; border-spacing: 0px;" cellpadding="0" width="100%"><tr><td align="' . $this->align . '" style="width: ' . $this->width . 'px;" width="' . $this->width . 'px">';
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
