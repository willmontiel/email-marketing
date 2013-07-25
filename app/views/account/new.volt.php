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
  
   <?php echo Phalcon\Tag::form(array('account/new', 'id' => 'registerAccount', 'method' => 'Post')); ?>
   <div class="row-fluid">
    <div class="span4">
      <p>
       Nombre de la cuenta: <?php echo $newFormAccount->render('companyName'); ?>
      </p>
    </div>
   </div>

      <p>
       Dirección de correo electronico: <?php echo $newFormAccount->render('email'); ?>
      </p>

      <p>
       Nombre:
       <?php echo $newFormAccount->render('firstName'); ?>
      </p>

      <p>
       Apellido:
       <?php echo $newFormAccount->render('lastName'); ?>
      </p>

	  <p>
       Nombre de usuario:
       <?php echo $newFormAccount->render('username'); ?>
      </p>

      <p>
       Contraseña: 
       <?php echo $newFormAccount->render('password'); ?>
      </p>

      <p>
       Repita la contraseña:
       <?php echo $newFormAccount->render('password2'); ?>
      </p>

      <p>
       Cantidad de espacio para archivos:
       <?php echo $newFormAccount->render('fileSpace'); ?>
      </p>

      <p>
       Cantidad de mensajes
       <?php echo $newFormAccount->render('messageQuota'); ?>
      </p>

      <p>
       Modo de uso:
       <?php echo $newFormAccount->render('modeUse'); ?>
      </p>

      <p>
       Modo de pago:
       <?php echo $newFormAccount->render('modeAccounting'); ?>
      </p>
      <p>
       <?php echo Phalcon\Tag::submitButton(array('Registrar', 'class' => 'btn btn-success')); ?>
      </p>
   
   </form>
  </div>  
 
 <div class="span2">
  <img src="images/gorilla.jpg"/>
 </div>
</div>
</div>