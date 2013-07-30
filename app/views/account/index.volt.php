<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php echo Phalcon\Tag::getTitle(); ?>
        <?php echo Phalcon\Tag::stylesheetLink('bootstrap/css/bootstrap.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('bootstrap/css/bootstrap-responsive.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/style.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/flat-ui.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/bootstrap-modal.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/prstyles.css'); ?>
		<?php echo Phalcon\Tag::stylesheetLink('css/normalize.css'); ?>
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
		<?php echo Phalcon\Tag::javascriptInclude('js/app.js'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Proyect">
        <meta name="author" content="Ivan">

        <style>

        </style>
    </head>
    <body>
			<!-- Comentario - remover -->
			<div class="container-fluid">
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
							
<div class="container-fluid"> 
<?php echo $this->getContent(); ?> 
   <div class="row-fluid">
 		<h1>DashBoard Cuentas</h1>
  </div>

  <div class="text-right">
   <h3><a href="account/new" >Crear nueva cuenta</a></h3>
  </div>

  <div class="row-fluid">
 	 <div class="span12" >
 		<table class='table table-striped'>
 			<tr>
 				<th>Id</th>
 				<th>Nombre de la cuenta</th>
 				<th>Modo de uso</th>
 				<th>Cantidad de trafico de archivos (Mb):</th>
 				<th>Limite de mensajes/contactos</th>
 				<th>Modo de pago</th>
				<th></th>
 			</tr>
 		 <?php foreach ($allAccount as $all) { ?>
 			<tr>
 				<td><?php echo $all->idAccount; ?></td>
				<td><?php echo $all->companyName; ?></a></td>
 				<td><?php echo $all->modeUse; ?></td>
 				<td><?php echo $all->fileSpace; ?></td>
 				<td><?php echo $all->messageQuota; ?></td>
 				<td><?php echo $all->modeAccounting; ?></td>
				<td>
				 <a href="account/show/<?php echo $all->idAccount; ?>">Ver</a><br>
				 <a href="account/edit/<?php echo $all->idAccount; ?>">Editar</a><br>
				 <a href="#delete<?php echo $all->idAccount; ?>" data-toggle="modal">Eliminar</a>
				</td>
 			</tr>
 		 <?php } ?>
 	    </table>
 	 </div>
	 <div class="span12"></div>
	 <div class="text-right">
		 <?php echo Phalcon\Tag::linkTo(array('emarketing', 'class' => 'btn btn-inverse', 'Regresar')); ?>
	 </div>
    </div>

<?php foreach ($allAccount as $all) { ?>
<div id="delete<?php echo $all->idAccount; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Seguro que Desea Eliminar</h3>
	</div>

	<form action="/emarketing/account/delete/<?php echo $all->idAccount; ?>" method="post">
		<div class="modal-body">
			<p>Para eliminar escriba la palabra "DELETE"</p>
			<?php echo Phalcon\Tag::textField(array('delete')); ?>
		</div>

		<div class="modal-footer">
			<button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Cerrar</button>
			<button class="btn btn-danger">Eliminar</button>
		</div>
	</form>
</div>
<?php } ?>
</div>

							<!-- Fin de contenido -->
						</div>		
					</div>
				</div>	
    </body>
</html>
