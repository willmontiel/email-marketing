<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select;

class EditAccountForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('companyName', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus" 
        )));
		
        $this->add(new Text ('fileSpace', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
        $this->add(new Text ('messageLimit', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
		
		$this->add(new Text ('contactLimit', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' 
        )));
        $this->add(new Select("accountingMode", array(
            '1' => 'Por Contacto',
            '2' => 'Envío',
        )));
        $this->add(new Select("subscriptionMode", array(
            '1' => 'Prepago',
            '2' => 'Postpago',
        )));
    }
}