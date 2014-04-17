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
{%block sectionsubtitle %}Administre sus bases de datos de contactos{% endblock %}
{% block content %}
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
					{{'{{#link-to "lists" class="btn-actn"}}Listas de contactos{{/link-to}}'}}
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "segments" class="shortcuts"}}<span class="sm-button-large-segment"></span>{{/link-to}}'}}
					</div>
						{{'{{#link-to "segments" class="btn-actn"}}Segmentos{{/link-to}}'}}
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "blockedemails" class="shortcuts"}} <span class="sm-button-large-bloq-list"></span>{{/link-to}}'}}
					</div>
						{{'{{#link-to "blockedemails" class="btn-actn"}}Lista de bloqueo{{/link-to}}'}}
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('contacts/search')}}#/contacts" class="shortcuts"><span class="sm-button-large-contact-search"></span></a>
					</div>
					<a href="{{url('contacts/search')}}#/contacts" class="btn-actn">Búsqueda de contactos</a>
				</div>	
			</div>
			<div class="row-fluid space"></div>
			<div class="row">
				<h4 class="sectiontitle">Avanzadas</h4>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('dbase')}}" class="shortcuts" title="Configuracion avanzada"><span class="sm-button-large-settings"></span></a>
					</div>
					<a href="{{url('dbase')}}" class="btn-actn">Configuración avanzada</a>
				</div>

				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('process/import')}}" class="shortcuts"><span class="sm-button-large-import-list"></span></a>
					</div>
					<a href="{{url('process/import')}}" class="btn-actn">Lista de importaciones</a>
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
					<div class="col-md-3 pull-right">
						<a class="btn btn-default extra-padding btn-sm" href="{{ url('contactlist') }}#/lists/new">Crear una nueva lista</a>
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
							<td {{' {{bind-attr style="dbase.style"}} '}}>
							</td>
							<td>
								<a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts">{{ '{{name}}' }}</a>
								<br/>
								{{ '{{description}}' }}
							</td>
							<td>
								<!-- Detalles de la lista -->
								<a href="{{url('contactlist/show')}}/{{ '{{unbound id}}' }}#/contacts" class="btn btn-default btn-sm">
								<i class="glyphicon glyphicon-search"></i> Detalles</a>

								<!-- Editar la lista -->
								{{ '{{#link-to "lists.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}<i class="glyphicon glyphicon-pencil"></i> Editar{{ '{{/link-to}}' }}

								<!-- Eliminar la lista -->
								{{ '{{#link-to "lists.delete" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete"}}' }}<i class="glyphicon glyphicon-trash"></i> Eliminar{{ '{{/link-to}}' }}

								<!-- Estadisticas de la lista -->
								<a href="{{url('statistic/contactlist')}}/{{ '{{unbound id}}' }}" class="btn btn-default btn-sm" title="Ver estadisticas"><i class="fa fa-bar-chart-o"></i></a>

							</td>
							<td>
								<div class="box-section news with-icons">
									<div class="news-time">
										<span class="blue big-number">{{ '{{infocontact.activeContacts}}' }}</span>
										<span class="blue big-number">{{'{{activeContactsF}}'}}</span> activos
									</div>
								</div>
							</td>
						</tr>
				{{ '{{else}}' }}
						<tr>
							<td>
								<div class="bs-callout bs-callout-warning">
									<h4>No se encontraron listas</h4>
									<p>Verifique el filtro de bases de datos.</p>
									<p>Todos los contactos se organizan en listas. Si desea crear una lista de contactos,
										haga {{'{{#link-to "lists.new" disabledWhen="createDisabled"}}'}} clic aquí {{'{{/link-to}}'}} o elija el botón de la parte superior
									</p>
								</div>
							</td>
						</tr>
				{{ '{{/each}}' }}
					</tbody>
				</table>
				<div class="row">
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
				{{ '{{outlet}}' }}
		</script>
		
		{#  ######## Handlebars de crear nueva lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/new">
			<div class="row">
				<h4 class="sectiontitle">Agregar una nueva lista</h4>
				<div class="col-md-5">
					<form  class="form-horizontal" role="form">
						{{'{{#if errors.errormsg}}'}}
						<div class="alert alert-error">
							{{'{{errors.errormsg}}'}}
						</div>
						{{'{{/if}}'}}

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
									class="sm-select form-contro"}}'
								}}
							</div>
						</div>
						<div class="form-actions pull-right">
							<div class="row">
								<div class="col-xs-6">
									<button class="btn btn-sm btn-default" {{ '{{action cancel this }}' }}>Cancelar</button>
								</div>

								<div class="col-xs-6">
									<button class="btn btn-sm btn-default btn-guardar extra-padding" {{ '{{action save this }}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
								</div>
							</div>	
						</div>
					</form>
				</div>
			</div>	
		</script>
		{#  ######## /Handlebars de crear nueva lista de contactos ######### #}
		
		{#  ######## Handlebars de editar lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/edit">
			<div class="box span4">
				<div class="box-header"><span class="title">Editar lista <strong>{{'{{name}}'}}</strong></span></div>
				<div class="box-content">
					<form>
						<div class="padded">
							{{'{{#if errors.errormsg}}'}}
								<div class="alert alert-error">
									{{'{{errors.errormsg}}'}}
								</div>
							{{'{{/if}}'}}
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
							<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
							<button class="btn btn-blue" {{ '{{action edit this}}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
						</div>
					</form>
				</div>
			</div>
		</script>
		{#  ######## /Handlebars de editar lista de contactos ######### #}

		{#  ######## Handlebars de eliminar lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/delete">
			<div class="row-fluid">
				<div class="box">
					<div class="box-header">
						<div class="title">
							Eliminar una lista de contactos
						</div>
					</div>
					<div class="box-content padded">
						<div class="row-fluid">
							<p>
								Aqui podrá eliminar listas de contactos, recuerde que al eliminar una lista de contactos
								<strong>no perderá los contactos</strong>, simplemente seran des-asociados de dicha lista, pero en caso
								de que algún contacto solo pertenezca a dicha lista y a ninguna otra, este si <strong>será eliminado
								por completo.</strong>
							</p>
							<p>
								Si está <strong>completamente seguro</strong> y desea continuar haga clic en el botón eliminar para
								proceder
							</p>
							{{'{{#if errors.errormsg}}'}}
								<div class="alert alert-error">
									{{'{{errors.errormsg}}'}}
								</div>
							{{'{{/if}}'}}
							<br>
							<button class="btn btn-danger" {{ '{{action delete this}}' }}>Eliminar</button>
							<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
						</div>
					</div>
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
