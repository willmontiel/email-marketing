<script type="text/x-handlebars" data-template-name="forms/index">
	<div class="row">
		<h4 class="sectiontitle">Formularios</h4>
		<table class="table table-condensed table-striped table-contacts">
			{{'{{#each model}}'}}
			<tr>
				<td>
					<div>
						{{'{{name}}'}}
					</div>
				</td>
				<td>
					<div>
						{{ '{{#link-to "forms.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}<i class="glyphicon glyphicon-pencil"></i> Editar{{ '{{/link-to}}' }}
					</div>
				</td>
				<td>
					<div>
						{{ '{{#link-to "forms.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete"}}' }}<i class="glyphicon glyphicon-trash"></i> Eliminar{{ '{{/link-to}}' }}
					</div>
				</td>
			</tr>
			{{'{{/each}}'}}
		</table>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/setup">
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo formulario</h4>
		<div class="col-md-8 form-setup-content">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="name" class="col-sm-3 control-label">* Nombre:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="name" id="name" class="form-control" placeholder="Nombre"}}'}}
					</div>
				</div>
					
				{#URLs de Redireccionamiento#}
					
				<div class="form-group">
					<label for="url-success" class="col-sm-3 control-label">* URL de Exito:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="urlsuccess" id="url-success" class="form-control" placeholder="URL de Exito"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="url-error" class="col-sm-3 control-label">* URL de Error:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="urlerror" id="url-error" class="form-control" placeholder="URL de Error"}}'}}
					</div>
				</div>
					
				{#Doble Optin#}
				<div class="form-group">
					<label for="optin" class="col-sm-3 control-label">Doble Optin:</label>
					<div class="col-md-7">
						{{' {{input type="checkbox" id="optin" name="optin" checked=optin valueBinding="optin"}}' }}
						<label for="optin"><span class="text-muted">Esta opción exige confirmación a través de un correo!</span></label>
					</div>
				</div>
				
				<div class="optin-information-container col-sm-offset-1 col-sm-11" style="background-color: #fceedb;">
					<div class="container-fluid">
						<div class="row">
							<div class="bs-callout bs-callout-info">
								<h4>Información de Doble Optin</h4>
								<p>El doble optin requiere que el usuario que se suscribe, confirme 
								su suscripción haciendo clic en un enlace enviado por correo. Selecciona aquí
								el contenido del correo, el asunto, origen y la página que se mostrará al contacto
								cuando confirme.
								</p>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<label for="optin-mail-subject" class="col-sm-3 control-label">Asunto: </label>
								<div class="col-md-6">
									<input type="text" id="optin-mail-subject" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="optin-mail-from-name" class="col-sm-3 control-label">De: </label>
								<div class="col-md-3">
									<input type="text" id="optin-mail-from-name" class="form-control">
								</div>
								<div class="col-md-3">
									<input type="text" id="optin-mail-from-email" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="optin-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
								<div class="col-md-6">
									<input type="text" id="optin-mail-reply-to" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "optin"}}' }}></span>
							</div>
							<div class="form-group welcome-url-field">
								<label for="url-welcome" class="col-sm-3 control-label">* URL de Bienvenida:</label>
								<div class="col-md-6">
									{{'{{view Ember.TextField valueBinding="urlwelcome" id="url-welcome" class="form-control" placeholder="URL de Bienvenida"}}'}}
								</div>
							</div>
						</div>
					</div>
				</div>
					
				{#Mensaje de Bienvenida#}
				<div class="form-group">
					<label for="welcome" class="col-sm-3 control-label">Mensaje de Bienvenida:</label>
					<div class="col-md-7">
						<label>
							{{' {{input type="checkbox" id="welcome" name="welcome" checked=welcome valueBinding="welcome"}}' }}
						</label>
						<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "welcome"}}' }}></span>
					</div>
				</div>
					
				{#Notificar#}	
				<div class="form-group">
					<label for="notify" class="col-sm-3 control-label">Notificar a: </label>
					<div class="col-md-6">
						{#<input id="notify" class="form-control" placeholder="Direccion de correo" type="text">#}
						{{'{{view Ember.TextField valueBinding="notify" id="notify" class="form-control" placeholder="Direccion de correo"}}'}}
					</div>
					<div class="col-md-1">
						<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "notify"}}' }} ></span>
					</div>
				</div>
				
				{# Botones #}
				<div class="form-actions">
					<div class="row">
						<div class="col-xs-2 col-md-offset-6">
							<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
						</div>
						<div class="col-xs-4">
							<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action "next" this}}' }}>Siguiente</button>
						</div>
					</div>
				</div>
					
			</form>
		</div>
	</div>
	<div class="create-email-spot">
		<div class="title-advanced-editor">
		</div>
		<div class="here-comes-frame">
		</div>
		<div class="advanced-editor-buttons pull-right">
			<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cleanEditor"}}' }} >Cancelar</button>
			<button id="btn-for-optin" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_optin_mail" this}}' }} >Guardar</button>
			<button id="btn-for-welcome" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_welcome_mail" this}}' }} >Guardar</button>
			<button id="btn-for-notify" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_notify_mail" this}}' }} >Guardar</button>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
	<br />
	<br />
	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class="form-full-content">
			
			</div>
			<div class="form-full-button col-md-offset-8">
				
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-menu">
			
			</div>
		</div>
	</div>
	<div class="row">
		<div class="btn btn-default btn-sm">
			<span {{ '{{action "cancel" this}}' }}>Cancelar</span>
		</div>
		<div class="btn btn-default btn-sm">
			<span {{ '{{action "sendData" this}}' }}>Crear Formulario</span>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/edit">
	<div class="row">
		<h4 class="sectiontitle">Editar formulario</h4>
		<div class="col-md-8 form-setup-content">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label for="name" class="col-sm-3 control-label">* Nombre:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="name" id="name" class="form-control"}}'}}
					</div>
				</div>
					
				{#URLs de Redireccionamiento#}
					
				<div class="form-group">
					<label for="url-success" class="col-sm-3 control-label">* URL de Exito:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="urlsuccess" id="url-success" class="form-control" placeholder="URL de Exito"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="url-error" class="col-sm-3 control-label">* URL de Error:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="urlerror" id="url-error" class="form-control" placeholder="URL de Error"}}'}}
					</div>
				</div>
					
				{#Doble Optin#}
				<div class="form-group">
					<label for="optin" class="col-sm-3 control-label">Doble Optin:</label>
					<div class="col-md-7">
						<label>
							{{' {{input type="checkbox" id="optin" name="optin" checked=optin valueBinding="optin"}}' }}
						</label>
						<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "optin"}}' }}></span>
					</div>
				</div>
				
				<div class="form-group welcome-url-field">
					<label for="url-welcome" class="col-sm-3 control-label">* URL de Bienvenida:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="urlwelcome" id="url-welcome" class="form-control" placeholder="URL de Bienvenida"}}'}}
					</div>
				</div>
					
				{#Mensaje de Bienvenida#}
				<div class="form-group">
					<label for="welcome" class="col-sm-3 control-label">Mensaje de Bienvenida:</label>
					<div class="col-md-7">
						<label>
							{{' {{input type="checkbox" id="welcome" name="welcome" checked=welcome valueBinding="welcome"}}' }}
						</label>
						<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "welcome"}}' }}></span>
					</div>
				</div>
					
				{#Notificar#}	
				<div class="form-group">
					<label for="notify" class="col-sm-3 control-label">Notificar a: </label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="notify" id="notify" class="form-control" placeholder="Direccion de correo"}}'}}
					</div>
					<div class="col-md-1">
						<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "notify"}}' }} ></span>
					</div>
				</div>
				
				{# Botones #}
				<div class="form-actions">
					<div class="row">
						<div class="col-xs-2 col-md-offset-6">
							<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
						</div>
						<div class="col-xs-4">
							<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action "next" this}}' }}>Siguiente</button>
						</div>
					</div>
				</div>
					
			</form>
		</div>
	</div>
	<div class="create-email-spot">
		<div class="title-advanced-editor">
		</div>
		<div class="here-comes-frame">
		</div>
		<div class="advanced-editor-buttons pull-right">
			<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cleanEditor"}}' }} >Cancelar</button>
			<button id="btn-for-optin" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_optin_mail" this}}' }} >Guardar</button>
			<button id="btn-for-welcome" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_welcome_mail" this}}' }} >Guardar</button>
			<button id="btn-for-notify" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_notify_mail" this}}' }} >Guardar</button>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/remove">
	<div class="row">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Eliminar un formulario
					</div>
				</div>
				<div class="box-content padded">			
					<p>¿Esta seguro que desea eliminar el Formulario <strong>{{'{{name}}'}}</strong>?</p>
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					<button {{'{{action eliminate this}}'}} class="btn btn-danger">Eliminar</button>
					<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>	
				</div>
			</div>
		</div>	
</script>