<?php

/**
 * @RoutePrefix("/field")
 */
class FieldController extends ControllerBase
{
	
	/**
	 * @Get("/")
	 */
	public function indexAction()
	{
		$r = $this->verifyAcl('field', 'index', '');
		if ($r)
			return $r;
		
		$idbase = 0;
		$dbases = $this->user->account->dbases;
		
		$lista = array();
		foreach ($dbases as $db) {
			$elem = array();
			$elem['id'] = $db->idDbase;
			$elem['name'] = $db->name;
			$elem['description'] = $db->description;
			$elem['Ctotal'] = $db->Ctotal;
			$elem['name'] = $db->Cactive;
			$elem['name'] = $db->Cinactive;
			$elem['name'] = $db->Cunsubscribed;
			$elem['name'] = $db->Cbounced;
			$elem['name'] = $db->Cspam;
		}
		
	}
	
	
	/**
	 * @Post("/")
	 */
	public function insertAction()
	{
		$r = $this->verifyAcl('field', 'insert', '');
		if ($r)
			return $r;
		
		$idBase = $this->request->get('idDbase', 'int', 0);
		
		$this->view->disable();
		
		
		$this->response->setContentType('application/json', 'UTF-8');
		
		$this->response->setContent(json_encode(
					array(
						'status' => 'success',
						'idDbase' => $idBase
					)
				)
		);
		
		return $this->response;
	}
	
	/**
	 * @Put("/{idCustomField:[0-9]+}")
	 */
	public function updateAction($idCustomField)
	{
		$r = $this->verifyAcl('field', 'update', '');
		if ($r)
			return $r;

		$this->view->disable();
		
		
		$this->response->setContentType('application/json', 'UTF-8');
		
		$this->response->setContent(json_encode(
					array(
						'status' => 'success',
						'command' => 'update',
						'idCustomField' => $idCustomField
					)
				)
		);
		
		return $this->response;
	}
	
	/**
	 * @Get("/{idCustomField:[0-9]+}")
	 */
	public function queryAction($idCustomField)
	{
		$r = $this->verifyAcl('field', 'query', '');
		if ($r)
			return $r;
		
		$this->view->disable();
		
		
		$this->response->setContentType('application/json', 'UTF-8');
		
		$this->response->setContent(json_encode(
					array(
						'status' => 'success',
						'command' => 'query',
						'idCustomField' => $idCustomField,
						'text' => 'Estos son los campos del custom field'
					)
				)
		);
		
		return $this->response;
	}

	public function newAction($idbase)
	{
		$r = $this->verifyAcl('field', 'new', '');
		if ($r)
			return $r;
		
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
		$r = $this->verifyAcl('field', 'edit', '');
		if ($r)
			return $r;
		
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
		$r = $this->verifyAcl('field', 'delete', '');
		if ($r)
			return $r;
		
		$registro = Customfield::findFirstByIdCustomField($id);
		$idbase = $registro->idDbase;
		$registro->delete();
		$this->response->redirect("dbase/show/$idbase#/campos");
	}
}
