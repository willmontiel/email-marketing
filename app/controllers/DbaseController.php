<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select;

class DbaseController extends ControllerBase
{
	protected $user;
	
	protected function findAndValidateDbaseAccount($id)
	{
		
		if ($db = Dbase::findFirstByIdDbase($id)) {
			if ($this->user->account == $db->account) {
                return $db;
            }
        }  
		$this->dispatcher->forward(
			array(
				'controller' => 'dbase',
				'action' => 'restricted'
			)
		);
		return null;
	}
	
	public function indexAction()
    {
		//Recuperar la informacion de la BD que se desea
		$r = $this->verifyAcl('dbase', 'list', '');
		if ($r)
			return $r;
		
		$this->view->setVar("dbases", $this->user->account->dbase);
    }
    
    public function newAction()
    {
		$r = $this->verifyAcl('dbase', 'new', '');
		if ($r)
			return $r;
        //Instanciar el formulario y Relacionarlo con los atributos del Model Dbase
        $db = new Dbase();
        $db->account = $this->user->account;
        $editform = new EditForm($db);

        if ($this->request->isPost()) {   
            $editform->bind($this->request->getPost(), $db);

            if ($editform->isValid() && $db->save()) {
                $this->flash->success('Base de Datos Creada Exitosamente!');
                $this->dispatcher->forward(
                    array(
                        'controller' => 'dbase',
                        'action' => 'show',
                        'params' => array($db->idDbase)
                    )
                );
            }
            else {
                foreach ($db->getMessages() as $msg) {
                    $this->flash->error($msg);
                }
            }
        }
        $this->view->editform = $editform;
    }
    
    public function showAction($id)
    {
		$r = $this->verifyAcl('dbase', 'show', '');
		if ($r)
			return $r;
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
			$this->view->setVar("sdbase", $db);
			
//*************************INICIA LA PARTE DE CUSTOM FIELDS*******************
			
			$field = Customfield::findByIdDbase($id);
			$this->view->setVar("fields", $field);
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
    }
    
    public function editAction($id)
    {
		$r = $this->verifyAcl('dbase', 'edit', '');
		if ($r)
			return $r;
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
            $this->view->setVar("edbase", $db);
			//Instanciar el formulario y Relacionarlo con los atributos del Model Dbase
			$editform = new EditForm($db);

			if ($this->request->isPost()) {   
				$editform->bind($this->request->getPost(), $db);

				if ($editform->isValid() && $db->save()) {
					$this->flash->success('Base de Datos Actualizada Exitosamente!');
					$this->dispatcher->forward(
						array(
							'controller' => 'dbase',
							'action' => 'show'
						)
					);
				}
				else {
					foreach ($db->getMessages() as $msg) {
						$this->flash->error($msg);
					}
				}
			}
			$this->view->editform = $editform;
        } 

    }
    
    public function deleteAction($id)
    {
        //Recuperar la informacion de la BD que se desea SI existe
        $db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
			if ($this->request->isPost() && ($this->request->getPost('delete')=="DELETE")) {
				$db->delete();
				$this->flashSession->success('Base de Datos Eliminada!');
				$this->response->redirect("dbase");
			} else {
				$this->flashSession->error('Escriba la palabra "DELETE" correctamente');
				$this->view->disable();
				return $this->response->redirect('dbase');
			}
		}
    }


    public function restrictedAction()
    {

    }
}