<?php
class BlockImageOnly extends HtmlAbstract
{
	public function assignContent($data)
	{
		$imgTag = new DOMDocument();
		$imgTag->loadHTML($data->contentData);
		
		$src = $imgTag->getElementsByTagName('img')->item(0)->getAttribute('src');
		$alt = $imgTag->getElementsByTagName('img')->item(0)->getAttribute('alt');
		$link = trim($data->imglink);
		
		$this->align = str_replace('pull-', '', $data->align);
		$height = $data->height;
		$this->width = $data->width;
		$this->vertalign = $data->vertalign;
		
		$image = '<img src="' . $src . '" alt="' . $alt . '" style="height: ' . $height . 'px; width: ' . $this->width . 'px;" height="' . $height . '" width="' . $this->width . '">';
		
		$this->children[] = (!empty($link)) ? '<a href= "http://' . $link . '">' . $image . '</a>' : $image;;
	}
	
	public function renderObjPrefix()
	{
		return '<td align="' . $this->align . '" style="width: ' . $this->width . 'px; vertical-align: ' . $this->vertalign . ';" width="' . $this->width . 'px">';
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
