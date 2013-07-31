<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Check,
    Phalcon\Forms\Element\Password;

class NewFieldForm extends Form
{
	public function initialize() 
	{
		
		$this->add(new Text ('name', array(
			'class' => 'span3',
			'maxlength' => 30,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus" 
        )));
		$this->add(new Select("type", array(
            '1' => 'Texto',
            '2' => 'Fecha',
			'3' => 'Númerico',
			'4' => 'Texto Multilínea',
			'5' => 'Selección',
			'6' => 'Selección Multiple'
        )));
		$this->add(new Check("required", array(	
			'value' => "1",
			'data-toggle' => "checkbox"
        )));

	}
}


