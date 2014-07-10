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
	{{ javascript_include('js/list_model.js') }}
	{{ javascript_include('js/app_list.js') }}
	{{ javascript_include('js/app_blockedemail.js') }}
	<script type="text/javascript">
		// ACL de blocked emails:
		App.blockedemailACL = {
			canCreate: {{ acl_Ember('api::addemailtoblockedlist')}},
			canDelete: {{ acl_Ember ('api::removeemailfromblockedlist')}}
		};
		//ACL de Contact List
		App.contactListACL = {
			canCreate: {{acl_Ember('api::createcontactlist')}},
			canRead: {{acl_Ember('api::getcontactbylist')}},
			canUpdate: {{acl_Ember('api::listsedit')}},
			canDelete: {{acl_Ember('api::deletecontactlist')}},
			allowBlockedemail: {{acl_Ember('api::listblockedemails')}},
			allowContactlist: {{acl_Ember('api::getlists')}}
		};
	</script>
	{{ javascript_include('js/load_activecontacts.js')}}
	{{ javascript_include('js/app_segment.js') }}
{% endblock %}
{% block sectiontitle %}
	<i class="icon-user"></i> Contactos
{% endblock %}
{% block sectionContactLimit %}
	{{ partial("partials/contactlimitinfo_partial") }}
{%endblock%}
{{flashSession.output()}}
	<div id="emberApplistContainer">

		{# ######## handlebars de APP ########### #}
		<script type="text/x-handlebars">
				{{ "{{outlet}}" }}
		</script>
		{# /handlebars de APP #}


		{# ######### handlebars de index ########## #}
		<script type="text/x-handlebars" data-template-name="index">
			{# Botones de navegacion primarios #}

			<div class="row">
				<h4 class="sectiontitle">Básicas</h4>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "lists" class="shortcuts"}}<span class="sm-button-large-contact-list"></span>{{/link-to}}'}}
					</div>
					<div class="w-190 center">
						{{'{{#link-to "lists" class="btn-actn"}}Listas de contactos{{/link-to}}'}}
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "segments" class="shortcuts"}}<span class="sm-button-large-segment"></span>{{/link-to}}'}}
					</div>
					<div class="w-190 center">
						{{'{{#link-to "segments" class="btn-actn"}}Segmentos{{/link-to}}'}}
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "blockedemails" class="shortcuts"}} <span class="sm-button-large-bloq-list"></span>{{/link-to}}'}}
					</div>
					<div class="w-190 center">
						{{'{{#link-to "blockedemails" class="btn-actn"}}Lista de bloqueo{{/link-to}}'}}
					</div>
				</div>
{#
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('contacts/search')}}#/contacts" class="shortcuts"><span class="sm-button-large-contact-search"></span></a>
					</div>
					<div class="w-190 center">
						<a href="{{url('contacts/search')}}#/contacts" class="btn-actn">Búsqueda de contactos</a>
					</div>
				</div>	
