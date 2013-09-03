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
		<?php echo Phalcon\Tag::stylesheetLink('css/select2.css'); ?>
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
		<?php echo Phalcon\Tag::javascriptInclude('js/select2.js'); ?>
		
		
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
										<a href="<?php echo $this->url->get(''); ?>">
											Mail Station<span class="navbar-unread">1</span>
										</a>
									</li>
								</ul>
								<ul class="span7"></ul>
								<ul class="nav text-left">
									<li>
										<a href="#fakelink">
											<span class="fui-user"></span> <?php echo $this->userObject->idAccount; ?> <?php echo $this->userObject->firstName; ?> <?php echo $this->userObject->lastName; ?> (<?php echo $this->userObject->username; ?>)
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
							<a href="<?php echo $this->url->get(''); ?>"><?php echo Phalcon\Tag::image(array('src' => '/images/email.png')); ?><a>
						<div class="row-fluid">
							<ul class="nav nav-list text-left">
								<li>
									<a href="<?php echo $this->url->get('contactlist#/lists'); ?>"><label><h4><span class="fui-user"></span> Contactos</h4></label> </a>
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
    <div class="row-fluid">
		<div class="span8">
			<div class="modal-header">
				<h1>Nueva Base de Datos</h1>
			</div>
		</div>
		
		<div class="span4">
			<span class="return-upper-right-corner"><a href="<?php echo $this->url->get('dbase'); ?>"><h3>Regresar</h3></a></span>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
    <div class="row-fluid">
        <form action = "<?php echo $this->url->get('dbase/new'); ?>" method="post">
        <div class="row-fluid">
            <div class="span3">
                <label for="name">*Nombre</label>
            </div>
            <div class="span4">
                <?php echo $editform->render('name'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="description">*Descripcion</label>                
            </div>
            <div class="span4">
                <?php echo $editform->render('description'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="Cdescription">*Descripcion de los Contactos</label>
            </div>
            <div class="span4">
                <?php echo $editform->render('Cdescription'); ?>
            </div>
        </div>
    </div>
    <div class="row-fluid">
		<?php echo Phalcon\Tag::submitButton(array('Guardar', 'class' => 'btn btn-success', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'title' => 'Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides')); ?>
		<a href="<?php echo $this->url->get('dbase'); ?>" class="btn btn-inverse">cancelar</a>
    </div>
    </form>
</div>

						<!-- Fin de contenido -->
					</div>		
				</div>
			</div>	
    </body>
</html>
