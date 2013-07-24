<?php
use Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\Email;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Select,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Password;

class AccountController extends \Phalcon\Mvc\Controller
{

    private function _validatePass($pass)
    {   
           //compruebo que el tamaño del string sea válido. 
           if (strlen($pass)<8 || strlen($pass)>40)
           { 
            return false; 
           } 

          
    }
    
    private function _validateUsername($username)
    {
        //compruebo que los caracteres sean los permitidos 
           $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_"; 
           for ($i=0; $i<strlen($username); $i++)
           { 
              if (strpos($permitidos, substr($username,$i,1))===false)
              {  
                 return false; 
              } 
              
           } 
           return true; 
    
    }
    
    public function newAction()
    {
        $account = new Account();
        $form = new Form($account);
        
        $form->add(new Text('companyName'));
//        $form->add(new Text('email'));
//        $form->add(new Text ('firstName'));
//        $form->add(new Text ('lastName'));
//        $form->add(new Password ('pass'));
//        $form->add(new Password ('pass2'));
//        $form->add(new Text ('username'));
        $form->add(new Text ('fileSpace'));
        $form->add(new Text ('messageQuota'));
        $form->add(new Select("modeUse", array(
            '1' => 'Por Contacto',
            '0' => 'Envio',
        )));
        

        if ($this->request->isPost()) {
            
            $form->bind($this->request->getPost(), $account);

            echo "Version de PhalconPHP: [" . \Phalcon\Version::getId() ."]\n";
            if ($form->isValid() && $account->save()) {
                
                $this->flash->success('La cuenta se ha registrado exitosamente!');
                
//                $this->dispatcher->forward(
//                    array(
//                        'controller' => 'account',
//                        'action' => 'new'
//                    )
//                );
        }
        else {
           foreach ($account->getMessages() as $msg) {
                    $this->flash->error($msg);
           }
        }
    }
      
        
    $this->view->form = $form;
 
     
}     
     
 }  
