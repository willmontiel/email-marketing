<?php
class BlockSeparator extends HtmlAbstract
{
	public function assignContent($data)
	{
		$this->children[] ='<hr />';
	}
	public function renderObjPrefix()
	{
		return '<td>';
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
