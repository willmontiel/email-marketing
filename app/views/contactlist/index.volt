{% extends "templates/index.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api';
	</script>
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/app_list.js') }}
	{{ javascript_include('js/app_blockedemail.js') }}

	<script>
		App.DBObjectList = App.store.findAll(App.Dbase);
	</script>
{% endblock %}

{% block content %}
	<div id="emberApplistContainer">
		<script type="text/x-handlebars">       
        <div class="row-fluid">
			<div class="span12 text-right">
				<div class="btn-group">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
					<ul class="dropdown-menu">
					  <li><a href="#">Ver Bases de Datos</a></li>
					  <li><a href="#">Crear Base de Datos</a></li>
					  <li><a href="#">Ver Cuentas</a></li>
					  <li><a href="#">Crear Cuentas</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<ul class="nav nav-tabs">
					{{'{{#linkTo "lists" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Listas de contactos</a>{{/linkTo}}'}}
					<li><a href="#" >Segmentos</a></li>
					{{'{{#linkTo "blockedemails" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Listas de bloqueo</a>{{/linkTo}}'}}                                                                                                                    
				</ul>
			</div>
        </div>
        {{ "{{outlet}}" }}
		</script>
		<script type="text/x-handlebars" data-template-name="lists/index">
			<div class="row-fluid">
				<div class="span12"></div>
			</div>
			<div class="row-fluid">
				<div class="span7">
					<p>Vea información detallada sobre sus listas de contactos</p>
				</div>
				<div class="span3"></div>
				<div class="span2 text-right">
					{{ '{{#linkTo "lists.import"}}' }}<span class="fui-list import"> Importar</span>{{ '{{/linkTo}}' }}
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
								<th class="span3">
									Descripción
								</th>
								<th class="span3">
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
								<td><a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts">{{ '{{name}}' }}</a></td>
								<td>{{ '{{description}}' }}</td>
								<td>50</td>
								<td><span class="label label-filling">{{ '{{dbase.name }}' }}</span></td>
								<td>
									<label><a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts">Ver</a></label>
									<label>{{ '{{#linkTo "lists.edit" this}}' }}Editar{{ '{{/linkTo}}' }}</label>
									<label>{{ '{{#linkTo "lists.delete" this}}' }}Eliminar{{ '{{/linkTo}}' }}</label>
								</td>
							</tr>
					{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
			</div>
			<div class="row-fluid">
				{{ partial("partials/pagination_partial") }}
				<div class="span4 text-right">
					<br>
					{{ '{{#linkTo "lists.new" }}' }}<button class="btn btn-primary">Nueva lista</button>{{ '{{/linkTo}}' }}
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
				<p>{{ '{{view Ember.Select contentBinding="App.DBObjectList" selectionBinding="dbase" optionValuePath="content.id" optionLabelPath="content.name"}}' }}</p>
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
		<script type="text/x-handlebars" data-template-name="lists/delete">
			<div class="row-fluid">
				<div class="span8">
					<h3>Eliminar una lista de contactos</h3>
				</div>
				<div class="span4 text-right">
					{{ '{{#linkTo lists.index}}' }}<button class="btn btn-inverse">Regresar</button>{{ '{{/linkTo}}' }}
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span10">
					<p>
						Aqui podrás eliminar tus listas de contactos, recuerda que al eliminar una lista de contactos
						<strong>no perderás tus contactos</strong>, simplemente seran desacioados de dicha lista, pero en caso
						de que algún contacto solo pertenezca a dicha lista y a ninguna otra, este si <strong>será eliminado
						por completo.</strong>
					</p>
					<p>
						Si estás <strong>completamente seguro</strong> y deseas continuar da click en el botón eliminar para
						proceder
					</p>
					<br>
					<button class="btn btn-danger" {{ '{{action delete this}}' }}>Eliminar</button>
					<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="lists/import">
			<form method="Post" action="/emarketing/contacts/importcontacts" >
				<input class="input-file" name="archivo" type="file" id="archivo"><br>
				{{submit_button('class': "btn btn-primary", "Cargar")}}
				<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
			</form>
		</script>
		{{ partial("contactlist/blockedemail_partial") }}
</div>
{% endblock %}
