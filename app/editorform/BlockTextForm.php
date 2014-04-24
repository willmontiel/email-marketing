<?php

class BlockTextForm extends FormAbstract
{
	public function assignContent($data)
	{
		$this->children[] = '';
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
