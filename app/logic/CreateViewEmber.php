<?php

class CreateViewEmber
{
	public static function createField(Customfield $field)
	{
		$fieldname = strtolower($field->name);
		switch ($field->type) {
			case "Text":
			case "Date":
			case "Numerical":
				$valor = "{{view Ember.TextField valueBinding='{$fieldname}' placeholder='{$field->name}' id='{$fieldname}'";
				break;
			case "TextArea":
				$valor = "{{view Ember.TextArea valueBinding='{$fieldname}' placeholder='{$field->name}' id='{$fieldname}'";
				break;
			case "Select":
				// {{ view Ember.Select contentBinding="nombrecampo_options" valueBinding="nombrecampo" }}
				$valor = "{{view Ember.Select valueBinding='{$fieldname}' contentBinding='App.{$fieldname}_options' id='{$fieldname}' class='select'";
				break;
			case "MultiSelect":
				$valor = "{{view Ember.Select multiple='true' valueBinding='{$fieldname}' contentBinding='App.{$fieldname}_options' id='{$fieldname}' class='select'";
				break;
		}
		
		if($field->minValue && $field->maxValue) {
			$valor.= " pattern='[0-9]{{$field->minValue},{$field->maxValue}}'";
		} elseif ($field->maxLength) {
			$valor.= " maxlength='{$field->maxLength}'";
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
			$oname = strtolower($field->name) . '_options';
			$resultado = 'App.' . $oname . ' = [' . PHP_EOL;
			$valores = explode(',', $field->values);
			$valores = array_merge(array($field->defaultValue), $valores);
			$resultado .= '"' . implode('",' . PHP_EOL . '"', $valores) . '"' . PHP_EOL . '];';
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