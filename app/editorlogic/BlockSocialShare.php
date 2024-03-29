<?php
//Phalcon\DI::getDefault()->get('logger')->log(print_r($content, true));
class BlockSocialShare extends HtmlAbstract
{
	public function assignContent($data)
	{
		$this->align = $data->align;
		
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
		
		foreach ($data->contentData as $content) {
			if($content->selected) {
				try {
					$domain = Urldomain::findFirstByIdUrlDomain(1);
					$lowersocialname = strtolower($content->socialname);
					$this->children[] = '<a href="$$$_social_media_share_$$$' . str_replace(" ", "", $lowersocialname) . '"><img src="' . $domain->imageUrl . '/images/socials/share/theme_' . $data->theme . '/share_' . str_replace(" ", "_", $lowersocialname) . '_image_' . $data->size .'.png" style="margin-right: 8px;"></a>';
				} catch(InvalidArgumentException $e){
					Phalcon\DI::getDefault()->get('logger')->log('Invalid Argument Exception: [' . $e . ']');
				}
			}
		}
	}
	public function renderObjPrefix()
	{
		return '<td style="width: ' . $this->column_width . '%;  padding-left: ' . $this->margin_left . 'px; padding-right: ' . $this->margin_right . 'px;" width="' . $this->column_width . '%"><table style="border-color: ' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: ' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; width: 100%; border-spacing: 0px;" cellpadding="0" width="100%"><tbody><tr><td style="text-align: ' . $this->align . '; width: 100%;">';
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
		return '</td></tr></tbody></table></td>';
	}
}
