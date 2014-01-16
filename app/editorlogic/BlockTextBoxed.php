<?php
class BlockTextBoxed extends HtmlAbstract
{
	public function assignContent($data)
	{
		$this->bgcolor = $data->boxedcolor;
		$this->bordercolor = $data->boxedbrcolor;
		$this->borderstyle = $data->boxedbrstyle;
		$this->boderwidth = $data->boxedbrwidth;
		$this->borderradius = $data->boxedbrradius;
		
		$this->children[] = $data->contentData;
	}
	
	public function renderObjPrefix()
	{
		return '<td><table border="0" cellpadding="0" style="background-color: ' . $this->bgcolor . '; border-collapse: separate; width: 100%; border: ' . $this->boderwidth . 'px '. $this->borderstyle . ' ' . $this->bordercolor . '; border-top-left-radius: ' . $this->borderradius . 'px; border-top-right-radius: ' . $this->borderradius . 'px; border-bottom-right-radius: ' . $this->borderradius . 'px; border-bottom-left-radius: ' . $this->borderradius . 'px;"><tr><td style="word-break: break-word; padding: 15px 15px;">';
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