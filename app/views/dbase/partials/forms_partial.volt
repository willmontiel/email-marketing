<script type="text/x-handlebars" data-template-name="forms/index">
	
</script>

<script type="text/x-handlebars" data-template-name="forms/setup">
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo formulario</h4>
		<div class="col-md-8 form-setup-content">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="name" class="col-sm-3 control-label">* Nombre:</label>
					<div class="col-md-6">
						<input id="name" class="form-control" placeholder="Nombre" type="text" required="required">
					</div>
				</div>
					
				{#URLs de Redireccionamiento#}
					
				<div class="form-group">
					<label for="url-form" class="col-sm-3 control-label">* URL:</label>
					<div class="col-md-6">
						<input id="url-form" class="form-control" placeholder="URL" type="text" required="required">
					</div>
				</div>
				<div class="form-group">
					<label for="url-success" class="col-sm-3 control-label">* URL de Exito:</label>
					<div class="col-md-6">
						<input id="url-success" class="form-control" placeholder="URL de Exito" type="text" required="required">
					</div>
				</div>
				<div class="form-group">
					<label for="url-error" class="col-sm-3 control-label">* URL de Error:</label>
					<div class="col-md-6">
						<input id="url-error" class="form-control" placeholder=" URL de Error" type="text" required="required">
					</div>
				</div>
					
				{#Doble Optin#}
				<div class="form-group">
					<label for="double-optin" class="col-sm-3 control-label">Doble Optin:</label>
					<div class="col-md-7">
						<label>
							<input id="double-optin" type="checkbox">
						</label>
						<span class="glyphicon glyphicon-envelope"></span>
					</div>
				</div>
					
				{#Mensaje de Bienvenida#}
				<div class="form-group">
					<label for="welcome-msj" class="col-sm-3 control-label">Mensaje de Bienvenida:</label>
					<div class="col-md-7">
						<label>
							<input id="welcome-msj" type="checkbox">
						</label>
						<span class="glyphicon glyphicon-envelope"></span>
					</div>
				</div>
					
				{#Notificar#}	
				<div class="form-group">
					<label for="notify" class="col-sm-3 control-label">Notificar a: </label>
					<div class="col-md-6">
						<input id="notify" class="form-control" placeholder="Direccion de correo" type="text">
					</div>
					<div class="col-md-1">
						<span class="glyphicon glyphicon-envelope"></span>
					</div>
				</div>
				
				{# Botones #}
				<div class="form-actions">
					<div class="row">
						<div class="col-xs-2 col-md-offset-6">
							<button class="btn btn-sm btn-default extra-padding">Cancelar</button>
						</div>
						<div class="col-xs-4">
							<button class="btn btn-sm btn-default btn-guardar extra-padding">Guardar</button>
						</div>
					</div>
				</div>
					
			</form>
		</div>
	</div>
	<div class="here-comes-frame">
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
	<br />
	<br />
	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class="form-full-content">
			
			</div>
			<div onclick="sendFormData()" class="btn btn-default btn-sm">
				<span>Crear Formulario</span>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-menu">
			
			</div>
		</div>
	</div>
</script>