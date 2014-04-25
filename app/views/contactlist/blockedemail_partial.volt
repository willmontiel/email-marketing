<script type="text/x-handlebars" data-template-name="blockedemails/index">
			{# Insertar botones de navegacion #}
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'blockedemails']) }}


	<div class="row">
		<h4 class="sectiontitle">Lista de bloqueo</h4>
			
		
		<p class="bs-callout bs-callout-warning">
			Esta es la lista global de direcciones de correo electrónico bloqueados, ninguna dirección de correo
			que esté listada abajo, esta recibiendo campañas de envío.
		</p>
		<div class="col-md-3 col-md-offset-8">
			{{ '{{#link-to "blockedemails.block" disabledWhen="createDisabled" class="btn btn-danger btn-delete btn-sm extra-padding"}}Bloquear un correo electronico{{/link-to}}' }}
		</div>
	</div>
	<div class="space"></div>
	<div class="row">
		<table class="table table-striped table-contacts">
			<thead>
				<tr>
					<td>
						Email
					</td>
					<td class="span3">
						Fecha
					</td>
					<td class="span5">
						Razón de bloqueo
					</td>
					<td class="span1">
						Acciones
					</td>
				</tr>
			</thead>
			</tbody>
				{{ '{{#each model}}' }}
					<tr>
						<td>{{ '{{email}}' }}</td>
						<td>{{'{{blockedDate}}' }}</td>
						<td>{{ '{{blockedReason}}'}}</td>
						<td>
							{{'{{#link-to "blockedemails.unblock" this disabledWhen="controller.deleteDisabled" class="btn-default btn-sm"}}Desbloquear{{/link-to}}'}}
						</td>
					</tr>
				{{ '{{else}}' }}
					<tr>
						<td celspadding="4">No hay direcciones de correo bloqueadas</td>
					</tr>
				{{ '{{/each }}' }}
			</tbody>
		</table>
	</div>
	<div class="row">
		{{ partial("partials/pagination_partial") }}
	</div>
</script>
<script type="text/x-handlebars" data-template-name="blockedemails">
	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="blockedemails/block">
	<div class="row">
		<h4 class="sectiontitle">Bloqueo de direcciones de correo</h4>

		{{ '{{#if App.errormessage }}' }}
			<div class="alert alert-message alert-error">
		{{ '{{ App.errormessage }}' }}
			</div>
		{{ '{{/if}} '}}	
		{{'{{#if errors.errormsg}}'}}
			<div class="alert alert-error">
				{{'{{errors.errormsg}}'}}
			</div>
		{{'{{/if}}'}}
		<div class="col-sm-12 hidden-md hidden-lg">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Cuando bloquee una dirección de correo, éste se marcará como des-suscrito, y por consiguiente será omitido de todas las listas y bases de datos de la cuenta.</p>
					
						<p>Configure el sistema para eliminar los contactos asociados a esa dirección por medio de una casilla de verificación, si no se configura esa opción, por defecto se marcarán como des-suscritos y en el futuro podrá revertirlo.</p>

						<p>Si la dirección de correo electrónico que ingrese no existe, se creará automáticamente como bloqueada
						y luego podrá desbloquearla, e ingresar los datos necesarios para crear un contacto para vincularlo
						a esta dirección de correo.</p>
					</div>
				</div>
			</div>
		</div>
		<form  class="form-horizontal" role="form">
			<div class="col-md-5">
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">* Dirección de correo electronico:</label>
						<div class="col-md-8">
							{{ '{{view Ember.TextField valueBinding="email" placeholder="Email" required="required" autofocus="autofocus" class="form-control"}}' }}
						</div>
				</div>
				<div class="form-group">
					<label for="" class="col-sm-4 control-label">* Motivo del bloqueo:</label>
						<div class="col-md-8">
							{{ '{{view Ember.TextArea valueBinding="blockedReason" placeholder="Motivo del bloqueo" required="required" class="form-control"}}' }}
						</div>
				</div>
				<div class="space"></div>
				<div class="bs-callout bs-callout-danger">
					<p>Si está seguro dele click al botón <strong>Bloquear</strong> para continuar.</p>
				</div>
				<p>{{ '{{view Ember.Checkbox checkedBinding="deleteContact" id="deleteContact" class="icheckbox_flat-aero hover"}}' }} Eliminar contactos asociados al email</p>
				<div class="form-actions pull-right">
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-default btn-sm extra-padding" {{ '{{action "cancel" this }}' }}>Cancelar</button>
						</div>
						<div class="col-xs-6">
							<button class="btn btn-danger btn-sm extra-padding" {{ '{{action "block" this }}' }}>Bloquear</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="hidden-xs hidden-sm col-md-5">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Cuando bloquee una dirección de correo, éste se marcará como des-suscrito, y por consiguiente será omitido de todas las listas y bases de datos de la cuenta.</p>
					
						<p>Configure el sistema para eliminar los contactos asociados a esa dirección por medio de una casilla de verificación, si no se configura esa opción, por defecto se marcarán como des-suscritos y en el futuro podrá revertirlo.</p>

						<p>Si la dirección de correo electrónico que ingrese no existe, se creará automáticamente como bloqueada
						y luego podrá desbloquearla, e ingresarlos datos necesarios para crear un contacto para vincularlo
						a esta dirección de correo.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="blockedemails/unblock">
	<div class="row">
		<h4 class="sectiontitle">Desbloquear Contactos</h4>
		<div class="bs-callout bs-callout-danger">
			<p>Usted va a desbloquear la dirección de correo electrónico <strong>{{'{{this.email}}'}}</strong></p>
				Recuerde que al desbloquearla si hay información de contacto, se marcará como des-suscrito y por ende 
				será un contacto in-activo en sus listas y bases de datos.</p>

			<p>Además si usted marcó la opción <strong>borrar todos los contactos relacionados con esta dirección de correo
				electrónico</strong> al momento de bloquearla, deberá crear el contacto de nuevo</p>

			<p>Click en <strong>desbloquear</strong> si desea continuar</p>

			{{'{{#if errors.errormsg}}'}}
				<br />
				<div class="alert alert-error">
					{{'{{errors.errormsg}}'}}
				</div>
			{{'{{/if}}'}}
		</div>
		<div class="col-xs-6">
	 		<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar </button>
			<button class="btn btn-guardar btn-sm extra-padding" {{'{{action unblock this}}'}} >Desbloquear</button>
		</div>
	</div>
</script>