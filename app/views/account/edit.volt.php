	  
     <?php echo Phalcon\Tag::form(array('account/edit', 'id' => 'registerAccount', 'method' => 'Post')); ?>
      <p>
       Nombre de la cuenta: 
	   <?php echo $editFormAccount->render('companyName'); ?>
      </p>

      <p>
       Modo de uso:
       <?php echo $editFormAccount->render('modeUse'); ?>
      </p>

      <p>
       Espacio de archivos:
       <?php echo $editFormAccount->render('fileSpace'); ?>
      </p>

	  <p>
       Cuota de mensajes:
       <?php echo $editFormAccount->render('messageQuota'); ?>
      </p>

      <p>
       Modo de Pago: 
       <?php echo $editFormAccount->render('modeAccounting'); ?>
      </p>

      <p>
       <?php echo Phalcon\Tag::submitButton(array('Registrar', 'class' => 'btn btn-success')); ?>
      </p>
     </form>