#}
			</div>
			<div class="row space"></div>
			<div class="row">
				<h4 class="sectiontitle">Avanzadas</h4>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('dbase')}}" class="shortcuts" title="Configuracion avanzada"><span class="sm-button-large-settings"></span></a>
					</div>
					<div class="w-190 center">
						<a href="{{url('dbase')}}" class="btn-actn">Configuración avanzada</a>
					</div>
				</div>

				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('process/import')}}" class="shortcuts"><span class="sm-button-large-import-list"></span></a>
					</div>
					<div class="w-190 center">
						<a href="{{url('process/import')}}" class="btn-actn">Lista de importaciones</a>
					</div>
				</div>
			</div>
			<div class="space"></div>
		</script>
		{# /handlebars de index #}


		{# ########## handlebars de listas ########## #}
		<script type="text/x-handlebars" data-template-name="lists/index">
			{# Insertar botones de navegacion #}
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

			<div class="row">
				<h4  class="sectiontitle">Listas de contactos</h4>
				<div class="row">
					<div class="col-xs-6 col-sm-5 col-md-4">
						<form role="form">
							{{ '{{view Ember.Select
									contentBinding="dbaseSelect"
									optionValuePath="content.id"
									optionLabelPath="content.name"
									valueBinding="selectedDbase"
									class="sm-select form-control"
									placeholder="Todas las bases de datos"
								}}'
							}}
						</form>
					</div>
					<div class="col-md-8 text-right">
						<a class="btn btn-default extra-padding btn-sm" href="{{ url('contactlist') }}#/lists/new"><span class="glyphicon glyphicon-plus"></span> Crear una nueva lista</a>
					</div>
				</div>
				<div class="space"></div>
			</div>				
			<div class="row">
				<table class="table table-striped table-contacts">
					<thead>
					</thead>
					<tbody>
				{{'{{#each model}}'}}
						<tr>
							<td class="sm-striped-bg" {{' {{bind-attr style="dbase.style"}} '}}>
							</td>
							<td>
								<a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts"><strong>{{ '{{name}}' }}</strong></a></br>
								{{ '{{description}}' }}
							</td>
							<td>
								<div class="box-section news with-icons">
									<div class="news-time">
										<p class="right"><span class="blue big-number right">{{'{{activeContactsF}}'}}<br> </span> activos</p>
									</div>
								</div>
							</td>
							<td class="text-right">
{#								<!-- Detalles de la lista -->
								<a href="{{url('contactlist/show')}}/{{ '{{unbound id}}' }}#/contacts" class="btn btn-default btn-sm extra-padding">
								<span class="glyphicon glyphicon-search"></span> Detalles</a>
#}
								<!-- Editar la lista -->
								{{ '{{#link-to "lists.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-pencil"></span> Editar{{ '{{/link-to}}' }}

								<!-- Eliminar la lista -->
								{{ '{{#link-to "lists.delete" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete extra-padding"}}' }}<span class="glyphicon glyphicon-trash"></span> Eliminar{{ '{{/link-to}}' }}

								<!-- Estadísticas de la lista -->
								<a href="{{url('statistic/contactlist')}}/{{ '{{unbound id}}' }}" class="btn btn-default btn-sm extra-padding" title="Ver estadisticas"><span class="glyphicon glyphicon-stats"></span> Estadísticas</a>

							</td>
						</tr>
				{{ '{{else}}' }}
						<tr>
							<td>
								<div class="bs-callout bs-callout-warning">
									<h4>No se encontraron listas</h4>
									<p>Verifique el filtro de bases de datos.</p>
									<p>Todos los contactos se organizan en listas. Si desea crear una lista de contactos,
										haga {{'{{#link-to "lists.new" disabledWhen="createDisabled"}}'}} clic aquí {{'{{/link-to}}'}} o elija el botón de la parte superior.</p>
									
								</div>
							</td>
						</tr>
				{{ '{{/each}}' }}
					</tbody>
				</table>
				{#   parcial paginacion   #}
				{{ partial("partials/pagination_partial") }}
			</div>

		</script>
		<script type="text/x-handlebars" data-template-name="lists">
				{{ '{{#if App.errormessage }}' }}
					<div class="bs-callout bs-callout-danger">
					{{ '{{ App.errormessage }}' }}
					</div>
				{{ '{{/if}} '}}	
				{{ '{{outlet}}' }}
		</script>
		
		{#  ######## Handlebars de crear nueva lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/new">
			<div class="row">
				<h4 class="sectiontitle">Crear una nueva lista</h4>
				<div class="col-sm-12 hidden-md hidden-lg">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Cree una nueva lista</p>
							</div>
						</div>
					</div>
				</div>

				{{'{{#if errors.errormsg}}'}}
				<div class="bs-callout bs-callout-danger">
					{{'{{errors.errormsg}}'}}
				</div>
				{{'{{/if}}'}}

				<div class="col-md-7">
					<form  class="form-horizontal" role="form">
						{{' {{#if errors.name }} '}}
						<span class="text text-error">{{'{{errors.name}}'}}</span>
						{{' {{/if }} '}}

						{{' {{#if errors.description }} '}}
							<span class="text text-error">{{'{{errors.description}}'}}</span>
						{{' {{/if }} '}}

						<div class="form-group">
							<label for="name" class="col-sm-4 control-label">* Nombre</label>
							<div class="col-md-8">
								{{ '{{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus" class="form-control"}}' }}
							</div>
						</div>
											

						<div class="form-group">
							<label for="description" class="col-sm-4 control-label">Descripción</label>
							<div class="col-md-8">
								{{ '{{view Ember.TextArea valueBinding="description" placeholder="Descripción" required="required" class="form-control sm-textarea-description"}}' }}
							</div>
						</div>

						<div class="form-group">
							<label for="dbases" class="col-sm-4 control-label">Base de datos</label>
							<div class="col-md-8">
								{{ '{{view Ember.Select
									contentBinding="controllers.dbase.content"
									optionValuePath="content.id"
									optionLabelPath="content.name"
									selectionBinding="dbase"
									id="dbases"
									prompt="Seleccione una base de datos"
									class="sm-select form-control"}}'
								}}
							</div>
						</div>
						<div class="form-actions pull-right">
							<button class="btn btn-sm btn-default extra-padding" {{ '{{action "cancel" this }}' }}>Cancelar</button>
							<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action "save" this }}' }}>Guardar</button>
						</div>
					</form>
				</div>
				<div class="hidden-xs hidden-sm col-md-5">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Cree una nueva lista</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</script>
		{#  ######## /Handlebars de crear nueva lista de contactos ######### #}
		
		{#  ######## Handlebars de editar lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/edit">
			<div class="row">
				<h4 class="sectiontitle">Editar la lista <strong>{{'{{name}}'}}</strong></h4>
				<div class="col-sm-12 hidden-md hidden-lg">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Cambie el nombre y/o la descripción de la lista</p>
							</div>
						</div>
					</div>
				</div>
				{{'{{#if errors.errormsg}}'}}
				<div class="bs-callout bs-callout-danger">
					{{'{{errors.errormsg}}'}}
				</div>
				{{'{{/if}}'}}
				<div class="col-md-7">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label for="" class="col-sm-4 control-label">*Nombre
								{{' {{#if errors.name }} '}}
									<span class="text text-error">{{'{{errors.name}}'}}</span>
								{{' {{/if }} '}}
							</label>
							<div class="col-md-8">
								{{ '{{view Ember.TextField valueBinding="name" class="form-control" placeholder="Nombre" required="required" autofocus="autofocus"}}' }}
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-4 control-label">Descripción
								{{' {{#if errors.description }} '}}
									<span class="text text-error">{{'{{errors.description}}'}}</span>
								{{' {{/if }} '}}
							</label>
							<div class="col-md-8">
								{{ '{{view Ember.TextArea valueBinding="description" class="form-control" placeholder="Descripción" required="required"}}' }}
							</div>
						</div>
						<div class="form-actions pull-right">
							<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
							<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action edit this}}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
						</div>
					</form>
				</div>
				<div class="hidden-xs hidden-sm col-md-5">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Cambie el nombre y/o la descripción de la lista</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</script>
		{#  ######## /Handlebars de editar lista de contactos ######### #}

		{#  ######## Handlebars de eliminar lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/delete">
			<div class="row">
				<h4 class="sectiontitle">Eliminar una lista de contactos</h4>
				<div class="bs-callout bs-callout-danger">
					
					<p>Al eliminar una lista de contactos <strong>no perderá los contactos</strong>, simplemente seran des-asociados de dicha lista, pero en caso de que algún contacto solo pertenezca a dicha lista y a ninguna otra, este si <strong>será eliminado por completo.</strong></p>
					
					
					<p>Si está <strong>completamente seguro</strong> y desea continuar haga clic en el botón eliminar para proceder</p>
					
				</div>
				{{'{{#if errors.errormsg}}'}}
					<div class="bs-callout bs-callout-danger">
						{{'{{errors.errormsg}}'}}
					</div>
				{{'{{/if}}'}}
				<div class="form-actions">
					<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
					<button class="btn btn-default btn-delete btn-sm extra-padding" {{ '{{action delete this}}' }}>Eliminar</button>
				</div>
			</div>
		</script>
		{#  ######## /Handlebars de eliminar lista de contactos ######### #}

	{{ partial("contactlist/blockedemail_partial") }}

		<!-- SM -->
	<div class="prueba"></div>
	{{ partial("contactlist/segment_partial")}}
</div>
{% endblock %}
