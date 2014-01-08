<?php
class BlockCompound extends HtmlAbstract
{
	public function assignContent($data)
	{
		foreach ($data->contentData as $key => $value) {
			
			if($key == 'image') {
				$block1 = new BlockImageOnly();
				
				$obj = new stdClass();
				$obj->contentData = $data->contentData->$key;
				$obj->width = $data->width;
				$obj->height = $data->height;
				$obj->align = $data->align;
				$obj->vertalign = $data->vertalign;
				$obj->imglink = $data->imglink;
				
				$block1->assignContent($obj);
				
			}
			else if($key == 'text') {
				$block2 = new BlockTextOnly();
				
				$obj = new stdClass();
				$obj->contentData = $data->contentData->$key;
				
				$block2->assignContent($obj);
			}
		}
		
		if($data->type == 'module-image-text') {
			$this->children[] = $block1;
			$this->children[] = $block2;
			
		}
		else {
			$this->children[] = $block2;
			$this->children[] = $block1;
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