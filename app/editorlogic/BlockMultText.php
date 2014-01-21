<?php
class BlockMultText extends HtmlAbstract
{
	public function assignContent($data)
	{
		
		foreach ($data->contentData as $key => $value) {
			$block = new BlockTextOnly();
			$obj = new stdClass();
			$obj->contentData = $value;
			$block->assignContent($obj);
			$this->children[] = $block;
		}
	}
	
	public function renderObjPrefix()
	{
		return '<td><table style="width: 100%; border-collapse: collapse; table-layout: fixed;"><tr>';
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
		return '</tr></table></td>';
	}
}
