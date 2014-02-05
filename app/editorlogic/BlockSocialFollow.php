<?php
class BlockSocialFollow extends HtmlAbstract
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
				$domain = Urldomain::findFirstByIdUrlDomain(1);
				switch ($content->socialname) {
					case 'Facebook':
						$this->children[] = '<a href="https://www.facebook.com"><img src="' . $domain->imageUrl . '/images/follow_facebook_image.png"><br><label>' . $content->text . '</label></a>';
						break;
					case 'Twitter':
						$this->children[] = '<a href="https://twitter.com"><img src="' . $domain->imageUrl . '/images/follow_twitter_image.png"><br><label>' . $content->text . '</label></a>';
						break;
					case 'LinkedIn':
						$this->children[] = '<a href="https://linkedin.com"><img src="' . $domain->imageUrl . '/images/follow_linkedin_image.png"><br><label>' . $content->text . '</label></a>';
						break;
					case 'Google Plus':
						$this->children[] = '<a href="https://plus.google.com"><img src="' . $domain->imageUrl . '/images/follow_google_plus_image.png"><br><label>' . $content->text . '</label></a>';
						break;
				}	
			}
		}
	}
	public function renderObjPrefix()
	{
		return '<td style="width: ' . $this->column_width . '%; padding-left: ' . $this->margin_left . 'px; padding-right: ' . $this->margin_right . 'px;" width="' . $this->column_width . '%" align="' . $this->align . '"><table style="border-color: #' . $this->border_color . '; border-style: ' . $this->border_style . '; border-width: ' . $this->border_width . 'px; background-color: #' . $this->background_color . '; border-top-left-radius: ' . $this->corner_top_left . 'px; border-top-right-radius: ' . $this->corner_top_right . 'px; border-bottom-right-radius: ' . $this->corner_bottom_right . 'px; border-bottom-left-radius: ' . $this->corner_bottom_left . 'px; margin-top: ' . $this->margin_top . 'px; margin-bottom: ' . $this->margin_bottom . 'px; font-family: Helvetica, Arial, sans-serif;" cellpadding="0"><tbody><tr>';
	}
	public function renderChildPrefix($i)
	{
		return '<td style="text-align: center;">';
	}
	public function renderChildPostfix($i)
	{
		return '</td>';
	}
	public function renderObjPostfix()
	{
		return '</tr></tbody></table></td>';
	}
}