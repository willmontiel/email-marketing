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
										<a href="/emarketing/">
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
							<a href="/emarketing/"><?php echo Phalcon\Tag::image(array('src' => '/images/email.png')); ?><a>
						<div class="row-fluid">
							<ul class="nav nav-list text-left">
								<li>
									<a href="/emarketing/contactlist#/lists"><label><h4><span class="fui-user"></span> Contactos</h4></label> </a>
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
	<div class="span9">
		<div class="row-fluid">
			<div class="modal-header">
				<h1>Creacion Rapida de Contactos</h1>
			</div>
		</div>
			<div class="row-fluid">
				<table class="table table-hover">
	<thead>
		<tr>
			<th>Email</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
		
	<?php foreach ($batch as $content) { ?>
		
		<tr>
			<td><?php echo $content['email']; ?></td>
			<td><?php echo $content['name']; ?></td>
			<td><?php echo $content['last_name']; ?></td>
			<?php if ($content['status'] == '1') { ?>
			<td>Crear</td>
			<?php } else { ?>
			<td>Repetido</td>
			<?php } ?>
		</tr>
	
	<?php } ?>
	</tbody>
</table>

<a href="/emarketing/contacts/importbatch/<?php echo $idContactlist; ?>" class="btn btn-inverse">Crear</a>
<a href="/emarketing/contactlist/show/<?php echo $idContactlist; ?>#/contacts/newbatch" class="btn btn-inverse">Cancelar</a>

			</div>
	</div>
</div>



						<!-- Fin de contenido -->
					</div>		
				</div>
			</div>	
    </body>
</html>
