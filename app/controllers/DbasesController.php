<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Select;

class DbasesController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        //Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $user = User::findFirst("username = '$name'");
        
        //Recuperar la informacion de la BD que se desea
        $db= Dbases::find("idAccount = $user->idAccount");
        $this->view->setVar("dbases", $db);
    }
    
    public function createAction()
    {
        //Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $user = User::findFirst("username = '$name'");

        //Instanciar el formulario y Relacionarlo con los atributos del Model Dbases
        $db = new Dbases();
        $db->idAccount = $user->idAccount;
        $editform = new EditForm($db);

        //Crear los campos        
        $editform->add(new Text("name"));
        $editform->add(new Text("description"));
        $editform->add(new Text("descriptionContacts"));
        if ($this->request->isPost()) 
        {   
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
        //Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $user = User::findFirst("username = '$name'");
        
        //Recuperar la informacion de la BD que se desea SI existe
        if($db = Dbases::findFirstByIdDbases($id))
        {
            //Verificar si el usuario tiene acceso a esa BD
            if($user->idAccount == $db->idAccount)
            {
                $this->view->setVar("sdbase", $db);
            }else //Si no tiene acceso redireccione 
            {
                $this->dispatcher->forward(
                    array(
                        'controller' => 'dbases',
                        'action' => 'restricted'
                    )
                );
            }
        }  else {
            $this->dispatcher->forward(
                array(
                    'controller' => 'dbases',
                    'action' => 'restricted'
                )
            );
        }
        
    }
    
    public function editAction($id)
    {
        //Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $user = User::findFirst("username = '$name'");

        //Recuperar la informacion de la BD que se desea SI existe
        if($db = Dbases::findFirstByIdDbases($id))
        {
            $this->view->setVar("edbase", $db);
            //Verificar si el usuario tiene acceso a esa BD
            if($user->idAccount === $db->idAccount)
            {
                //Instanciar el formulario y Relacionarlo con los atributos del Model Dbases
                $editform = new EditForm($db);

                //Crear los campos        
                $editform->add(new Text("name"));
                $editform->add(new Text("description"));
                $editform->add(new Text("descriptionContacts"));
                if ($this->request->isPost()) 
                {   
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
                }else //Si no tiene acceso redireccione 
                {
                $this->dispatcher->forward(
                    array(
                        'controller' => 'dbases',
                        'action' => 'restricted'
                    )
                 );
            }
        } else {
            //Si la BD no Existe
            $this->dispatcher->forward(
                        array(
                            'controller' => 'dbases',
                            'action' => 'restricted'
                        )
                    );
        }

        
        
    }
    
    public function deleteAction($id)
    {
        //Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $user = User::findFirst("username = '$name'");

        //Recuperar la informacion de la BD que se desea SI existe
        if($db = Dbases::findFirstByIdDbases($id))
        {
            if($user->idAccount === $db->idAccount)
            {
                $db->delete();
                $this->response->redirect("dbases");
//                $this->dispatcher(
//                            array(
//                                'controller' => 'dbases',
//                                'action' => 'index'
//                            )
//                        );
            }
        }
    }


    public function restrictedAction()
    {

    }
}