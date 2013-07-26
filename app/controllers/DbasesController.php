<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select;

class DbasesController extends \Phalcon\Mvc\Controller
{
	protected $user;
	
	public function initialize()
	{
		//Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $this->user = User::findFirst("username = '$name'");
	}

	protected function findAndValidateDbaseAccount($id)
	{
		if ($db = Dbases::findFirstByIdDbases($id)) {
			if ($this->user->account == $db->account) {
                return $db;
            }
        }  
		$this->dispatcher->forward(
			array(
				'controller' => 'dbases',
				'action' => 'restricted'
			)
		);
		return null;
	}
	
	public function indexAction()
    {

		//Recuperar la informacion de la BD que se desea
        $this->view->setVar("dbases", $this->user->account->dbases);
    }
    
    public function newAction()
    {
        //Instanciar el formulario y Relacionarlo con los atributos del Model Dbases
        $db = new Dbases();
        $db->account = $this->user->account;
        $editform = new EditForm($db);

        if ($this->request->isPost()) {   
            $editform->bind($this->request->getPost(), $db);

            if ($editform->isValid() && $db->save()) {
                $this->flash->success('Base de Datos Creada Exitosamente!');
                $this->dispatcher->forward(
                    array(
                        'controller' => 'dbases',
                        'action' => 'show',
                        'params' => array($db->idDbases)
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
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
			$this->view->setVar("sdbase", $db);
        }
    }
    
    public function editAction($id)
    {
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
            $this->view->setVar("edbase", $db);
			//Instanciar el formulario y Relacionarlo con los atributos del Model Dbases
			$editform = new EditForm($db);

			if ($this->request->isPost()) {   
				$editform->bind($this->request->getPost(), $db);

				if ($editform->isValid() && $db->save()) {
					$this->flash->success('Base de Datos Actualizada Exitosamente!');
					$this->dispatcher->forward(
						array(
							'controller' => 'dbases',
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
				$this->response->redirect("dbases");
			} else {
				$this->flashSession->error('Escriba la palabra "DELETE" correctamente');
				$this->view->disable();
				return $this->response->redirect('dbases');
			}
		}
    }


    public function restrictedAction()
    {

    }
}