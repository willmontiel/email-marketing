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
       <?php echo $form->render('companyName'); ?>
       <?php echo $form->render('email'); ?>
      </p>

      <p>
       <?php echo $form->render('firstName'); ?>
       <?php echo $form->render('lastName'); ?>
      </p>

      <p>
       <?php echo $form->render('pass'); ?>
       <?php echo $form->render('pass2'); ?>
      </p>

      <p>
       <?php echo $form->render('username'); ?>
       <?php echo $form->render('fileSpace'); ?>
      </p>

      <p>
       <?php echo $form->render('messageQuota'); ?>
       <?php echo $form->render('modeUse'); ?>
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