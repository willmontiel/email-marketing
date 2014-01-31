<?php
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
//				Phalcon\DI::getDefault()->get('logger')->log(print_r($content, true));
				$domain = Urldomain::findFirstByIdUrlDomain(1);
				switch ($content->socialname) {
					case 'Facebook':
						$this->children[] = '<a href="https://facebook.com/sharer/sharer.php?u=http%3A%2F%2Fgoogle.com%2F&display=popup"><img src="' . $domain->imageUrl . '/images/share_facebook_image_' . $data->size .'.png" style="margin-right: 8px;"></a>';
						break;
					case 'Twitter':
						$this->children[] = '<a href="https://twitter.com/intent/tweet?text=AQUI VA EL TEXTO%3A%20http%3A%2F%2Furl.com%2Frestourl&source=webclient"><img src="' . $domain->imageUrl . '/images/share_twitter_image_' . $data->size .'.png" style="margin-right: 8px;"></a>';
						break;
					case 'LinkedIn':
						$this->children[] = '<a href="https://linkedin.com/cws/share?url=http%3A%2F%2Fgoogle.com"><img src="' . $domain->imageUrl . '/images/share_linkedin_image_' . $data->size .'.png" style="margin-right: 8px;"></a>';
						break;
					case 'Google Plus':
						$this->children[] = '<a href="https://plus.google.com/share?url=http%3A%2F%2Fexample.com"><img src="' . $domain->imageUrl . '/images/share_google_plus_image_' . $data->size .'.png" style="margin-right: 8px;"></a>';
						break;
				}				
			}
		}
	}
	public function renderObjPrefix()
	{
		return '<td style="width: ' . $this->column_width . '%;" width="' . $this->column_width . '%"><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; margin-left: ' . $this->margin_left . 'px; margin-right: ' . $this->margin_right . 'px; width: 100%;" cellpadding="0" width="100%"><tbody><tr><td style="text-align: ' . $this->align . ';">';
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
