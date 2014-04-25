<?php

class CreateViewEmber
{
	public static function createField(Customfield $field, $options = null)
	{
		$options = (!$options)?array():$options;

		$fieldname = "campo".$field->idCustomField;

		$optionstxt = '';
		foreach ($options as $key => $value) {
			$optionstxt .= " {$key}='{$value}' ";
		}
		switch ($field->type) {
			case "Text":
			case "Numerical": 
				$valor = "{{view Ember.TextField valueBinding='{$fieldname}' id='{$fieldname}' {$optionstxt}";
				break;
			case "TextArea":
				$valor = "{{view Ember.TextArea valueBinding='{$fieldname}' placeholder='{$field->name}' id='{$fieldname}'";
				break;
			case "Select":
				// {{ view Ember.Select contentBinding="nombrecampo_options" valueBinding="nombrecampo" }}
				$valor = "{{view Ember.Select valueBinding='{$fieldname}' contentBinding='App.{$fieldname}_options' id='{$fieldname}' {$optionstxt}";
				break;
			case "MultiSelect":
				$valor = "{{view Ember.Select multiple='true' valueBinding='{$fieldname}' contentBinding='App.{$fieldname}_options' id='{$fieldname}'  {$optionstxt}";
				break;
			case "Date":
				$valor = "{{view App.DatePickerField valueBinding='{$fieldname}' placeholder='{$field->name}' id='{$fieldname}'";
				break;
		}
		
		if($field->minValue && $field->maxValue) {
			$valor.= " placeholder='El valor debe estar entre {$field->minValue} y {$field->maxValue}' pattern='[0-9]{{$field->minValue},{$field->maxValue}}'";
		} elseif ($field->maxLength) {
			$valor.= " placeholder='Maximo {$field->maxLength} caracteres' maxlength='{$field->maxLength}'";
		}
		
		if ($field->required == "Si") {
			$valor.= " required='required' }}";
		}
		else {
			$valor.= "}}";
		}
		return $valor;
	}
	
	public static function createCustomFieldXeditable(Customfield $field)
	{
		$fieldname = "campo".$field->idCustomField;
		switch ($field->type) {
			case "Text":
			case "Numerical": 
				$valor = "{{view App.EmberXEditableTextView value={$fieldname} field='{$fieldname}' title='Editar {$field->name}'";
				break;
			case "TextArea":
				$valor = "{{view App.EmberXEditableTextAreaView value={$fieldname} field='{$fieldname}' title='Editar {$field->name}'";
				break;
			case "Select":
				// {{ view Ember.Select contentBinding="nombrecampo_options" valueBinding="nombrecampo" }}
				$valor = "{{view App.EmberXEditableSelectView source=App.{$fieldname}_options_xeditable value={$fieldname} field='{$fieldname}' title='Editar {$field->name}'";
				break;
			case "MultiSelect":
				$valor = "{{view App.EmberXEditableMultiSelectView source=App.{$fieldname}_options_xeditable value={$fieldname} field='{$fieldname}' title='Editar {$field->name}'";
				break;
			case "Date":
				$valor = "{{view App.EmberXEditableDateView value={$fieldname}  field='{$fieldname}' title='Editar {$field->name}'";
				break;
		}
		
		if($field->minValue && $field->maxValue) {
			$valor.= " placeholder='El valor debe estar entre {$field->minValue} y {$field->maxValue}' pattern='[0-9]{{$field->minValue},{$field->maxValue}}'";
		} elseif ($field->maxLength) {
			$valor.= " placeholder='Maximo {$field->maxLength} caracteres' maxlength='{$field->maxLength}'";
		}
		
		if ($field->required == "Si") {
			$valor.= " required='required' }}";
		}
		else {
			$valor.= "}}";
		}
		return $valor;
	}
	
	public static function createOptions(Customfield $field)
	{
		// App.campo_options = [
		// "valor1",
		// "valor2",
		// "valor3" ...
		// ];
		//
		$resultado = '';
		if ($field->type == 'Select' || $field->type == 'MultiSelect') {
			$oname = "campo" . $field->idCustomField . '_options';
			$resultado = 'App.' . $oname . ' = [' . PHP_EOL;
			$valores = explode(',', $field->values);
			$valores = array_merge(array($field->defaultValue), $valores);
			$valores = array_unique($valores);
			$resultado .= '"' . implode('",' . PHP_EOL . '"', $valores) . '"' . PHP_EOL . '];';
		}
		return $resultado;
	}
	
	public static function createOptionsForXeditable(Customfield $field)
	{
		$resultado = '';
		if ($field->type == 'Select' || $field->type == 'MultiSelect') {
			$oname = "campo" . $field->idCustomField . '_options_xeditable';
			$resultado = 'App.' . $oname . ' = [' . PHP_EOL;
			$valores = explode(',', $field->values);
			$valores = array_merge(array($field->defaultValue), $valores);
			$valores = array_unique($valores);
			foreach ($valores as $valor) {
				$resultado .= " {value: '" . $valor . "', text: '" . $valor . "'}, " . PHP_EOL;
			}
			$resultado .= '];';
		}
		return $resultado;
	}
	
	public static function  createEmberTextField($value, $placeholder, $required) 
	{
		$valor = "{{view Ember.TextField valueBinding='$value' placeholder='$placeholder' id='$value'";
		if ($required == "required") {
			$valor.= " required='required' }}";
		}
		else {
			$valor.= "}}";
		}
		return $valor;
	}
}