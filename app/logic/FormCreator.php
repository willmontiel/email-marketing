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
		
		$html_content = $this->getHtmlStyles() . '<form method="post" action="' . $this->getLinkAction($form) . '" class="sm-big-container" style="width:' . $jsoncontent->properties_zone->size . 'px; background-color: ' . $jsoncontent->properties_zone->background_color . ';">';
		
		if($jsoncontent->header_zone->active) {
			$html_content.= $this->getHeader($jsoncontent->header_zone);
		}
		
		$htmlelements = array();
		foreach ($content as $element) {
			$block = array();
			$block['label'] = $this->getLabelElement($element);
			$block['hide'] = ($element->hide) ? 'sm-field-element-form-hide' : '';
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
			$html_content.= '<div class="sm-full-size sm-container-table ' . $block['hide'] . '" style="color: ' . $jsoncontent->properties_zone->color . '; font-family: ' . $jsoncontent->properties_zone->family . '; font-size: ' . $jsoncontent->properties_zone->font_size . 'px;">' . $block['label'] . $block['field'] . '</div>';
			$htmlelements['fields'][] = $block;
		}
		
		$html_content.= $this->getButton($jsoncontent->button_zone) . '</form>';

		return $html_content;
	}
	
	protected function getHeader($element)
	{
		$header = '<div class="sm-header-properties" style="font-size: ' . $element->size . 'px; font-family: ' . $element->family . '; text-align: ' . $element->align . '; font-weight: ' . $element->bold . '; color: ' . $element->color . '; background-image: ' . $element->img_bg_link . ';">' . $element->name . '</div>';
		return $header;
	}
	
	protected function getButton($element)
	{
		$button = '<div class="sm-form-btn-container" style="text-align: ' . $element->align . ';"><input class="sm-form-btn" style="color: ' . $element->color . '; background-color: ' . $element->bckg_color . '; font-size: ' . $element->size . 'px; font-family: ' . $element->family . '; font-weight: ' . $element->bold . ';" value="' . $element->name . '" type="submit"></div>';
		return $button;
	}
	
	protected function getLabelElement($element)
	{
		$label = ($element->required === 'Si') ? '<span class="sm-required">* </span>' . $element->name : $element->name;
		return '<div class="sm-one-fourth-size sm-pull-left sm-label-text"><label for="c_' . $element->id . '">' . $label . '</label></div>';
	}
	
	protected function getTextElement($element)
	{
		$field = ($element->required != 'Si') ? '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" value="' . $this->getValue($element) . '">' : '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control field-element-form-required" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" value="' . $this->getValue($element) . '" required>';
		if($element->hide) {
			$field = '<input type="text" id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control" data-name="' . $element->name . '" value="' . $element->defaultvalue . '">';
		}
		
		return '<div class="sm-half-size sm-pull-left">' . $field . '</div>';
	}
	
	protected function getTextAreaElement($element)
	{
		$field = ($element->required != 'Si') ? '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" >' . $this->getValue($element) . '</textarea>' : '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control field-element-form-required" placeholder="' . $element->placeholder . '" data-name="' . $element->name . '" required>' . $this->getValue($element) . '</textarea>';
		if($element->hide) {
			$field = '<textarea id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control" value="' . $element->defaultvalue . '" data-name="' . $element->name . '"></textarea>';
		}
		
		return '<div class="sm-half-size sm-pull-left">' . $field . '</div>';
	}
	
	protected function getSelectElement($element)
	{
		$field = ($element->required != 'Si') ? '<select id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control" data-name="' . $element->name . '" >' : '<select id="c_' . $element->id . '" name="c_' . $element->id . '" class="sm-form-control field-element-form-required" data-name="' . $element->name . '" required>';
		
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
		return '<div class="sm-half-size sm-pull-left">' . $field . '</div>';
	}
	
	protected function getMultiSelectElement($element)
	{
		$field = ($element->required != 'Si') ? '<select id="c_' . $element->id . '[]" name="c_' . $element->id . '[]" class="sm-form-control" multiple="true" data-name="' . $element->name . '" >' : '<select id="c_' . $element->id . '[]" name="c_' . $element->id . '[]" class="sm-form-control field-element-form-required" multiple="true" data-name="' . $element->name . '" required>';
		
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
		return '<div class="sm-half-size sm-pull-left">' . $field . '</div>';
	}
	
	protected function getDateElement($element)
	{
		$required = array('option' => '', 'class' => '');
		$value = $this->getDateValue($element);
		
		if($element->required == 'Si') {
			$required['option'] = 'required';
			$required['class'] = 'field-element-form-required';
		}
		if($element->hide) {
			$value = array('day' => $element->defaultday, 'month' => $element->defaultmonth, 'year' => $element->defaultyear);
		}
		
		$dNm = $this->getDaysAndMonthOpt($value);
		
		$field = '<div class="sm-date-each-field-container"><select id="c_' . $element->id . '_day" name="c_' . $element->id . '_day" class="sm-form-control ' . $required['class'] . '" ' . $required['option'] . '>' .$dNm['days'] . '</select></div><div class="sm-date-each-field-container"><select id="c_' . $element->id . '_month" name="c_' . $element->id . '_month" class="sm-form-control ' . $required['class'] . '" ' . $required['option'] . '>' .$dNm['months'] . '</select></div><div class="sm-date-each-field-container"><input id="c_' . $element->id . '_year" name="c_' . $element->id . '_year" class="sm-form-control ' . $required['class'] . '" ' . $required['option'] . ' value="' . $value['year'] . '"></div>';
		
		return '<div class="sm-half-size sm-pull-left">' . $field . '</div>';
	}
	
	protected function getDaysAndMonthOpt($value)
	{
		$days = '';
		$months = '';
		$months_array = array(
							array('name' => 'Enero', 'value' => 01),
							array('name' => 'Febrero', 'value' => 02),
							array('name' => 'Marzo', 'value' => 03),
							array('name' => 'Abril', 'value' => 04),
							array('name' => 'Mayo', 'value' => 05),
							array('name' => 'Junio', 'value' => 06),
							array('name' => 'Julio', 'value' => 07),
							array('name' => 'Agosto', 'value' => 08),
							array('name' => 'Septiembre', 'value' => 09),
							array('name' => 'Octubre', 'value' => 10),
							array('name' => 'Noviembre', 'value' => 11),
							array('name' => 'Diciembre', 'value' => 12),
			);
		
		for ($i = 1; $i <= 31; $i++) {
			$selected = ($i == $value['day']) ? 'selected' : '';
			if($i < 10) {
				$days.= '<option ' . $selected . '>0' . $i . '</option>';
			}
			else {
				$days.= '<option ' . $selected . '>' . $i . '</option>';
			}
		}
		
		foreach ($months_array as $month){
			$selected = ($month['value'] == $value['month']) ? 'selected' : '';
			$months.= '<option value="' . $month['value'] . '" ' . $selected . '>' . $month['name'] . '</option>';
		}
		
		return array('days' => $days, 'months' => $months);
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
			if($element->id === 'birthDate') {
				$datef = new \EmailMarketing\General\Misc\DateFormat();
				$date = $datef->transformDateFormat($this->contact->birthDate, 'Y-m-d', 'd/m/Y');
				$date_array = explode('/', $date);
				return array('day' => $date_array[0], 'month' => $date_array[1], 'year' => $date_array[2]);
			}
			$fis = Fieldinstance::findFirst(array(
				'conditions' => 'idCustomField = ?1 AND idContact = ?2',
				'bind' => array(1 => $element->id,
								2 => $this->contact->idContact)
			));
			
			if($fis) { 
				$date = date('d/m/Y', $fis->numberValue);
				$date_array = explode('/', $date);
				return array('day' => $date_array[0], 'month' => $date_array[1], 'year' => $date_array[2]);
			}
		}
		return array('day' => 1, 'month' => 1, 'year' => '');
	}
	
	protected function getHtmlStyles()
	{
		$styles = '	<style type="text/css">
						select[multiple],select[size]{height: auto;}
						.sm-full-size{width: 100%; !important;}
						.sm-one-fourth-size{width: 25% !important;}
						.sm-pull-left{float:left !important;}
						.sm-label-text{text-align:right !important; margin-right:15px !important; font-weight: bold !important; word-break:break-word !important;}
						.sm-half-size{width: 50% !important;}
						.sm-date-each-field-container{width: 33%; float: left;}
						.sm-header-properties{height: 75px; line-height: 75px; display: table; width: 100%;}
						.sm-container-table{display: table; margin-top: 12px;}
						.sm-required{color: #d9534f !important; padding-right: 4px !important;}
						.sm-form-control{display: block;width: 100%;height: 20px;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;color: #555;background-color: #fff;background-image: none;border: 1px solid #ccc;border-radius: 4px;-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);-webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; }
						select.sm-form-control{height: 34px !important;}
						.sm-form-btn{margin-bottom: 10px;display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-radius: 4px;padding-right: 25px;padding-left: 25px;}
						.sm-form-btn-container{padding: 20px 40px 10px 45px;}
						.sm-big-container{padding: 10px 0;}
						.sm-field-element-form-hide{display:none;}
					</style>';
		$scripts = '<script type="text/javascript" src="/vendors/bootstrap_v3/js/jquery-1.9.1.js"></script>
					<script type="text/javascript">
						$(function() {
							$("form").on("submit", function(e) {
								var required = $(".field-element-form-required");
								for (var i = 0 ; i < required.length; i++) {
									if($(required[i]).val().length === 0) {
										e.preventDefault();
									}
								}
							});
						});
					</script>';
		
		return $styles . $scripts;
	}
}
