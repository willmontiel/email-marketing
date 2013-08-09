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
		<?php echo Phalcon\Tag::javascriptInclude('js/libs/handlebars-1.0.0-rc.4.js'); ?>
		<?php echo Phalcon\Tag::javascriptInclude('js/libs/ember-1.0.0-rc.6.1.js'); ?>
		<?php echo Phalcon\Tag::javascriptInclude('js/libs/ember-data.js'); ?>
		<?php echo Phalcon\Tag::javascriptInclude('js/libs/ember-data-validations'); ?>
		<?php echo Phalcon\Tag::javascriptInclude('js/app.js'); ?>

		
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
							<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#nav-collapse-01"></button>
						<div class="nav-collapse collapse" id="nav-collapse-01">
							<ul class="nav">
								<li>
									<a href="#fakelink">
										Mail Station
										<span class="navbar-unread">1</span>
									</a>
								</li>
							</ul>
								<div class="span8"></div>
							<ul class="nav">
								<li>
									<a href="#fakelink">
										<?php echo $this->userObject->firstName; ?> <?php echo $this->userObject->lastName; ?> (<?php echo $this->userObject->username; ?>)<span class="fui-user"></span>
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
					<div class="span3">
							<a href="/emarketing/"><?php echo Phalcon\Tag::image(array('src' => '/images/gorilla.jpg')); ?><a>
						<div class="row-fluid">
							<ul class="nav nav-list text-center">
								<li>
									<a href="/emarketing/dbases"><label>Contactos</label></a>
								</li>
								<li>
									<a href="#"><label>Campañas</label></a>
								</li>
								<li>
									<a href="#"><label>Autorespuestas</label></a>
								</li>
								<li>
									<a href="#"><label>Estadisticas</label></a>
								</li>
							</ul>

						</div>
					</div>
					<div class="span9">
						<?php echo $this->getContent(); ?>
						<!-- Inicio de contenido -->
						
<?php echo $this->getContent(); ?>
<div class="row-fluid">
    <div class="row-fluid">
		<div class="span8">
			<div class="modal-header">
				<h1>Nueva Base de Datos</h1>
			</div>
		</div>
		
		<div class="span4">
			<span class="return-upper-right-corner"><a href="/emarketing/dbase"><h3>Regresar</h3></a></span>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
    <div class="row-fluid">
        <form action = "/emarketing/dbase/new" method="post">
        <div class="row-fluid">
            <div class="span3">
                <label for="name">Nombre</label>
            </div>
            <div class="span4">
                <?php echo $editform->render('name'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="description">Descripcion</label>                
            </div>
            <div class="span4">
                <?php echo $editform->render('description'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="Cdescription">Descripcion de los Contactos</label>
            </div>
            <div class="span4">
                <?php echo $editform->render('Cdescription'); ?>
            </div>
        </div>
    </div>
    <div class="row-fluid">
		<?php echo Phalcon\Tag::submitButton(array('Guardar', 'class' => 'btn btn-success')); ?>
		<?php echo Phalcon\Tag::linkTo(array('dbase', 'class' => 'btn btn-inverse', 'Cancelar')); ?>
    </div>
    </form>
</div>

						<!-- Fin de contenido -->
					</div>		
				</div>
			</div>	
    </body>
</html>
