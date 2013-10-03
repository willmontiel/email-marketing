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
		$currentPage = $this->request->getQuery('page', null, 1); // GET
		
		$idAccount = $this->user->account->idAccount;
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data"  => Dbase::find("idAccount = $idAccount"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page"  => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
    }
    
    public function newAction()
    {
        //Instanciar el formulario y Relacionarlo con los atributos del Model Dbase
        $db = new Dbase();
		
        $editform = new EditForm($db);

        if ($this->request->isPost()) {   
            $editform->bind($this->request->getPost(), $db);
			$idAccount = $this->user->account->idAccount;
			$name = $editform->getValue('name');
		
			$nameExist = Dbase::findFirst(array(
				"conditions" => "idAccount = ?1 AND name = ?2",
				"bind" => array(1 => $idAccount,
								2 => $name)
			));
			if ($nameExist) {
				$this->flashSession->outputMessage('error', 'El nombre de la Base de Datos ya se encuentra registrada en esta cuenta, por favor verifica los datos');
				return $this->response->redirect('dbase/new');
			}
			else {
				
				$log->log('A punto de guardar ');
				$db->idAccount = $idAccount;
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
						$this->flashSession->error($msg);
					}
					return $this->response->redirect('dbase/new');
					
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
			$fields = Customfield::findByIdDbase($db->idDbase);
			$this->view->setVar("fields", $fields);

        }
    }
   
    public function editAction($id)
    {
        //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
            $this->view->setVar("edbase", $db);
			//Instanciar el formulario y Relacionarlo con los atributos del Model Dbase
			$editform = new EditForm($db);
			
			if ($this->request->isPost()) {   
				$editform->bind($this->request->getPost(), $db);
				
				$idAccount = $db->idAccount;
				$name = $editform->getValue('name');
				
				$nameExist = Dbase::findFirst(array(
					"conditions" => "idAccount = ?1 AND name = ?2 AND idDbase != ?3",
					"bind" => array(1 => $idAccount,
									2 => $name,
									3 => $db->idDbase)
				));
				if ($nameExist) {
					$this->flashSession->error('El nombre de la Base de Datos ya se encuentra registrada en esta cuenta, por favor verifica los datos');
					return $this->response->redirect('dbase/edit/'. $id);
				}
				else {
					if ($editform->isValid() && $db->save()) {
						$this->flashSession->success('Base de Datos Actualizada Exitosamente!');
						return $this->response->redirect('dbase/show/'. $id);
					}
					else {
						foreach ($db->getMessages() as $msg) {
							$this->flashSession->error($msg);
						}
						return $this->response->redirect("dbase/edit/". $id);
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
