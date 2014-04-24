<div class="row">
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
				<div class="col-md-9">
					{{' {{input type="checkbox" id="optin" name="optin" checked=optin valueBinding="optin"}}' }}
					<label for="optin"><span class="text-muted">Esta opción exige confirmación a través de un correo!</span></label>
				</div>
			</div>
			{{ '{{#if this.optin }}' }}
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
								{{'{{view Ember.TextField valueBinding="optinsubject" id="optin-mail-subject" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="optin-mail-from-name" class="col-sm-3 control-label">De: </label>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="optinfromname" id="optin-mail-from-name" class="form-control"}}'}}
							</div>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="optinfromemail" id="optin-mail-from-email" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="optin-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="optinreplyto" id="optin-mail-reply-to" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group" style="text-align: center;">
							<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "optin"}}' }}></span>
						</div>
						<div class="form-group welcome-url-field">
							<label for="url-welcome" class="col-sm-3 control-label">* URL de Bienvenida:</label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="welcomeurl" id="url-welcome" class="form-control" placeholder="URL de Bienvenida"}}'}}
							</div>
						</div>
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}		


			{#Mensaje de Bienvenida#}
			<div class="form-group">
				<label for="welcome" class="col-sm-3 control-label">Mensaje de Bienvenida:</label>
				<div class="col-md-9">
					{{' {{input type="checkbox" id="welcome" name="welcome" checked=welcome valueBinding="welcome"}}' }}
					<label for="welcome"><span class="text-muted">Esta opción envía un correo al usuario una vez que se haya suscrito!</span></label>
				</div>
			</div>
			{{ '{{#if this.welcome }}' }}
			<div class="welcome-information-container col-sm-offset-1 col-sm-11" style="background-color: #fceedb;">
				<div class="container-fluid">
					<div class="row">
						<div class="bs-callout bs-callout-info">
							<h4>Información de Bienvenida</h4>
							<p>El mensaje de bienvenida es utilizado cuando el usuario se ha suscrito exitosamente
							a la lista, en el cual se enviara un correo con un contenido de bienvenida. Selecciona aquí
							el contenido del correo, el asunto y el origen.
							</p>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="welcome-mail-subject" class="col-sm-3 control-label">Asunto: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="welcomesubject" id="welcome-mail-subject" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="welcome-mail-from-name" class="col-sm-3 control-label">De: </label>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="welcomefromname" id="welcome-mail-from-name" class="form-control"}}'}}
							</div>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="welcomefromemail" id="welcome-mail-from-email" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="welcome-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="welcomereplyto" id="welcome-mail-reply-to" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group" style="text-align: center;">
							<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "welcome"}}' }}></span>
						</div>
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}	


			{#Notificar#}	
			<div class="form-group">
				<label for="notify" class="col-sm-3 control-label">Notificar a: </label>
				<div class="col-md-9">
					{{' {{input type="checkbox" id="notify" name="notify" checked=notify valueBinding="notify"}}' }}
					<label for="notify"><span class="text-muted">Esta opción envía un correo notificando la suscripción de un nuevo contacto!</span></label>
				</div>
			</div>
			{{ '{{#if this.notify }}' }}
			<div class="notify-information-container col-sm-offset-1 col-sm-11" style="background-color: #fceedb;">
				<div class="container-fluid">
					<div class="row">
						<div class="bs-callout bs-callout-info">
							<h4>Información de Notificación</h4>
							<p>Reciba un correo de notificacion cada vez que un usuario se suscriba a su lista
							por medio de este formulario. Selecciona aquí el contenido del correo, el asunto 
							y el origen.
							</p>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label for="notify-mail-subject" class="col-sm-3 control-label">Asunto: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="notifysubject" id="notify-mail-subject" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="notify-mail-from-name" class="col-sm-3 control-label">De: </label>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="notifyfromname" id="notify-mail-from-name" class="form-control"}}'}}
							</div>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="notifyfromemail" id="notify-mail-from-email" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="notify-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="notifyreplyto" id="notify-mail-reply-to" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group" style="text-align: center;">
							<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "notify"}}' }}></span>
						</div>
						<div class="form-group welcome-url-field">
							<label for="email-notify" class="col-sm-3 control-label">* Notificar a:</label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="notifyemail" id="email-notify" class="form-control" placeholder="Direccion de correo"}}'}}
							</div>
						</div>
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}		

				
			{#Listas#}
			<div class="form-group">
				<label for="list-attachment" class="col-sm-3 control-label">Lista: </label>
				<div class="col-md-6">
					{{ '{{view Ember.Select
							contentBinding="selectoflists"
							optionValuePath="content.id"
							optionLabelPath="content.name"
							valueBinding="listselected"
						}}'
					}}
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