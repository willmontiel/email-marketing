<?php

class FormCreator
{	
	function __construct() {
		$this->urlObj = Phalcon\DI::getDefault()->get('urlManager');
	}
	
	public function setContact(Contact $contact)
	{
		$this->contact = $contact;
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
		$field = ($element->required != 'Si') ? '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" value="' . $this->getValue($element) . '">' : '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control field-element-form-required" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" value="' . $this->getValue($element) . '" required>';
		if($element->hide) {
			$field = '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" data-name="' . $element->name . '" value="' . $element->defaultvalue . '">';
		}
		
		return $field;
	}
	
	protected function getTextAreaElement($element)
	{
		$field = ($element->required != 'Si') ? '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" >' . $this->getValue($element) . '</textarea>' : '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control field-element-form-required" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" required>' . $this->getValue($element) . '</textarea>';
		if($element->hide) {
			$field = '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" value="' . $element->defaultvalue . '" data-name="' . $element->name . '"></textarea>';
		}
		
		return $field;
	}
	
	protected function getSelectElement($element)
	{
		$field = ($element->required != 'Si') ? '<select id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control" data-name="' . $element->name . '" >' : '<select id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control field-element-form-required" data-name="' . $element->name . '" required>';
		
		$values = explode(',', $element->values);
		$options = '';
		foreach ($values as $value) {
			if(($element->hide && $value === $element->defaultvalue) || $value === $this->getValue($element)) {
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
		$field = ($element->required != 'Si') ? '<select id="c_' . $element->id . '[]" name="c_' . $element->id . '[]" class="form-control" multiple="true" data-name="' . $element->name . '" >' : '<select id="c_' . $element->id . '[]" name="c_' . $element->id . '[]" class="form-control field-element-form-required" multiple="true" data-name="' . $element->name . '" required>';
		
		$values = explode(',', $element->values);
		$defaultvalues = explode(',', $element->defaultvalue);
		$options = '';
		foreach ($values as $value) {
			if($element->hide && in_array($value, $defaultvalues) || $this->checkValueInOptions($element, $value)) {
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
		$field = ($element->required != 'Si') ? '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control date_view_picker" data-name="' . $element->name . '" value="' . $this->getDateValue($element) . '">' : '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control field-element-form-required date_view_picker" data-name="' . $element->name . '" value="' . $this->getDateValue($element) . '" required>';
		if($element->hide) {
			$field = '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="form-control date_view_picker" value="' . $element->defaultmonth . '/' . $element->defaultday . '/' . $element->defaultyear . '" data-name="' . $element->name . '">';
		}
		
		return $field;
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
	
	public function getLinkUpdateAction(Form $form)
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlObj->getBaseUri(true));
		
		$action = 'contacts/update';
		$parameters = array(1, $this->contact->idContact, $form->idForm);
		$link = $linkdecoder->encodeLink($action, $parameters);
		
		return $link;
	}
	
	public function getValue($element)
	{
		if(isset($this->contact)) {
			if($element->id === 'email' ) {
				return $this->contact->email->email;
			}
			else if($element->id === 'name' || $element->id === 'lastName') {
				$field = $element->id;
				return $this->contact->$field;
			}
			else {
				$value = Fieldinstance::findFirst(array(
					'conditions' => 'idCustomField = ?1 AND idContact = ?2',
					'bind' => array(1 => $element->id,
									2 => $this->contact->idContact)
				));
				
				return $value->textValue;
			}
		}
		
		return '';
	}
	
	public function checkValueInOptions($element, $value)
	{
		if(isset($this->contact)) {
			$fis = Fieldinstance::findFirst(array(
				'conditions' => 'idCustomField = ?1 AND idContact = ?2',
				'bind' => array(1 => $element->id,
								2 => $this->contact->idContact)
			));
			if($fis) { 
				$fi = explode(',', $fis->textValue);

				if(in_array($value, $fi)) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function getDateValue($element)
	{
		if(isset($this->contact)) {
			$fis = Fieldinstance::findFirst(array(
				'conditions' => 'idCustomField = ?1 AND idContact = ?2',
				'bind' => array(1 => $element->id,
								2 => $this->contact->idContact)
			));
			
			if($fis) { 
				return date('m/d/Y', $fis->numberValue);
			}
		}
		return '';
	}
}
