<?php
class FieldController extends ControllerBase
{
	public function indexAction()
	{
		
	}
	
	public function newAction($idbase)
	{
		$field = new Customfield();
		$form = new NewFieldForm($field);
		
		if ($this->request->isPost()) {

			$form->bind($this->request->getPost(), $field);
			$this->db->begin();
			$field->idDbase = $idbase;
			$field->idAccount = Dbase::findFirstByIdDbase($idbase)->idAccount;
	
				if (!$form->isValid() OR !$field->save()) {
				
				$this->db->rollback();
					foreach ($field->getMessages() as $msg) {
						$this->flash->error($msg);
					}
				}
				else{
				$this->db->commit();
				$this->flash->success('Se ha creado el campo');
				$this->response->redirect("dbase/show/$idbase#/campos");
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
	
	public function deleteAction($id)
	{
		$registro = Customfield::findFirstByIdCustomField($id);
		$idbase = $registro->idDbase;
		$registro->delete();
		$this->response->redirect("dbase/show/$idbase#/campos");
	}
}
