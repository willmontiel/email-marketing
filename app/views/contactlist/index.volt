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
	<div class="row-fluid">
		<div class="span12">
			<a href="{{url('contacts/search')}}#/contacts" class="btn btn-blue"><i class="icon-search"></i> Buscar contactos</a>
		</div>	
	</div>	
	<br />
	<div id="emberApplistContainer">
		<script type="text/x-handlebars">
			{# Tabs de navegacion #}
			<div class="box">
				<div class="box-header">
					<ul class="nav nav-tabs nav-tabs-left">
						{{'{{#linkTo "lists" tagName="li" href=false disabledWhen="allowContactlist"}}<a {{bindAttr href="view.href"}}> Listas de contactos</a>{{/linkTo}}'}}
						{{'{{#linkTo "segments" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Segmentos</a>{{/linkTo}}'}}
						{{'{{#linkTo "blockedemails" tagName="li" href=false disabledWhen="allowBlockedemail" }}<a {{bindAttr href="view.href"}}> Lista de bloqueo</a>{{/linkTo}}'}}
					</ul>
					<div class="title">
						<a href="{{url('dbase')}}" class="pull-right" title="Configuracion avanzada"><i class="icon-cog"></i></a>
						<a href="{{url('process/import')}}" class="pull-right btn btn-default list-import-process">Lista de Importaciones</a>
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
			<div class="row-fluid">
				<div class="span7">
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
				<div class="span5">
					<div class="action-nav-normal pull-right" style="margin-bottom: 5px;">
						{{'{{#linkTo "lists.new" class="btn btn-default" disabledWhen="createDisabled"}}'}}<i class="icon-plus"></i> Crear nueva Lista{{'{{/linkTo}}'}}
					</div>
				</div>	
			</div>
			<div class="box">
				<div class="box-header">
					<span class="title">Listas de Contactos</span>
					<ul class="box-toolbar">
						<li><span class="label label-green">{{'{{totalrecords}}'}}</span></li>
					</ul>
				</div>
				<div class="box-content">
					<table class="table table-bordered">
						<thead>
						</thead>
						<tbody>
					{{'{{#each model}}'}}
							<tr>
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
									{{ '{{#linkTo "lists.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/linkTo}}' }}
									{{ '{{#linkTo "lists.delete" this disabledWhen="controller.deleteDisabled" class="btn btn-default"}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/linkTo}}' }}
									<a href="{{url('statistic/contactlist')}}/{{ '{{unbound id}}' }}" class="btn btn-default" title="Ver estadisticas"><i class="icon-bar-chart"></i></a>
									

									{# {{ '{{#linkTo "lists.edit" this disabledWhen="controller.updateDisabled"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/linkTo}}' }}
									<a href="{{url('contactlist/show')}}/{{ '{{unbound id}}' }}#/contacts"><i class="icon-search"></i> Ver detalles</a>
									{{ '{{#linkTo "lists.delete" this disabledWhen="controller.deleteDisabled"}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/linkTo}}' }}
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
												{{'{{#linkTo "lists.new" class="btn btn-default" disabledWhen="createDisabled"}}'}}<i class="icon-plus"></i> Crear nueva Lista{{'{{/linkTo}}'}}
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
		
		<!------------- Crear una nueva lista ------------------------->
		
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
	{{ partial("contactlist/blockedemail_partial") }}
	{{ partial("contactlist/segment_partial")}}
</div>
{% endblock %}
