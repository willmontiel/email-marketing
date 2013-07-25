<div class="container-fluid">
<div class="row-fluid">
 <div class="span12">
  <div id="specific">
   <h1>Mail Gorilla</h1>
  </div>
 </div>
 <br>
 <div class="span9">
  <?php echo $this->getContent(); ?>
  
  <div id="form-signin">
   <?php echo Phalcon\Tag::form(array('account/new', 'id' => 'registerAccount', 'method' => 'Post')); ?>
      <p>
       Nombre de la cuenta: 
       <?php echo $form->render('companyName'); ?>
      </p>

      <p>
       Dirección de correo electronico: <?php echo $form->render('email'); ?>
      </p>

      <p>
       Nombre:
       <?php echo $form->render('firstName'); ?>
      </p>

      <p>
       Apellido:
       <?php echo $form->render('lastName'); ?>
      </p>

	  <p>
       Nombre de usuario:
       <?php echo $form->render('username'); ?>
      </p>

      <p>
       Contraseña: 
       <?php echo $form->render('password'); ?>
      </p>

      <p>
       Repita la contraseña:
       <?php echo $form->render('password2'); ?>
      </p>

      <p>
       Cantidad de espacio para archivos:
       <?php echo $form->render('fileSpace'); ?>
      </p>

      <p>
       Cantidad de mensajes
       <?php echo $form->render('messageQuota'); ?>
      </p>

      <p>
       Modo de uso:
       <?php echo $form->render('modeUse'); ?>
      </p>

      <p>
       Modo de pago:
       <?php echo $form->render('modeAccounting'); ?>
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