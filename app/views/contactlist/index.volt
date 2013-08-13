{% extends "templates/index.volt" %}
{% block header_javascript %}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api/dbase/{{ idbases.idDbase }}';
	</script>
	{{ super() }}
	{{ partial("partials/emberlist_partial") }}
{% endblock %}

{% block content %}
	<h4>{{idbases.idDbase}}</h4>
	
	{% for contactlist in contactlists %}
		<li>{{contactlist.idList}}</li>
	{% endfor %}
	

	<div id="emberApplistContainer">
		<script type="text/x-handlebars" data-template-name="lists/index">   
			<div class="row-fluid">
				<div class="span12">
					<h1>Listas</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12">
					<p>Vea información detallada sobre sus listas de contactos</p>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12 text-right">
					{{ '{{#linkTo "lists.new"}}' }}<button class="btn btn-primary">Nueva lista</button>{{ '{{/linkTo}}' }}
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
						</tbody>
					{{'{{#each model}}'}}
							<tr>
								<td>{{ '{{name}}' }}</td>
								<td>{{ '{{description}}' }}</td>
								<td></td>
								<td>
									<label>Creada el: </label>
									<span class="primary-label">{{ '{{createdon}}' }}</span>
									<label>Última actualización: </label>
									<span class="primary-label">{{ '{{updatedon}}' }}</span>
								</td>
								<td>
									<label><a href="contactlist/show/{{'{{id}}' }}">Ver</a></label>
									<label>Editar</label>
								</td>
							</tr>
					{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12 text-right">
					{{ '{{#linkTo "lists.new"}}' }}<button class="btn btn-primary">Nueva lista</button>{{ '{{/linkTo}}' }}
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="lists">
			{{ '{{#if Applist.errormessage }}' }}
				<div class="alert alert-message alert-error">
			{{ '{{ Applist.errormessage }}' }}
				</div>
			{{ '{{/if}} '}}	
		
			{{'{{outlet}}'}}
		</script>
		
		<!------------- Crear una nueva lista ------------------------->
		
		<script type="text/x-handlebars" data-template-name="lists/new">
			<div class="row-fluid">
				<div class="span12">
					<h1>Listas</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12">
					<p>Agregar una nueva lista</p>
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
		
		<!------------------------------------------------------------------>
		
		<script type="text/x-handlebars" data-template-name="segments/index">
			<br>
			<div class="row-fluid">
				<div class="span12"></div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<h1>Segmentos</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12">
					<p>Cree, Consulte o actualice información sobre sus segmentos</p>
				</div>
			</div>
		</script>
	</div>
{% endblock %}