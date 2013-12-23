<?php
class BlockTextOnly extends HtmlAbstract
{
	public function assignContent($data)
	{
		$this->children[] = $data->contentData;
	}
	
	public function renderObjPrefix()
	{
		return '<td style="word-break: break-word; padding: 15px 15px;">';
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