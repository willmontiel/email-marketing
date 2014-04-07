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
		
		$style = 'background-color:' . $data->bgcolor . '; color:' . $data->textcolor . '; display: inline-block; font-family:' . $data->fontfamily . '; font-size:' . $data->fontsize . 'px; font-weight:bold; padding-top:10px; padding-bottom:10px; padding-left: 10px; padding-right: 10px; text-align:center; height:' . $data->height . 'px; width:' . $data->width . 'px; border-top-left-radius: ' . $data->radius . 'px; border-top-right-radius: ' . $data->radius . 'px; border-bottom-right-radius: ' . $data->radius . 'px; border-bottom-left-radius: ' . $data->radius . 'px; border:' . $data->borderwidth . 'px ' . $data->borderstyle . ' ' . $data->bordercolor . '; text-decoration: none; -webkit-text-size-adjust:none; mso-hide:all; ';
		
		if($data->withbgimage == 1) {
			$style.= 'background-image:url(' . $domain->imageUrl . '/images/btn-' . $data->bgimage . '.png);';
		}
		
		$mso = '<!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://' . $data->link . '" style="v-text-anchor:middle; height:' . ($data->height+20+($data->borderwidth*2)) . 'px; width:' . ($data->width+20+($data->borderwidth*2)) . 'px;" arcsize="' . round( $data->radius / 180 * 100 ) . '%" strokecolor="' . $data->bordercolor . '" strokeweight="' . $data->borderwidth . 'px" fillcolor="' . $data->bgcolor . '"><w:anchorlock/><center style="color:' . $data->textcolor . ';font-family:' . $data->fontfamily . ';font-size:' . $data->fontsize . 'px; font-weight:bold; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px;">' . $data->text . '</center></v:roundrect><![endif]-->';
		
		$this->children[] ='<div>' . $mso . '<a href="http://' . $data->link . '" style="' . $style . '">' . $data->text . '</a></div>';
	}
	public function renderObjPrefix()
	{
		return '<td style="width: ' . $this->column_width . '%; padding-left: ' . $this->margin_left . 'px; padding-right: ' . $this->margin_right . 'px;" width="' . $this->column_width . '%"><table style="border-color: ' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: ' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; width: 100%; border-spacing: 0px;" cellpadding="0" width="100%"><tr><td style="text-align: ' . $this->align . ';">';
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