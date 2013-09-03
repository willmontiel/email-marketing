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
		
		<?php echo $this->partial('partials/ember_partial'); ?>
		<?php echo Phalcon\Tag::javascriptInclude('js/mixin_pagination.js'); ?>


<script type="text/javascript">
	var MyDbaseUrl = 'emarketing/api/import/<?php echo $idContactlist; ?>';

	var myImportModel = {
		datas: DS.attr( 'string' ),
		email: DS.attr( 'string' ),	
		name: DS.attr( 'string' ),
		lastname: DS.attr( 'string' ),
		delimiter: DS.attr( 'string' )
		<?php foreach ($customfields as $field) { ?>
			,
			<?php echo Phalcon\Text::lower($field->name); ?>: DS.attr('string')
		<?php } ?>
	};
</script>

<?php echo Phalcon\Tag::javascriptInclude('js/app_import.js'); ?>
<script type="text/javascript">
	
	App.originalF = "<?php echo $row[0]; ?>";
	App.originalS = "<?php echo $row[1]; ?>";
	App.originalT = "<?php echo $row[2]; ?>";
	App.originalFo = "<?php echo $row[3]; ?>";
	App.originalFi = "<?php echo $row[3]; ?>";
	App.optionsOr = " ,<?php echo $row[0]; ?>"
	
	App.options = App.optionsOr.split(",");
	
	App.firstline = App.originalF.split(",");
	
	App.secondline = App.originalS.split(",");
	
	App.thirdline = App.originalT.split(",");
	
	App.fourthline = App.originalFo.split(",");
	
	App.fifthline = App.originalFi.split(",");
</script>



		
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
						
<div id="emberAppImportContainer">
	<script type="text/x-handlebars" data-template-name="contacts/index">
		<div class="row-fluid">
			<div class="span5">
				<table class="contact-info">
					<tbody>
						<tr>		
							<td>Email</td>
							<td>
								<?php echo '{{ view Ember.Select contentBinding="App.options" valueBinding="email" id="email" class="select"}}'; ?>
							</td>
						</tr>
						<tr>		
							<td>Nombre</td>
							<td>
								<?php echo '{{ view Ember.Select contentBinding="App.options" valueBinding="name" id="name"}}'; ?>
							</td>
						</tr>
						<tr>		
							<td>Apellido</td>
							<td>
								<?php echo '{{ view Ember.Select contentBinding="App.options" valueBinding="lastname" id="lastname"}}'; ?>
							</td>
						</tr>
						<?php foreach ($customfields as $field) { ?>
						<tr>		
							<td><?php echo $field->name; ?></td>
							<td>
								<?php echo '{{ view Ember.Select contentBinding="App.options" valueBinding="' . Phalcon\Text::lower($field->name) . '" id="' . Phalcon\Text::lower($field->name) . '"}}'; ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<div class="span3">
					Delimitador:
					<?php echo ' {{view App.delimiterView valueBinding="delimiter" contentBinding="content"}} '; ?>
				</div>
			</div>
			<div class="span5">
				<p>Email: <?php echo '{{email}}'; ?></p>
				<p>Nombre: <?php echo '{{name}}'; ?></p>
				<p>Apellido: <?php echo '{{lastname}}'; ?></p>
				<?php foreach ($customfields as $field) { ?>
				<p><?php echo $field->name; ?>: <?php echo '{{' . Phalcon\Text::lower($field->name) . '}}'; ?></p>
				<?php } ?>
				
			</div>
		</div>
		<div class="row-fluid">
			<div class="span8">
				<table class="table table-striped">
					<tr>
						<?php echo ' {{#each App.firstline}} '; ?>
							<td><?php echo ' {{this}} '; ?></td>
						<?php echo ' {{/each}} '; ?>
					</tr>
					<tr>
						<?php echo ' {{#each App.secondline}} '; ?>
							<td><?php echo ' {{this}} '; ?></td>
						<?php echo ' {{/each}} '; ?>
					</tr>
					<tr>
						<?php echo ' {{#each App.thirdline}} '; ?>
							<td><?php echo ' {{this}} '; ?></td>
						<?php echo ' {{/each}} '; ?>
					</tr>
					<tr>
						<?php echo ' {{#each App.fourthline}} '; ?>
							<td><?php echo ' {{this}} '; ?></td>
						<?php echo ' {{/each}} '; ?>
					</tr>
					<tr>
						<?php echo ' {{#each App.fifthline}} '; ?>
							<td><?php echo ' {{this}} '; ?></td>
						<?php echo ' {{/each}} '; ?>
					</tr>
				</table>
			</div>
		</div>
	</script>
	
	<script type="text/x-handlebars" data-template-name="select">
		<?php echo ' {{view App.DelimiterView name="delimiter" contentBinding="App.delimiter_opt"}} '; ?>
	</script>
	
	<script type="text/x-handlebars" data-template-name="contacts">
		<?php echo ' {{outlet}} '; ?>
	</script>

</div>

						<!-- Fin de contenido -->
					</div>		
				</div>
			</div>	
    </body>
</html>
