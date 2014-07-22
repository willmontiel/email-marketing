{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1Url()}}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/app_std.js') }}
	{{ javascript_include('js/app_search_contact.js') }}
{% endblock %}
{% block content %}
	<div id="emberApplistContainer">
		<script type="text/x-handlebars">
			{{ '{{outlet}}' }}
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/index">

			{# Menu de navegacion pequeño #}
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'search']) }}
			{# /Menu de navegacion pequeño #}

			<div class="row">
				<h4 class="sectiontitle">Búsqueda global de contactos</h4>
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-md-8">
							{{' {{view Ember.TextField valueBinding="searchText" onEvent="enter" action=search type="text" placeholder="Dirección de correo, nombre, apellido, dominio..." autofocus="autofocus" class="form-control"}} '}}
						</div>
						<div class="col-sm-4">
							{#<button class="btn btn-default" {{ '{{action reset this}}' }}><i class="icon-bolt"></i> Limpiar</button>#}
							<button class="btn btn-default btn-guardar extra-padding" {{ '{{action search this}}' }}><i class="glyphicon glyphicon-search"></i> Buscar</button>
							{{ '{{#if totalrecords}}' }}
								<button {{ '{{action seeMore this}}' }} class="btn btn-default">Ver más</button>
							{{ '{{/if}}' }}
						</div>
					</div>
				</form>
			</div>
			<div class="row">
				<table class="table table-striped table-contacts">
					<thead>
						<tr>
							<td>Dirección de correo</td>
							<td>Nombre</td>
							<td>Apellido</td>
							<td>Base de datos</td>
						</tr>
					</thead>
					<tbody>
						{{' {{#each controller}} '}}
							<tr>
								<td><b><a href="{{url('dbase/show')}}/{{ '{{unbound idDbase}}' }}#/contacts/show/{{ '{{unbound id}}' }}" target="_blank">{{'{{email}}'}}</a></b></td>
								<td>{{'{{name}}'}}</td>
								<td>{{'{{lastName}}'}}</td>
								<td>{{'{{dbase}}'}}</td>	
							</tr>
						{{' {{else}} ' }}
							<tr>
								<td colspan="3"><b>No se encontrarón coincidencias</b></td>
							</tr>
						{{' {{/each}} ' }}
					</tbody>
				</table>
			</div>
		</script>
	</div>
{% endblock %}