<?php

abstract class FormAbstract
{
//	abstract public function assignContent($content);
	
	public function render()
	{
		$htmlRendered = $this->renderObjPrefix();
		$i = 1;
		
		if(isset($this->children)) {
			
			foreach ($this->children as $child) {
				$htmlRendered .= $this->renderChildPrefix($i);

				if(is_object($child)) {

					$htmlRendered .= $child->render();
				}
				else {
					$htmlRendered .= $child;
				}

				$htmlRendered .= $this->renderChildPostfix($i);
				$i++;
			}
		}
		
		$htmlRendered .= $this->renderObjPostfix();
		
		return $htmlRendered;
	}
	
//	abstract public function renderObjPrefix();
//	abstract public function renderChildPrefix($i);
//	abstract public function renderChildPostfix($i);
//	abstract public function renderObjPostfix();
}

?>
