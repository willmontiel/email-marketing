<?php
//	' . $data . ';'
class BlockButton extends HtmlAbstract
{
	public function assignContent($data)
	{
		$url = Phalcon\DI::getDefault()->get('url');
		
		$domain = Urldomain::findFirstByIdUrlDomain(1);

		$this->align = $data->align;
		
		$style = 'background-color:' . $data->bgcolor . '; color:' . $data->textcolor . '; display: inline-block; font-family:' . $data->fontfamily . '; font-size:' . $data->fontsize . 'px; font-weight:bold; line-height:' . $data->height . 'px; text-align:center; text-decoration:none; margin: 10; width:' . $data->width . 'px; ';
		
		if($data->withborderradius == 1) {
			$style.= 'border-radius:' . $data->radius . 'px;';
		}
		
		if($data->withbordercolor == 1) {
			$style.= 'border:1px solid ' . $data->bordercolor . ';';
		}
		
		if($data->withbgimage) {
			$style.= 'background-image:url(' . $domain->imageUrl . '/images/btn-' . $data->bgimage . '.png);';
		}
		
		$this->children[] ='<a href="http://' . $data->link . '" style="' . $style . '">' . $data->text . '</a>';
	}
	public function renderObjPrefix()
	{
		return '<td style="text-align: ' . $this->align . ';">';
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
		return '</td>';
	}
}