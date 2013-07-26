<div class="container-fluid">
<div class="row-fluid">
 <div class="span12">
  <div id="specific">
   <h1>Mail Gorilla</h1>
  </div>
 </div>
 <br>
 <div class="span11">
  <?php echo $this->getContent(); ?>
   <div class="row-fluid">
    <div class="span4">
		<?php echo Phalcon\Tag::form(array('account/new', 'id' => 'registerAccount', 'method' => 'Post')); ?>
      <p>
       <label for="companyName">Nombre de la cuenta:</label>
		<?php echo $newFormAccount->render('companyName'); ?>
      </p>
      <p>
       <label for="email">Dirección de correo electronico:</label> 
		<?php echo $newFormAccount->render('email'); ?>
      </p>

      <p>
       <label for="firstName">Nombre:</label>
       <?php echo $newFormAccount->render('firstName'); ?>
      </p>

      <p>
       <label for="lastName">Apellido:</label>
       <?php echo $newFormAccount->render('lastName'); ?>
      </p>

	  <p>
       <label for="username">Nombre de usuario:</label>
       <?php echo $newFormAccount->render('username'); ?>
      </p>

      <p>
       <label for="password">Contraseña:</label>
       <?php echo $newFormAccount->render('password'); ?>
      </p>

      <p>
       <label for="password2"> Repita la contraseña:</label>
       <?php echo $newFormAccount->render('password2'); ?>
      </p>

      <p>
       <label for="fileSpace">Cantidad de espacio para archivos:</label>
       <?php echo $newFormAccount->render('fileSpace'); ?>
      </p>

      <p>
       <label for="messageQuota">Cantidad de mensajes</label>
       <?php echo $newFormAccount->render('messageQuota'); ?>
      </p>

      <p>
       <label for="modeUse">Modo de uso:</label>
       <?php echo $newFormAccount->render('modeUse'); ?>
      </p>

      <p>
       <label for="modeAccounting">Modo de pago:</label>
       <?php echo $newFormAccount->render('modeAccounting'); ?>
      </p>
      <p>
       <?php echo Phalcon\Tag::submitButton(array('Registrar', 'class' => 'btn btn-success')); ?>
      </p>
   </form>
	</div>
   </div>
  </div>  
 
 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>