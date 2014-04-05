<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element,
	Phalcon\Forms\Element\TextArea;

class FlashMessageForm extends Form
{
    public function initialize()
    {
		$this->add(new Text('name', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus",
			'class' => 'form-control',
			'id' => 'name'
        )));
		
		$this->add(new TextArea('message', array(
			'maxlength' => 50,
			'type' => 'text',
			'rows' => 3,
			'required' => 'required' ,
			'class' => 'form-control',
			'id' => 'message'
        )));
		
		$this->add(new Select('accounts', Account::find(), array(
			'using' => array('idAccount', 'companyName'),
			'multiple' => 'multiple',
			'id' => 'accounts'
		)));
		
		$this->add(new RadioElement('type', array(
			'required' => 'required',
        )));
		
		$this->add(new Text('start', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'class' => 'add-on input-date-picker'
        )));
		
		$this->add(new Text('end', array(
			'maxlength' => 80,
			'type' => 'text',
			'required' => 'required',
			'class' => 'add-on input-date-picker'
        )));
	}
}
