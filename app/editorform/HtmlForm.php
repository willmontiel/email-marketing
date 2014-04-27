<?php

class HtmlForm extends FormAbstract
{
	public function assignContent($content)
	{		
		$this->title = $content->title;
		$this->button = $content->button;
		
		foreach ($content->content as $element) {
			$block = HtmlFormBlock::createBlock($element->type);
			$block->assignContent($content);
			$this->children[] = $block->render();
		}
	}
	
	public function renderObjPrefix()
	{
		return '';
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
		return '';
	}
}

?>
