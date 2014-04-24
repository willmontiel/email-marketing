<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\TextArea;

class EditForm extends Form
{
    public function initialize()
    {
        $this->add(new Text('name', array(
			'maxlength' => 50,
			'type' => 'text',
			'required' => 'required',
			'autofocus' => "autofocus",
			'class' => "form-control"
        )));
        $this->add(new TextArea('description', array(
			'maxlength' => 150,
			'type' => 'text',
			'required' => 'required',
			'class' => "form-control"
        )));
        $this->add(new TextArea('Cdescription', array(
			'maxlength' => 150,
			'type' => 'text',
			'required' => 'required',
			'class' => "form-control"
        )));
		$this->add(new Text('color',array(
			'type' => 'text',
			'class' => 'text-for-db-color'
		)));
    }
}
