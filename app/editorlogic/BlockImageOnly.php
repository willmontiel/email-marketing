<?php
class BlockImageOnly extends HtmlAbstract
{
	public function assignContent($data)
	{
		$imgTag = new DOMDocument();
		$imgTag->loadHTML($data->contentData);
		
		$src = $imgTag->getElementsByTagName('img')->item(0)->getAttribute('src');
		$alt = $imgTag->getElementsByTagName('img')->item(0)->getAttribute('alt');
		
		$this->align = str_replace('pull-', '', $data->align);
		$height = $data->height;
		$width = $data->width;
		
		$this->children[] ='<img src="' . $src . '" alt="' . $alt . '" style="height: ' . $height . 'px; width: ' . $width . 'px;" height="' . $height . '" width="' . $width . '">';
	}
	
	public function renderObjPrefix()
	{
		return '<td align="' . $this->align . '">';
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
