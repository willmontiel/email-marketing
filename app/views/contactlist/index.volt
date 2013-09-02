{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api';
	</script>
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/app_list.js') }}
	{{ javascript_include('js/app_blockedemail.js') }}
	{{ javascript_include('js/app_segment.js') }}

	<script>
		App.DBObjectList = App.store.findAll(App.Dbase);
	</script>
{% endblock %}

{% block sectiontitle %}<i class="icon-user"></i> Contactos{%endblock%}
{%block sectionsubtitle %}Administre sus bases de datos de contactos{% endblock %}
{% block content %}
	<div id="emberApplistContainer">
		{# handlebars de index #}
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
			{# Tabs de navegacion #}
			<div class="box">
				<div class="box-header">
					<ul class="nav nav-tabs nav-tabs-left">
						{{'{{#linkTo "lists" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Listas de contactos</a>{{/linkTo}}'}}
						{{'{{#linkTo "segments" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Segmentos</a>{{/linkTo}}'}}
						{{'{{#linkTo "blockedemails" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Listas de bloqueo</a>{{/linkTo}}'}}
					</ul>
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
			<div class="action-nav-normal">
				<div class="row-fluid">
					<div class="action-nav-button span2 pull-right">
						{{ '{{#linkTo "lists.new" title="Crear lista de contactos" }}' }}
							<i class="icon-file-alt"></i>
							<span>Crear lista de Contactos</span>
						{{' {{/linkTo}}' }}
						<span class="triangle-button red"><i class="icon-plus"></i></span>
					</div>
				</div>
			</div>
			<br>
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
							<span>{{'{{activeContacts}}'}}</span> activos
						</div>
						<div class="news-content">
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
	</div>
	<br>
	<div class="row-fluid">
		<br><br>
		<div class="span3">
			<form>
				<label>*Nombre</label>
				{{' {{#if errors.email }} '}}
					<p class="alert alert-error">{{'{{errors.email}}'}}</p>
				{{' {{/if }} '}}
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
		{{ partial("contactlist/segment_partial") }}
</div>
{% endblock %}
