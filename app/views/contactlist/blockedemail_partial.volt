<script type="text/x-handlebars" data-template-name="blockedemails/index">
	<div class="row-fluid">
		<div class="span10">
			<div class="padded">
				Esta es la lista global de direcciones de correo electrónico bloqueados, ninguna dirección de correo
				que esté listada abajo, esta recibiendo campañas de envío.
			</div>
		</div>
		<div class="span2 text-right">
			<div class="padded">
				{{ '{{#linkTo "blockedemails.block" }}' }}<button class="btn btn-danger">Bloquear</button>{{ '{{/linkTo}}' }}
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Bloquear dirección de correo electrónico
					</div>
				</div>
				<div class="box-content">
					<div class="padded">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="span3">
										Email
									</th>
									<th class="span3">
										Fecha
									</th>
									<th class="span5">
										Razón de bloqueo
									</th>
									<th class="span1">
										Acciones
									</th>
								</tr>
							</thead>
							</tbody>
								{{ '{{#each model}}' }}
									<tr>
										<td>{{ '{{email}}' }}</td>
										<td>{{'{{blockedDate}}' }}</td>
										<td>{{ '{{blockedReason}}'}}</td>
										<td>
											{{'{{#linkTo "blockedemails.unblock" this}}'}} Desbloquear {{'{{/linkTo}}'}}
										</td>
									</tr>
								{{ '{{/each }}' }}
							</tbody>
						</table>
					</div>
				</div>
				<div class="box-footer">
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="blockedemails">
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
	{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}	

	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="blockedemails/block">
	<div class="row-fluid">
		<div class="padded">
			<div class="span12">
				<h4>Bloquear direcciones de correo</h4>
				<p>
					Aqui podrá bloquear direcciones de correo, Cuando bloquee una dirección de
					correo, este se marcará como des-suscrito, y por consiguiente sera omitido de todas las listas y bases de datos de 
					la cuenta al realizar una campaña de envío. 
				</p>
				<p>
					Al intentar bloquear direcciones de correo, el sistema preguntará si desea eliminar los contactos 
					que pertenecen a esa dirección, si no se selecciona esa opción, por defecto se marcarán como des-suscritos
					y en futuro podrá revertirlo.
				</p>
				<p>
					Si la dirección de correo electrónico que ingrese no existe, se creará automáticamente como bloqueada
					y luego podrá desbloquearla, e ingresara los datos necesarios para crear un contacto para vincularlo
					a esta dirección de correo.
				</p>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="padded">
			<div class="span4">
				<div class="box">
					<div class="box-header">
						<div class="title">
							Bloquear direccion de correo electrónico
						</div>
					</div>
					<div class="box-content">
						<div class="padded">
							<form>
								<label>*Dirección de correo electronico: </label>
								{{ '{{view Ember.TextField valueBinding="email" placeholder="Escribe el Email aquí" required="required" autofocus="autofocus"}}' }}

								<label>*Escriba la razón por la cual esta bloqueando esta dirección de correo electrónico: </label>
								{{ '{{view Ember.TextArea valueBinding="blockedReason" placeholder="Escribe la razón de bloqueo" required="required"}}' }}
								<p>Si estas seguro dale click al botón <strong>Bloquear</strong> para continuar.</p>
								<p>{{ '{{view Ember.Checkbox checkedBinding="deleteContact" id="deleteContact" class="icheck"}}' }} Eliminar contactos asociados al email</p>
								<button class="btn btn-danger" {{ '{{action block this }}' }}>Bloquear</button>
								<button class="btn btn-inverse" {{ '{{action cancel this }}' }}>Cancelar</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="blockedemails/unblock">
	<div class="row-fluid">
		<div class="span8">
			<h2>Desbloquear Contactos</h2>
		</div>
		<div class="text-right span4">
			{{ '{{#linkTo "blockedemails.index" }}' }}<button class="btn btn-inverse">Regresar</button>{{ '{{/linkTo}}' }}
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span8">
			<p>
				Usted va a desbloquear la dirección de correo electrónico <strong>{{'{{this.email}}'}}</strong>
			</p>
			<p>
				Recuerde que al desbloquearla si hay información de contacto, se marcará como des-suscrito y por ende 
				será un contacto in-activo en 
				sus listas y bases de datos, 
				Además si usted marcó la opción <strong>borrar todos los contactos relacionados con esta dirección de correo
				electrónico</strong> al momento de bloquearla, deberá crear el contacto de nuevo
			</p>
			<p>
				Click en <strong>desbloquear</strong> si desea continuar
			</p>
			 <button {{'{{action unblock this}}'}} class="btn btn-primary">Desbloquear</button>
			 <button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar </button>
		</div>
	</div>
</script>