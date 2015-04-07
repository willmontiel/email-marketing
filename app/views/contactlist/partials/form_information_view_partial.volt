		<form class="form-horizontal form-setup-content" role="form">
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
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-group">
				<label for="optin" class="col-sm-3 control-label">Doble Optin:</label>
				<div class="checkbox-inline mleft-15">
					<label for="optin">
						{{' {{input type="checkbox" id="optin" name="optin" checked=optin valueBinding="optin"}}' }}
						<span class="text-muted">Esta opción exige confirmación a través de un correo!</span>
					</label>
				</div>
			</div>
			{{ '{{#if this.optin }}' }}
			<div class="container-fluid col-sm-offset-1 col-sm-9" style="background-color: #fceedb;">
				<div class="bs-callout bs-callout-info">
					<h4>Información de doble optin</h4>
					<p>El doble optin requiere que el usuario que se suscriba, confirme 
					su suscripción haciendo clic en un enlace enviado por correo. Seleccione aquí
					el contenido del correo, el asunto, origen y la página que se mostrará al contacto
					cuando confirme.
					</p>
				</div>
				<div class="form-group">
					<label for="optin-mail-subject" class="col-sm-3 control-label">Asunto: </label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="optinsubject" id="optin-mail-subject" class="form-control"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="optin-mail-from-name" class="col-sm-3 control-label">De: </label>
					<div class="col-md-3">
						{{'{{view Ember.TextField valueBinding="optinfromname" id="optin-mail-from-name" class="form-control" placeholder="Nombre"}}'}}
					</div>
					<div class="col-md-3">
						{{'{{view Ember.TextField valueBinding="optinfromemail" id="optin-mail-from-email" class="form-control" placeholder="example@test.com"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="optin-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="optinreplyto" id="optin-mail-reply-to" class="form-control" placeholder="example@test.com"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Contenido del correo: </label>
					<a href="#" {{ '{{action "show_editor" "optin"}}' }} class="" ><img src="{{url('vendors/bootstrap_v3/images/icon-edit-avanz.png')}}"  /></a>
				</div>
				<div class="form-group welcome-url-field">
					<label for="url-welcome" class="col-sm-3 control-label">* URL de bienvenida:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="welcomeurl" id="url-welcome" class="form-control" placeholder="URL de Bienvenida"}}'}}
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}		


			{#Mensaje de Bienvenida#}
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-group">
				<label for="welcome" class="col-sm-3 control-label">Mensaje de bienvenida:</label>
				<div class="checkbox-inline mleft-15">
					<label for="welcome">
						{{' {{input type="checkbox" id="welcome" name="welcome" checked=welcome valueBinding="welcome"}}' }}
						<span class="text-muted">Esta opción envía un correo al usuario una vez que se haya suscrito!</span>
					</label>
				</div>
			</div>
			{{ '{{#if this.welcome }}' }}
			<div class="container-fluid col-sm-offset-1 col-sm-9" style="background-color: #fceedb;">
				<div class="bs-callout bs-callout-info">
					<h4>Información de bienvenida</h4>
					<p>El mensaje de bienvenida es utilizado cuando el usuario se ha suscrito exitosamente
					a la lista. Seleccione aquí el contenido del correo, el asunto y el origen.</p>
				</div>
				<div class="form-group">
					<label for="welcome-mail-subject" class="col-sm-3 control-label">Asunto: </label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="welcomesubject" id="welcome-mail-subject" class="form-control"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="welcome-mail-from-name" class="col-sm-3 control-label">De: </label>
					<div class="col-md-3">
						{{'{{view Ember.TextField valueBinding="welcomefromname" id="welcome-mail-from-name" class="form-control" placeholder="Nombre"}}'}}
					</div>
					<div class="col-md-3">
						{{'{{view Ember.TextField valueBinding="welcomefromemail" id="welcome-mail-from-email" class="form-control" placeholder="example@test.com"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="welcome-mail-reply-to" class="col-sm-3 control-label">Responder a: </label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="welcomereplyto" id="welcome-mail-reply-to" class="form-control" placeholder="example@test.com"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-3 control-label">Contenido del correo: </label>
					<a href="#" {{ '{{action "show_editor" "welcome"}}' }} class="" ><img src="{{url('vendors/bootstrap_v3/images/icon-edit-avanz.png')}}"  /></a>
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
						<span class="text-muted">Esta opción envía un correo notificando la suscripción de un nuevo contacto!</span>
					</label>
				</div>
			</div>
			{{ '{{#if this.notify }}' }}
			<div class="container-fluid col-sm-offset-1 col-sm-9" style="background-color: #fceedb;">
				<div class="bs-callout bs-callout-info">
					<h4>Información de notificación</h4>
					<p>Reciba un correo de notificación cada vez que un usuario se suscriba a su lista
					por medio de este formulario. Seleccione aquí el contenido del correo, el asunto 
					y el origen.
					</p>
				</div>
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
					<a href="#" {{ '{{action "show_editor" "notify"}}' }} class="" ><img src="{{url('vendors/bootstrap_v3/images/icon-edit-avanz.png')}}"  /></a>
				</div>
				<div class="form-group welcome-url-field">
					<label for="email-notify" class="col-sm-3 control-label">* Notificar a:</label>
					<div class="col-md-6">
						{{'{{view Ember.TextField valueBinding="notifyemail" id="email-notify" data-role="tagsinput" class="form-control" placeholder="Direccion de correo"}}'}}
					</div>
				</div>
			</div>
			{{ '{{/if}} '}}		

				
			{#Listas#}
			<div class="clearfix"></div>
			<div class="space"></div>
			<div class="form-group">
				<label for="list-attachment" class="col-sm-3 control-label">Lista: </label>
				<div class="col-md-6">
					{{ '{{view Ember.Select
							contentBinding="selectoflists"
							optionValuePath="content.id"
							optionLabelPath="content.name"
							valueBinding="listselected"
							selectionBinding="listselectedfield"
							prompt="Seleccione una lista"
							class="form-control"
						}}'
					}}
				</div>
			</div>	
				
				
			{# Botones #}
			<div class="form-actions col-xs-offset-5">
				<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
				<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action "next" this}}' }}>Siguiente</button>
			</div>
			<div class="space"></div>
		</form>
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
	<button id="btn-for-optin" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_optin_mail" this}}' }} >Guardar</button>
	<button id="btn-for-welcome" class="btn btn-sm btn-default btn-guardar extra-padding btn-form-email-creator-save" {{ '{{action "create_welcome_mail" this}}' }} >Guardar</button>
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
