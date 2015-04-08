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
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-group">
				<label for="welcome" class="col-sm-3 control-label">Aviso de Actualización:</label>
				<div class="checkbox-inline mleft-15">
					<label for="welcome">
						{{' {{input type="checkbox" id="updatenotify" name="updatenotify" checked=updatenotify valueBinding="updatenotify"}}' }}
						<span class="text-muted">Esta opción envía un correo al contacto una vez que haya editado sus datos!</span>
					</label>
				</div>
			</div>
			{{ '{{#if this.updatenotify }}' }}
			<div class="welcome-information-container col-sm-offset-1 col-sm-11" style="background-color: #fceedb;">
				<div class="container-fluid">
					<div class="row">
						<div class="bs-callout bs-callout-info">
							<h4>Información de Actualización</h4>
							<p>El mensaje de actualización es utilizado cuando el contacto ha editado su información 
								correctamente.  Seleccione aquí el contenido del correo, el asunto y el origen.								
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
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Contenido del correo: </label>
							<a href="#" {{ '{{action "show_editor" "updatenotify"}}' }} class=""><img src="{{url('vendors/bootstrap_v3/images/icon-edit-avanz.png')}}"  /></a>
						</div>
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}		
				
			{#Notificar#}
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-group">
				<label for="notify" class="col-sm-3 control-label">Notificar a: </label>
				<div class="checkbox-inline mleft-15">
					<label for="notify">
						{{' {{input type="checkbox" id="notify" name="notify" checked=notify valueBinding="notify"}}' }}
						<span class="text-muted">Esta opción envía un correo notificando la actualización de un contacto!</span>
					</label>
				</div>
			</div>
			{{ '{{#if this.notify }}' }}
			<div class="notify-information-container col-sm-offset-1 col-sm-11" style="background-color: #fceedb;">
				<div class="container-fluid">
					<div class="row">
						<div class="bs-callout bs-callout-info">
							<h4>Información de Notificación</h4>
							<p>Reciba un correo de notificación cada vez que un usuario se actualice
							por medio de este formulario. Seleccione aquí el contenido del correo, el asunto 
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
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Contenido del correo: </label>
							<a href="#" {{ '{{action "show_editor" "notify"}}' }} class=""><img src="{{url('vendors/bootstrap_v3/images/icon-edit-avanz.png')}}"  /></a>
						</div>
						<div class="form-group welcome-url-field">
							<label for="email-notify" class="col-sm-3 control-label">* Notificar a:</label>
							<div class="col-md-6">
								{{'{{view App.TagsInput valueBinding="notifyemail" id="email-notify" class="form-control" placeholder="Direccion de correo"}}'}}
							</div>
						</div>
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}	
			
			{#Dbase#}
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-group">
				<label for="list-attachment" class="col-sm-3 control-label">Base de datos: </label>
				<div class="col-md-6">
					{{ '{{view Ember.Select
							contentBinding="selectofdbases"
							optionValuePath="content.id"
							optionLabelPath="content.name"
							valueBinding="dbaseselected"
							selectionBinding="dbaseselectedfield"
							prompt="Seleccione una base de datos"
							class="form-control"
						}}'
					}}
				</div>
			</div>	
			
			
			{# Botones #}
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-actions">
					<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
					<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action "next" this}}' }}>Siguiente</button>
			</div>
			<div class="space"></div>
			
		</form>
	</div>
</div>
<div class="create-email-spot" style="display: none;">
	<form class="form-inline" role="form">
		<div class="title-advanced-editor form-group">
		</div>
		<div class="form-group">
			<a class="btn btn-default extra-padding" data-toggle="modal" href="#preview-modal" onClick="verHTML()"><span class="glyphicon glyphicon-search"></span> Previsualizar</a>
		</div>
	</form>
	<div class="here-comes-frame">
	</div>
	<div class="advanced-editor-buttons pull-right">
		<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cleanEditor"}}' }} >Cancelar</button>
		<button id="btn-for-updatenotify" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_notify_contact_mail" this}}' }} >Guardar</button>
		<button id="btn-for-notify" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_notify_mail" this}}' }} >Guardar</button>
	</div>
</div>

<div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-prevew-width">
	<div class="modal-content modal-prevew-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Previsualización de plantilla</h4>
		  </div>
	  <div class="modal-body modal-prevew-body" id="modal-body-preview">
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	  </div>
	</div>
  </div>
</div>