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
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		//$this->request->getPost('page', 'int'); // POST
	
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data"  => $this->user->account->dbases,
				"limit" => 5,
				"page"  => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		$this->view->setVar("page", $page);
		
//		$this->view->setVar("dbases", $this->user->account->dbases);
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
			
			if($otherbd=Dbase::findFirstByName($db->name)){
				if($otherbd->idAccount == $db->account->idAccount){
					$this->flash->error('El nombre de la Base de Datos ya se encuentra registrada en esta cuenta, por favor verifica los datos');
				}
			}
			else{
				$db->Ctotal = 0;
				$db->Cactive = 0;
				$db->Cinactive = 0;
				$db->Cunsubscribed  = 0;
				$db->Cbounced  = 0;
				$db->Cspam   = 0;

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
			$fields = Customfield::findByIdDbase($db->idDbase);
			$this->view->setVar("fields", $fields);

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
