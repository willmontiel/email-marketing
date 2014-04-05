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
				<h4>Básicas</h4>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "lists" class="shortcuts"}}<span class="sm-button-large-contact-list"></span>{{/link-to}}'}}
					</div>
					<a href="" class="btn-actn">Listas de contactos</a>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "segments" class="shortcuts"}}<span class="sm-button-large-segment"></span>{{/link-to}}'}}
					</div>
					<a href="" class="btn-actn">Segmentos</a>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						{{'{{#link-to "blockedemails" class="shortcuts"}} <span class="sm-button-large-bloq-list"></span>{{/link-to}}'}}
					</div>
					<a href="" class="btn-actn">Lista de bloqueo</a>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('contacts/search')}}#/contacts" class="shortcuts"><span class="sm-button-large-contact-search"></span></a>
					</div>
					<a href="" class="btn-actn">Búsqueda de contactos</a>
				</div>	
			</div>
			<div class="row-fluid space"></div>
			<div class="row">
				<h4>Avanzadas</h4>
				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('dbase')}}" class="shortcuts" title="Configuracion avanzada"><span class="sm-button-large-settings"></span></a>
					</div>
					<a href="" class="btn-actn">Configuración avanzada</a>
				</div>

				<div class="col-xs-6 col-md-3">
					<div class="to-do sm-btn-blue">
						<a href="{{url('process/import')}}" class="shortcuts"><span class="sm-button-large-import-list"></span></a>
					</div>
					<a href="" class="btn-actn">Lista de importaciones</a>
				</div>
			</div>
		</script>
		{# /handlebars de index #}


		{# ########## handlebars de listas ########## #}
		<script type="text/x-handlebars" data-template-name="lists/index">
			{# Insertar botones de navegacion #}
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

			<div class="row">
				<h4>Listas de contactos</h4>
				<div class="col-md-3">
					<form>
						<p>
							{{ '{{view Ember.Select
									contentBinding="dbaseSelect"
									optionValuePath="content.id"
									optionLabelPath="content.name"
									valueBinding="selectedDbase"}}'
							}}
						</p>
					</form>
				</div>
			</div>				
			<div class="box">
				<div class="box-content">
					<table class="table table-bordered">
						<thead>
						</thead>
						<tbody>
					{{'{{#each model}}'}}
							<tr>
								<td {{' {{bind-attr style="dbase.style"}} '}}>
								</td>
								<td>
									<div class="box-section news with-icons">
										<div class="avatar blue">
											<i class="icon-ok icon-2x"></i>
										</div>
										<div class="news-content">
											<div class="news-title"><a href="contactlist/show/{{ '{{unbound id}}' }}#/contacts">{{ '{{name}}' }}</a></div>
											<div class="news-text">
												{{ '{{description}}' }}
											</div>
											<span class="label label-filling">{{ '{{dbase.name }}' }}</span>
										</div>
									</div>
								</td>
								<td>
									<a href="{{url('contactlist/show')}}/{{ '{{unbound id}}' }}#/contacts" class="btn btn-blue"><i class="icon-search"></i> Detalles</a>
									{{ '{{#link-to "lists.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/link-to}}' }}
									{{ '{{#link-to "lists.delete" this disabledWhen="controller.deleteDisabled" class="btn btn-default"}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/link-to}}' }}
									<a href="{{url('statistic/contactlist')}}/{{ '{{unbound id}}' }}" class="btn btn-default" title="Ver estadisticas"><i class="icon-bar-chart"></i></a>
									{{ '{{#linkTo "lists.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/linkTo}}' }}
									{{ '{{#linkTo "lists.delete" this disabledWhen="controller.deleteDisabled" class="btn btn-default"}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/linkTo}}' }}
									<a href="{{url('statistic/contactlist')}}/{{ '{{unbound id}}' }}" class="btn btn-default" title="Ver estadisticas"><i class="fa fa-bar-chart-o"></i></a>
									

									{# {{ '{{#link-to "lists.edit" this disabledWhen="controller.updateDisabled"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/link-to}}' }}
									<a href="{{url('contactlist/show')}}/{{ '{{unbound id}}' }}#/contacts"><i class="icon-search"></i> Ver detalles</a>
									{{ '{{#link-to "lists.delete" this disabledWhen="controller.deleteDisabled"}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/link-to}}' }}
									<a href="{{url('statistic/contactlist')}}/{{ '{{unbound id}}' }}"><i class="icon-bar-chart icon-2x"></i></a>
										#}
								</td>
								<td>
									<div class="box-section news with-icons">
										<div class="news-time">
											<span>{{ '{{infocontact.activeContacts}}' }}</span>
											<span>{{'{{activeContactsF}}'}}</span> activos
										</div>
									</div>
								</td>
							</tr>
					{{ '{{else}}' }}
							<tr>
								<td>
									<div class="box-section news with-icons">
										<div class="avatar green">
											<i class="icon-lightbulb icon-2x"></i>
										</div>
										<div class="news-content">
											<div class="news-title">
												No hay listas de contactos
											</div>
											<div class="news-text">
												<p>
													Para empezar a administrar contactos, puede crear una lista de contactos,
													haga clic en el siguiente enlace para crear una
												</p>
												{{'{{#link-to "lists.new" class="btn btn-default" disabledWhen="createDisabled"}}'}}<i class="icon-plus"></i> Crear nueva Lista{{'{{/link-to}}'}}
											</div>
										</div>
									</div>
								</td>
							</tr>
					{{ '{{/each}}' }}
						</tbody>
					</table>
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
				{{ '{{outlet}}' }}
		</script>
		
		{#  ######## Handlebars de crear nueva lista de contactos ######### #}
		<script type="text/x-handlebars" data-template-name="lists/new">
			<div class="box span4">
				<div class="box-header"><span class="title">Agregar una nueva lista</span></div>
				<div class="box-content padded">
					<form>
						{{'{{#if errors.errormsg}}'}}
							<div class="alert alert-error">
								{{'{{errors.errormsg}}'}}
							</div>
						{{'{{/if}}'}}
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
							{{ '{{view Ember.Select
								contentBinding="controllers.dbase.content"
								optionValuePath="content.id"
								optionLabelPath="content.name"
								selectionBinding="dbase"
								prompt="Seleccione una base de datos"
								}}'
							}}
						</div>
						<div class="form-actions">
							<button class="btn btn-default" {{ '{{action cancel this }}' }}>Cancelar</button>
							<button class="btn btn-blue" {{ '{{action save this }}' }} data-toggle="tooltip" title="Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides">Guardar</button>
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
	{{ partial("contactlist/segment_partial")}}
</div>
{% endblock %}
