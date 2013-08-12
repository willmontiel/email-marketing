<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php echo Phalcon\Tag::getTitle(); ?>
        <?php echo Phalcon\Tag::stylesheetLink('bootstrap/css/bootstrap.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/style.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/flat-ui.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/bootstrap-modal.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/prstyles.css'); ?>
		<?php echo Phalcon\Tag::stylesheetLink('css/style.css'); ?>
		<?php echo Phalcon\Tag::stylesheetLink('css/emarketingstyle.css'); ?>
		
		
        <?php echo Phalcon\Tag::javascriptInclude('js/libs/jquery-1.9.1.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('bootstrap/js/bootstrap.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery-ui-1.10.3.custom.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.ui.touch-punch.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-select.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-switch.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/flatui-checkbox.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/flatui-radio.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.tagsinput.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.placeholder.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.stacktable.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/application.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-modal.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-modalmanager.js'); ?>
		
		
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Proyect">
        <meta name="author" content="Ivan">

        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<div class="container-fluid">
			<!-----Inicio de la Toolbar Flat ui  ------->
		<div class="row-fluid">
			<div class="span12">
				<div class="navbar navbar-inverse">
					<div class="navbar-inner">
						<div class="container">
							<div class="nav-collapse collapse" id="nav-collapse-01">
								<ul class="nav">
									<li>
										<a href="/emarketing/">
											Mail Station<span class="navbar-unread">1</span>
										</a>
									</li>
								</ul>
								<ul class="span8"></ul>
								<ul class="nav">
									<li>
										<a href="#fakelink">
											<span class="fui-user"></span> <?php echo $this->userObject->firstName; ?> <?php echo $this->userObject->lastName; ?> (<?php echo $this->userObject->username; ?>)
										</a>
										<ul>
											<li><a href="#fakelink"><span class="fui-eye"></span> Ver perfil</a></li>
											<li><a href="#fakelink"><span class="fui-new"></span> Cambio de contraseña</a></li>
											<li><a href="session/logout"><span class="fui-cross"></span></span> Cerrar Sesión</a></li>
										</ul>
									</li>
								</ul>	
							</div>	
						</div>
					</div>
				</div>
			</div>
		 </div>
	<!----- fin de la toolbar flat ui---->
			
				<div class="row-fluid">
					<div class="span2">
							<a href="/emarketing/"><?php echo Phalcon\Tag::image(array('src' => '/images/email.png')); ?><a>
						<div class="row-fluid">
							<ul class="nav nav-list text-left">
								<li>
									<a href="/emarketing/dbase"><label><h4><span class="fui-user"></span> Contactos</h4></label> </a>
								</li>
								<li>
									<a href="#"><label><h4><span class="fui-list"></span> Campañas</h4></label></a>
								</li>
								<li>
									<a href="#"><label><h4><span class="fui-mail"></span> Autorespuestas</h4></label></a>
								</li>
								<li>
									<a href="#"><label><h4><span class="fui-check"></span> Estadisticas</h4></label></a>
								</li>
							</ul>

						</div>
					</div>
					<div class="span10">
						<?php echo $this->getContent(); ?>
						<!-- Inicio de contenido -->
						
<div class="row-fluid">
		<h1>Crear una nueva cuenta</h1>
</div>
 <div class="span12">
	<div class="row-fluid">
		<div class="span5">
		<?php echo Phalcon\Tag::form(array('account/new', 'id' => 'registerAccount', 'method' => 'Post')); ?>

			<fieldset>
				<legend>Datos de la cuenta</legend>

				<p>
				 <label for="companyName">*Nombre de la cuenta:</label>
				  <?php echo $newFormAccount->render('companyName'); ?>
				</p>

				<p>
				 <label for="fileSpace">*Espacio disponible en disco (Mb):</label>
				 <?php echo $newFormAccount->render('fileSpace'); ?>
				</p>

				<p>
				 <label for="messageQuota">*Limite de mensajes/contactos</label>
				 <?php echo $newFormAccount->render('messageQuota'); ?>
				</p>

				<p>
				 <label for="modeUse">*Modo de uso:</label>
				 <?php echo $newFormAccount->render('modeUse'); ?>
				</p>

				<p>
				 <label for="modeAccounting">*Modo de pago:</label>
				 <?php echo $newFormAccount->render('modeAccounting'); ?>
				</p>	
			</fieldset>
		</div>
     
		<div class="span1"></div>
    
		<div class="span5">
			<fieldset>
				<legend>Datos del administrador</legend>
				<p>
				 <label for="firstName">*Nombre:</label>
				 <?php echo $newFormAccount->render('firstName'); ?>
				</p>

				<p>
				 <label for="lastName">*Apellido:</label>
				 <?php echo $newFormAccount->render('lastName'); ?>
				</p>

				<p>
				 <label for="email">*Dirección de correo electronico:</label> 
				  <?php echo $newFormAccount->render('email'); ?>
				</p>

				<p>
				 <label for="username">*Nombre de usuario:</label>
				 <?php echo $newFormAccount->render('username'); ?>
				</p>

				<p>
				 <label for="password">*Contraseña:</label>
				 <?php echo $newFormAccount->render('password'); ?>
				</p>

				<p>
				 <label for="password2">*Repita la contraseña:</label>
				 <?php echo $newFormAccount->render('password2'); ?>
				</p>
			</fieldset>
		</div>
	</div>

      <p>
		<?php echo Phalcon\Tag::submitButton(array('Registrar', 'class' => 'btn btn-success', 'data-toggle' => 'tooltip', 'title' => 'Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides')); ?>
		<?php echo Phalcon\Tag::linkTo(array('account', 'class' => 'btn btn-inverse', 'Cancelar')); ?>
      </p>
   </form>
   </div>
  </div>  

						<!-- Fin de contenido -->
					</div>		
				</div>
			</div>	
    </body>
</html>