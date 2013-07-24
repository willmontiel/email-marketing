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
        echo "Version de PhalconPHP: [" . \Phalcon\Version::getId() ."]\n";
    }
    
    public function createAction()
    {
        
    }
    
    public function readAction()
    {
        //Recuperar idAccount del usuario
        $name = $this->session->get("user-name");
        $user = User::findFirst("username = '$name'");

        //Recuperar la informacion de la BD que se desea
        $id= $_GET['id'];
        $db= Dbases::findFirst("idDbases = $id");

        //Verificar si el usuario tiene acceso a esa BD
        if($user->idAccount == $db->idAccount)
        {
            $this->view->setVar("sdbase", $db);
        }else //Si no tiene acceso redireccione 
        {
            $this->response->redirect("restricted");
        }
        
    }
    
    public function editAction()
    {
        //Instanciar el formulario y Relacionarlo con los atributos del Model Dbases
        $dbases = new Dbases;
        $editform = new Form($dbases);
        
        //Crear los campos
        $editform->add(new Text("name"));
        $editform->add(new Text("description"));
        $editform->add(new Text("descriptionContacts"));
        
        //Validar los valores que se reciben por Post
        $editform->bind($this->request->getPost(), $dbases);

        if ($this->request->isPost()) 
        {
            if($editform->isValid())
            {
                $dbases->contact = 0;
                $dbases->unsubscribed = 0;
                $dbases->bounced = 0;
                $dbases->idAccount=2;
                echo "es valido";
                //$dbases->save();
                //$this->response->redirect("dbases/read");
            }
        }
        $this->view->editform = $editform;
        
    }
}