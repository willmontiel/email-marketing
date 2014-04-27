<?php

class FormCreator
{	
	function __construct() {
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
	}

	
	public function getHtmlForm(Form $form)
	{
		$jsoncontent = json_decode($form->content);
		$content = $jsoncontent->content;
		
		$htmlelements = array();
	
		foreach ($content as $element) {
			$block = array();
			$block['label'] = $this->getLabelElement($element);
			$block['hide'] = ($element->hide) ? 'field-element-form-hide' : '';
			switch ($element->type) {
				case 'Text':
				case 'Email':
					$block['field'] = $this->getTextElement($element);
					break;
				case 'TextArea':
					$block['field'] = $this->getTextAreaElement($element);
					break;
				case 'Select':
					$block['field'] = $this->getSelectElement($element);
					break;
				case 'MultiSelect':
					$block['field'] = $this->getMultiSelectElement($element);
					break;
				case 'Date':
					$block['field'] = $this->getDateElement($element);
					break;
			}
			$htmlelements['fields'][] = $block;
		}
		
		$htmlelements['title'] = $jsoncontent->title;
		$htmlelements['button'] = $jsoncontent->button;
		
		return $htmlelements;
	}
	
	
	protected function getLabelElement($element)
	{
		$label = ($element->required === 'Si') ? '<span class="required">* </span>' . $element->name : $element->name;
		return '<label for="c_' . $element->id . '">' . $label . '</label>';
	}
	
	protected function getTextElement($element)
	{
		$field = '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" placeholder="' . $element->placeholder . '">';
		if($element->hide) {
			$field = '<input type="text" class="form-control" value="' . $element->defaultvalue . '">';
		}
		
		return $field;
	}
	
	protected function getTextAreaElement($element)
	{
		$field = '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" placeholder="' . $element->placeholder . '"></textarea>';
		if($element->hide) {
			$field = '<textarea class="form-control" value="' . $element->defaultvalue . '"></textarea>';
		}
		
		return $field;
	}
	
	protected function getSelectElement($element)
	{
		$field = '<select id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control">';

		$values = explode(',', $element->values);
		$options = '';
		foreach ($values as $value) {
			if($element->hide && $value === $element->defaultvalue) {
				$options.= '<option selected>' . $value . '</option>';
			}
			else {
				$options.= '<option>' . $value . '</option>';
			}
		}
		$field.= $options . '</select>';
		return $field;
	}
	
	protected function getMultiSelectElement($element)
	{
		$field = '<select id="c_' . $element->id . '" name="c_' . $element->id . '[]" class="form-control" multiple="true">';

		$values = explode(',', $element->values);
		$defaultvalues = explode(',', $element->defaultvalue);
		$options = '';
		foreach ($values as $value) {
			if($element->hide && in_array($value, $defaultvalues)) {
				$options.= '<option selected>' . $value . '</option>';
			}
			else {
				$options.= '<option>' . $value . '</option>';
			}
		}
		$field.= $options . '</select>';
		return $field;
	}
	
	protected function getDateElement($element)
	{
		
	}
	
	public function getLinkAction(Form $form)
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		$action = 'contacts/form';
		$parameters = array(1, $form->target, $form->idForm);
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		return $link;
	}
}
