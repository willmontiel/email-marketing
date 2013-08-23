<script type="text/x-handlebars" data-template-name="blocked/index">
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
	<div class="row-fluid">
		<div class="span10">
			<p>
				Esta es la lista global de direcciones de correo electrónico bloqueados, ninguna dirección de correo
				que esté listada abajo, esta recibiendo tus campañas de envío.
			</p>
		</div>
		<div class="span2 text-right">
			{{ '{{#linkTo "blocked.block" }}' }}<button class="btn btn-danger">Bloquear</button>{{ '{{/linkTo}}' }}
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12">
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
					{{ '{{#each controller}}' }}
						<tr>
							<td>{{ '{{email}}' }}</td>
							<td>{{ date("F j, Y",'{{blockedDate}}') }}</td>
							<td>{{ '{{blockedReason}}'}}</td>
							<td>
								<label><a href="contactlist/show/#/contacts">Desbloquear</a></label>
							</td>
						</tr>
					{{ '{{/each }}' }}
				</tbody>
			</table>
		</div>
	</div>
	<div class="row-fluid">
		{{ partial("partials/pagination_partial") }}
		<div class="span4 text-right">
			<br>
			{{ '{{#linkTo "blocked.block" }}' }}<button class="btn btn-danger">Bloquear</button>{{ '{{/linkTo}}' }}
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="blocked">
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
	{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}	

	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="blocked/block">
	<div class="row-fluid">
		<div class="span12">
			<h2>Bloquear Contactos</h2>
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span10">
			<p>
				Aqui podrás bloquear direcciones de correo, Cuando bloquees una direccion de
				correo, este se marcará como des-suscrito, y por consiguiente sera omitido de todas las listas y bases de datos de 
				tu cuenta al realizar una campaña de envío. 
			</p>
			<p>
				Al intentar bloquear direcciones de correo, el sistema te preguntará si deseas eliminar los contactos 
				que pertenecen a esa dirección, si no se selecciona esa opción, por defecto se marcarán como des-suscritos
				y podras revertirlo.
			</p>
		</div>
		<div class="span2 text-right">
			{{ '{{#linkTo "blocked.index" }}' }}<button class="btn btn-inverse">Regresar</button>{{ '{{/linkTo}}' }}
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span4">
			<form>
				<label>*Dirección de correo electronico: </label>
				<p>
					{{ '{{view Ember.TextField valueBinding="email" placeholder="Escribe el Email aquí" required="required" autofocus="autofocus"}}' }}
				</p>
				<label>*Escribe la razón por la cual estas bloqueando esta direccion de correo electrónico: </label>
				<p>
					{{ '{{view Ember.TextArea valueBinding="blockedReason" placeholder="Escribe la razón de bloqueo" required="required"}}' }}
				</p>
				
				<button class="btn btn-danger" {{ '{{action block this }}' }}>Bloquear</button>
				<button class="btn btn-inverse" {{ '{{action cancel this }}' }}>Cancelar</button>
			</form>
		</div>
	</div>
</script>