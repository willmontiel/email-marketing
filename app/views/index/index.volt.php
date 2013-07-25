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
 
 <div class="span7 offset1">
  <?php echo $this->getContent(); ?>
  
  <div id="form-signin">
   <?php echo Phalcon\Tag::form(array('account/new', 'id' => 'registerAccount')); ?>
  
   <p>            
    <?php echo Phalcon\Tag::textField(array('companyName', 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Nombre de la Cuenta (Compañía)')); ?>
    <?php echo Phalcon\Tag::textField(array('email', 'class' => 'input-xlarge', 'type' => 'email', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'E-Mail')); ?>
   </p>
   
   <p>            
    <?php echo Phalcon\Tag::textField(array('firstName', 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Nombre')); ?>
    <?php echo Phalcon\Tag::textField(array('lastName', 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Apellido')); ?> 
   </p>

   <p> 
    <?php echo Phalcon\Tag::passwordField(array('pass', 'size' => 35, 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Contraseña')); ?>
    <?php echo Phalcon\Tag::passwordField(array('pass2', 'size' => 35, 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Repite tu Contraseña')); ?> 
   </p>

   <p>
    <?php echo Phalcon\Tag::textField(array('username', 'size' => 35, 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Nombre de Usuario')); ?> 
    Modo de: <?php echo Phalcon\Tag::select(array('type', array('1' => 'Contactos', '0' => 'Envios'))); ?> 
    </select>    
   </p>
   
   <p>
    <?php echo Phalcon\Tag::textField(array('fileSpace', 'size' => 35, 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Cuota de Espacio para Archivos')); ?> 
    <?php echo Phalcon\Tag::textField(array('messageQuota', 'size' => 35, 'class' => 'input-xlarge', 'type' => 'text', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Contactos por Mes')); ?> 
   </p>
   
   <p>
   <?php echo Phalcon\Tag::submitButton(array('Registrar', 'class' => 'btn btn-inverse dropdown-toggle', 'data-toggle' => 'dropdown')); ?>
   </p>
   
   </form>
  </div>  
 </div>

 <?php echo Phalcon\Tag::linkTo(array('session', 'class' => 'btn btn-info dropdown-toggle', 'data-toggle' => 'dropdown', 'login')); ?>
 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>