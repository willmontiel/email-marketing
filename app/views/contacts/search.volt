{% extends "templates/index_new.volt" %}
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
{% block sectiontitle %}<i class="icon-search"></i>Buscar contactos{% endblock %}
{% block sectionsubtitle %}Busque contactos facilmente con solo ingresar un indicio{% endblock %}
{% block content %}
	<div id="emberApplistContainer">
		<script type="text/x-handlebars">
			{{ '{{outlet}}' }}
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/index">
			<div class="row-fluid">
				<div class="span8 offset2">
					<div class="box">
						<div class="box-content">
							<form>
								<div class="padded">
									<div class="title-search">Buscar Contactos</div>
									<div class="input-prepend">
										{{' {{view Ember.TextField valueBinding="searchText" onEvent="enter" action=search type="text" placeholder="Dirección de correo, nombre, apellido, dominio..." autofocus="autofocus"}} '}}
									</div>
								</div>
								<div class="form-actions" style="text-align: center !important;">
									{#<button class="btn btn-default" {{ '{{action reset this}}' }}><i class="icon-bolt"></i> Limpiar</button>#}
									<button class="btn btn-lightblue" {{ '{{action search this}}' }}><i class="icon-search"></i> Buscar</button>
									{{ '{{#if totalrecords}}' }}
										<button {{ '{{action seeMore this}}' }} class="btn btn-default">Ver más</button>
									{{ '{{/if}}' }}
								</div>
							</form>
						</div>
					</div>	
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box">
						<div class="box-content">
							<table class="table table-normal">
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
					</div>
				</div>
			</div>
		</script>
	</div>
{% endblock %}