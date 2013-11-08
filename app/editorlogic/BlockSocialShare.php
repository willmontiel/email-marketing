<?php
class BlockSocialShare extends HtmlAbstract
{
	public function assignContent($data)
	{
		foreach ($data->contentData as $content) {
			if($content->selected) {
				$this->children[] = '<a href=" '. $content->url .'"><img src=""><label>' . $content->linktext . '</label></a>';
			}
		}

	}
	public function renderObjPrefix()
	{
		return '<td><table><tbody><tr>';
	}
	public function renderChildPrefix($i)
	{
		return '<td>';
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
