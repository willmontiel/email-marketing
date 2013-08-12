<script type="text/x-handlebars" data-template-name="lists/index">
	<div class="row-fluid">
		<div class="span12">
			<h3>Listas</h3>
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12">
			<p>Cree listas y agregue contactos, para una mejor organización de sus envíos</p>
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="text-right">
			{{'{{#linkTo "lists.new"}} <button class="btn btn-primary" >Nueva Lista</button> {{/linkTo}}'}}
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="span3">
							Nombre
						</th>
						<th class="span4">
							Descripción
						</th>
						<th class="span2">
							Contactos
						</th>
						<th class="span2">
							Estado
						</th>
						<th class="span1">
							Acciones
						</th>
					</tr>
				</thead>
				<tbody>
					{{'{{#each controller}}'}}
						<tr>
							<td>
								{{ '{{name}}' }}
							</td>
							<td>
								{{ '{{description}}' }}
							</td>
							<td>
								90
							</td>
							<td>
								<dl>
									<dd>Creada el:</dd>
									<dd><span class="green-label">{{ '{{createdon}}' }}</span></dd>
									<dd>Ultima actualización:</dd> 
									<dd><span class="green-label">{{ '{{updatedon}}' }}</span></dd>
								<dl>
							</td>
							<td>
								<dl>
									<dd>Ver</dd>
									<dd>Editar</dd>
									<dd>Eliminar</dd>
									<dd></dd>
								</dl>
							</td>
						</tr>
					{{ '{{/each}}' }}
				</tbody>
			</table>
		</div>
	</div>
	<div class="row-fluid">
		<div class="text-right">
			{{'{{#linkTo "lists.new"}} <button class="btn btn-primary" >Nueva Lista</button> {{/linkTo}}'}}
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="lists">
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
			{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}	
	{{'{{outlet}}'}}
</script>

<!---------- Crear una lista ---------------->

<script type="text/x-handlebars" data-template-name="lists/new">
	<div class="row-fluid">
		<div class="span12">
			<h4>Agregar una nueva lista</h4>
		</div>
		<br>
		<div class="span12">
		</div>
		<br>
		<div class="span3">
			<form>
				<label>*Nombre</label>
				<p>{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus"}}' }}</p>
				<label>Descripción</label>
				<p>{{ '{{view Ember.TextField valueBinding="description" placeholder="Descripción" required="required"}}' }}</p>
				<br>
				<button class="btn btn-primary" {{ '{{action save this }}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
				<button class="btn btn-inverse" {{ '{{action cancel this }}' }}>Cancelar</button>
			</form>
		</div>
	</div>
</script>