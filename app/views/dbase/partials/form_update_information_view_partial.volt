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
				
			{#Mensaje de Aviso al Contacto#}
				
			<div class="form-group">
				<label for="welcome" class="col-sm-3 control-label">Aviso de Actualización:</label>
				<div class="col-md-9">
					{{' {{input type="checkbox" id="updatenotify" name="updatenotify" checked=updatenotify valueBinding="updatenotify"}}' }}
					<label for="welcome"><span class="text-muted">Esta opción envía un correo al usuario una vez que se haya suscrito!</span></label>
				</div>
			</div>
			{{ '{{#if this.updatenotify }}' }}
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
								{{'{{view Ember.TextField valueBinding="updatenotifysubject" id="updatenotify-mail-subject" class="form-control"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="welcome-mail-from-name" class="col-sm-3 control-label">De: </label>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="updatenotifyfromname" id="updatenotify-mail-from-name" class="form-control" placeholder="Nombre"}}'}}
							</div>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="updatenotifyfromemail" id="updatenotify-mail-from-email" class="form-control" placeholder="example@test.com"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="welcome-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="updatenotifyreplyto" id="updatenotify-mail-reply-to" class="form-control" placeholder="example@test.com"}}'}}
							</div>
						</div>
						<div class="form-group" style="text-align: center;">
							<span class="glyphicon glyphicon-envelope" {{ '{{action "show_editor" "updatenotify"}}' }}></span>
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
								{{'{{view Ember.TextField valueBinding="notifyfromname" id="notify-mail-from-name" class="form-control" placeholder="Nombre"}}'}}
							</div>
							<div class="col-md-3">
								{{'{{view Ember.TextField valueBinding="notifyfromemail" id="notify-mail-from-email" class="form-control" placeholder="example@test.com"}}'}}
							</div>
						</div>
						<div class="form-group">
							<label for="notify-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
							<div class="col-md-6">
								{{'{{view Ember.TextField valueBinding="notifyreplyto" id="notify-mail-reply-to" class="form-control" placeholder="example@test.com"}}'}}
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
			
			{# Botones #}
			<div class="form-actions col-xs-offset-5">
				<div class="col-xs-4">
					<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
				</div>
				<div class="col-xs-4">
					<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action "next" this}}' }}>Siguiente</button>
				</div>
			</div>
			<div class="space"></div>	
			
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
		<button id="btn-for-updatenotify" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_notify_mail" this}}' }} >Guardar</button>
		<button id="btn-for-notify" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_notify_mail" this}}' }} >Guardar</button>
	</div>
</div>