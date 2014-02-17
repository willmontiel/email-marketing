<?php
//	' . $data . ';'
class BlockButton extends HtmlAbstract
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
		
		$domain = Urldomain::findFirstByIdUrlDomain(1);

		$this->align = $data->align;
		
		$style = 'background-color:#' . $data->bgcolor . '; color:#' . $data->textcolor . '; display: inline-block; font-family:' . $data->fontfamily . '; font-size:' . $data->fontsize . 'px; font-weight:bold; line-height:' . $data->height . 'px; text-align:center; text-decoration:none; width:' . $data->width . 'px; border-radius:' . $data->radius . 'px; border:' . $data->borderwidth . 'px ' . $data->borderstyle . ' ' . $data->bordercolor . '; ';
		
		if($data->withbgimage == 1) {
			$style.= 'background-image:url(' . $domain->imageUrl . '/images/btn-' . $data->bgimage . '.png);';
		}
		
		$this->children[] ='<a href="http://' . $data->link . '" style="' . $style . '">' . $data->text . '</a>';
	}
	public function renderObjPrefix()
	{
		return '<td style="width: ' . $this->column_width . '%; padding-left: ' . $this->margin_left . 'px; padding-right: ' . $this->margin_right . 'px;" width="' . $this->column_width . '%"><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; width: 100%; border-spacing: 0px;" cellpadding="0" width="100%"><tr><td style="text-align: ' . $this->align . ';">';
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