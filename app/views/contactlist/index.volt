{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url}}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/app_std.js') }}
	{{ javascript_include('js/list_model.js') }}
	{{ javascript_include('js/app_list.js') }}
	{{ javascript_include('js/app_blockedemail.js') }}
	<script type="text/javascript">
		App.DBObjectList = App.store.findAll(App.Dbase);
		var SegmentModel = {
			name: DS.attr('string'),
			description: DS.attr('string'),
			
			isDbaseSelected: function() {
				App.fieldObjects = [];
				for(var i=0; i<{{totalFields}}; i++) {
				
				}
				
				App.fieldObjects[ ..idDBase.. ] = [
					// Por cada uno de los customfields de esa base de datos:
					{ id: ..nombre del campo codificado.., label: ..nombre del campo... }
				];
			}.property('criteria')
		};
	</script>
	{{ javascript_include('js/app_segment.js') }}
{% endblock %}
{% block sectiontitle %}<i class="icon-user"></i> Contactos{%endblock%}
{%block sectionsubtitle %}Administre sus bases de datos de contactos{% endblock %}
{% block content %}
	<div id="emberApplistContainer">
		{{ dump(fields[0][1])}}
		{# handlebars de index #}
		<script type="text/x-handlebars">
			{# Tabs de navegacion #}
			<div class="box">
				<div class="box-header">
					<ul class="nav nav-tabs nav-tabs-left">
						{{'{{#linkTo "lists" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Listas de contactos</a>{{/linkTo}}'}}
						{{'{{#linkTo "segments" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Segmentos</a>{{/linkTo}}'}}
						{{'{{#linkTo "blockedemails" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Listas de bloqueo</a>{{/linkTo}}'}}
					</ul>
					<div class="title">
						<a href="{{url('dbase')}}" class="pull-right" title="Configuracion avanzada"><i class="icon-cog"></i></a>
					</div>
				</div>
				<div class="box-content padded">
					<div class="tab-content">
						{{ "{{outlet}}" }}
					</div>
				</div>
			</div>
		</script>
		{# /handlebars de index #}
		{# handlebars de listas #}
		<script type="text/x-handlebars" data-template-name="lists/index">
			<div class="action-nav-normal pull-right" style="margin-bottom: 5px;">
				{{'{{#linkTo "lists.new" class="btn btn-default"}}'}}<i class="icon-plus"></i> Crear nueva Lista{{'{{/linkTo}}'}}
			</div>
			<div class="clearfix"></div>
			
			<div class="box">
				<div class="box-header">
					<span class="title">Listas de Contactos</span>
					<ul class="box-toolbar">
						<li><span class="label label-green">{{'{{totalrecords}}'}}</span></li>
					</ul>
				</div>
				<div class="box-content">
				{{'{{#each model}}'}}
					<div class="box-section news with-icons">
						<div class="avatar blue">
							<i class="icon-ok icon-2x"></i>
						</div>
						<div class="news-time">
							<span>{{'{{activeContactsF}}'}}</span> activos
						</div>
						<div class="news-content">
							<div class="pull-right" style="margin-right: 10px;">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li>{{ '{{#linkTo "lists.edit" this}}' }}<i class="icon-pencil"></i> Editar{{ '{{/linkTo}}' }}</li>
										<li><a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts"><i class="icon-search"></i> Ver detalles</a></li>
										<li>{{ '{{#linkTo "lists.delete" this}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/linkTo}}' }}</li>
									</ul>
								</div>
							</div>
							<div class="news-title"><a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts">{{ '{{name}}' }}</a></div>
							<div class="news-text">
								{{ '{{description}}' }}
							</div>
							<span class="label label-filling">{{ '{{dbase.name }}' }}</span>
						</div>
					</div>
				{{ '{{/each}}' }}
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>

		</script>
		<script type="text/x-handlebars" data-template-name="lists">
			{{'{{outlet}}'}}
		</script>
		
		<!------------- Crear una nueva lista ------------------------->
		
<script type="text/x-handlebars" data-template-name="lists/new">
	<div class="box span4">
		<div class="box-header"><span class="title">Agregar una nueva lista</span></div>
		<div class="box-content padded">
			<form>
				<div class="padded">
					<label>Nombre *
						{{' {{#if errors.name }} '}}
							<span class="text text-error">{{'{{errors.name}}'}}</span>
						{{' {{/if }} '}}
					</label>				

					{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus"}}' }}
					<label>Descripción
						{{' {{#if errors.description }} '}}
							<span class="text text-error">{{'{{errors.description}}'}}</span>
						{{' {{/if }} '}}
					</label>
					{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción" required="required"}}' }}
					<label>Base de datos</label>
					{{ '{{view Ember.Select contentBinding="App.DBObjectList" selectionBinding="dbase" optionValuePath="content.id" optionLabelPath="content.name"}}' }}
				</div>
				<div class="form-actions">
					<button class="btn btn-default" {{ '{{action save this }}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
					<button class="btn btn-default" {{ '{{action cancel this }}' }}>Cancelar</button>
				</div>
			</form>
		</div>
	</div>	
</script>
		
<script type="text/x-handlebars" data-template-name="lists/edit">
	<div class="box span4">
		<div class="box-header"><span class="title">Editar lista <strong>{{'{{name}}'}}</strong></span></div>
		<div class="box-content">
			<form>
				<div class="padded">
					<label>*Nombre
						{{' {{#if errors.name }} '}}
							<span class="text text-error">{{'{{errors.name}}'}}</span>
						{{' {{/if }} '}}
					</label>
					{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre" required="required" autofocus="autofocus"}}' }}
					<label>Descripción
						{{' {{#if errors.description }} '}}
							<span class="text text-error">{{'{{errors.description}}'}}</span>
						{{' {{/if }} '}}
					</label>
					{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción" required="required"}}' }}
				</div>
				<div class="form-actions">
					<button class="btn btn-primary" {{ '{{action edit this}}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Editar</button>
					<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
				</div>
			</form>
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
				<strong>no perderás tus contactos</strong>, simplemente seran des-asociados de dicha lista, pero en caso
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
	{{ partial("contactlist/blockedemail_partial") }}
	{{ partial("contactlist/segment_partial")}}
</div>
{% endblock %}
