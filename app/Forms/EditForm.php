<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Password;

class EditForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('name', array(
        'maxlength' => 50,
		'type' => 'text',
		'required' => 'required',
		'autofocus' => "autofocus"
        )));
        $this->add(new Text('description', array(
        'maxlength' => 150,
		'type' => 'text',
		'required' => 'required'
        )));
        $this->add(new Text('Cdescription', array(
        'maxlength' => 150,
		'type' => 'text',
		'required' => 'required'
        )));
    }
}
