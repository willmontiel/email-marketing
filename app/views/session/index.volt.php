<div class="container-fluid">
<div class="row-fluid">
 <div class="span12">
  <div id="specific" class="hero-unit">
   <h1>Mail Gorilla</h1>
  </div>
 </div>

 <div class="span12 offset4" >
  <h3>Registrar una Cuenta</h3>
 </div>
 
 <div class="span6 offset2">
  <?php echo $this->getContent(); ?>
  
  <div id="form-login">
   <?php echo Phalcon\Tag::form(array('session/login', 'id' => 'sessionlogin')); ?>
  
   <p>            
    <?php echo Phalcon\Tag::textField(array('username', 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Nombre de Usuario o E-mail')); ?>
   </p>

   <p> 
    <?php echo Phalcon\Tag::passwordField(array('pass', 'class' => 'input-xlarge', 'type' => 'email', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Contraseña')); ?>
   </p>
  
   <p>
   <?php echo Phalcon\Tag::submitButton(array('Ingresar', 'class' => 'btn btn-inverse dropdown-toggle', 'data-toggle' => 'dropdown')); ?>
   <?php echo Phalcon\Tag::checkField(array('online')); ?> Recuerdame
   </p>
   </form>
  </div>  
 </div>

 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>