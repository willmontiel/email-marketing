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
			'class' => 'span12'
        )));
		
		$this->add(new TextArea('message', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required' ,
			'class' => 'span12'
        )));
		
		$this->add(new Select('accounts', Account::find(), array(
			'using' => array('idAccount', 'companyName'),
			'multiple' => 'multiple',
			'class' => 'chzn-select'
		)));
		
		$this->add(new RadioElement('mtype', array(
			'required' => 'required',
			'class' => 'icheck'
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
