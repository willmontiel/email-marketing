{% extends "templates/index.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api';
	</script>
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/app_list.js') }}

	<script>
		Applist.DBObjectList = Applist.store.findAll(Applist.Dbase);
	</script>
{% endblock %}

{% block content %}
	<div id="emberApplistContainer">
		<script type="text/x-handlebars" data-template-name="lists/index">   
			<div class="row-fluid">
				<div class="span12">
					<h1>Listas</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span7">
					<p>Vea información detallada sobre sus listas de contactos</p>
				</div>
				<div class="span3"></div>
				<div class="span2 text-right">
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
									Base de datos
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
								<td><span class="label label-filling">{{ '{{dbase.name }}' }}</span></td>
								<td>
									<label><a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts">Ver</a></label>
									<label>{{ '{{#linkTo "lists.edit" this}}' }}Editar{{ '{{/linkTo}}' }}</label>
								</td>
							</tr>
					{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span5">
					<div class="pagination">
						<ul>
							<li class="previous"><span class="fui-arrow-left" {{ '{{action firstPage this}}' }} style="cursor: pointer;"><span class="fui-arrow-left" style="cursor: pointer;"></span></span></li>
							<li class="previous"><span class="fui-arrow-left" {{ '{{action prevPage this}}' }} style="cursor: pointer;"></span></li>
							
							<li class="next"><span class="fui-arrow-right" {{ '{{action nextPage this}}' }}></span></li>
							<li class="next"><span class="fui-arrow-right" {{ '{{action lastPage this}}' }}><span class="fui-arrow-right"></span></span></li>
						</ul>
					 </div>
				</div>
				<div class="span5">
					<br><br>
					Registros totales: <span class="label label-filling">{{  '{{totalrecords}}' }}</span>&nbsp;
					Página  <span class="label label-filling">{{  '{{currentpage}}' }}</span>
					de <span class="label label-filling">{{  '{{availablepages}}' }}</span>
				</div>
				<div class="span2 text-right">
					<br>
					{{ '{{#linkTo "lists.new" }}' }}<button class="btn btn-primary">Nueva lista</button>{{ '{{/linkTo}}' }}
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
						<p>{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción" required="required"}}' }}</p>
						<label>Base de datos</label>
						<p>{{ '{{view Ember.Select contentBinding="Applist.DBObjectList" selectionBinding="dbase" optionValuePath="content.id" optionLabelPath="content.name"}}' }}</p>
						<br>
						<button class="btn btn-primary" {{ '{{action save this }}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
						<button class="btn btn-inverse" {{ '{{action cancel this }}' }}>Cancelar</button>
					</form>
				</div>
			</div>	
		</script>
		
		<script type="text/x-handlebars" data-template-name="lists/edit">
			<div class="row-fluid">
				<div class="span12">
					<h1>Listas</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12">
					<p>Edita facilmente la información de tus listas</p>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span3">
					<label>*Nombre</label>
					<p>
						{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre" required="required" autofocus="autofocus"}}' }}
					</p>
					<label>Descripción</label>
					<p>
						{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción" required="required"}}' }}
					</p>
					<br>
					<p>
						<button class="btn btn-inverse" {{ '{{action edit this}}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Editar</button>
						<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
					</p>
				</div>
			</div>
		</script>
	</div>
		<!---------------------------------

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
  --------------------------------->
{% endblock %}