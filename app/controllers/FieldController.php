<?php
class FieldController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
		
	}
	
	public function newAction()
	{
		$field = new CustomField();
		$form = new NewFieldForm($field);
		
		if ($this->request->isPost()) {
			
			$form->bind($this->request->getPost(), $field);
			$this->db->begin();
			
			if (!$form->isValid() OR !$field->save()) {
				
				$this->db->rollback();
				foreach ($field->getMessages() as $msg) {
						$this->flash->error($msg);
				}
			}
			else{
				$this->db->commit();
				$this->flash->success('Se ha creado la cuenta exitosamente');
			}
		}
		$this->view->NewFieldForm = $form;
		
	}
	
	public function editAction($id)
	{
		$field = new CustomField();
		$editform = new NewFieldForm($field);
		$registro = Customfield::findFirstByIdCustomField($id);
				
		if ($this->request->isPost()) {   
			$editform->bind($this->request->getPost(), $registro);

			if ($editform->isValid() && $registro->save()) {
				$this->flash->success('Campo Editado Exitosamente!');
			}
		}
	}
}
