{% extends "templates/index_b3.volt" %}

{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ partial("partials/date_view_partial") }}
	{{ partial("partials/xeditable_view_partial") }}
	{{ partial("partials/xeditable_select_view_partial") }}
	{{ javascript_include('js/search-reference-pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}

	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1Url() ~ '/dbase/' ~ sdbase.idDbase }}';
		
		var myContactModel = {
			list: DS.belongsTo('list'),
			email: DS.attr( 'string' ),
			name: DS.attr( 'string' ),
			lastName: DS.attr( 'string' ),
			status: DS.attr( 'number' ),
			activatedOn: DS.attr('string'),
			bouncedOn: DS.attr('string'),
			subscribedOn: DS.attr('string'),
			unsubscribedOn: DS.attr('string'),
			spamOn: DS.attr('string'),
			ipActive: DS.attr('string'),
			ipSubscribed: DS.attr('string'),
			updatedOn: DS.attr('string'),
			createdOn: DS.attr('string'),
			isBounced: DS.attr('boolean'),
			isSubscribed: DS.attr('boolean'),
			isSpam: DS.attr('boolean'),
			isActive: DS.attr('boolean'),
			isEmailBlocked: DS.attr('boolean'),
			mailHistory: DS.attr('string')
			{%for field in fields%}
			,
				{% if field.type == "Text" %}
					campo{{field.idCustomField }}: DS.attr('string')
				{% elseif field.type == "Date" %}
					campo{{field.idCustomField }}: DS.attr('string')
				{% elseif field.type == "TextArea" %}
					campo{{field.idCustomField }}: DS.attr('string')
				{% elseif field.type == "Numerical" %}
					campo{{field.idCustomField }}: DS.attr('number')
				{% elseif field.type == "Select" %}
					campo{{field.idCustomField }}: DS.attr('string')
				{% elseif field.type == "MultiSelect" %}
					campo{{field.idCustomField }}: DS.attr('string')
				{% endif %}
			
			{%endfor%}
		};
	</script>
	{{ javascript_include('js/app_dbase.js') }}
	{{ javascript_include('js/app_contact.js') }}
	{{ javascript_include('js/app_forms.js') }}
	<script type="text/javascript">
		//ACL de los campos personalizados
		App.customFieldACL = {
			canCreate: {{acl_Ember('api::createcustomfield')}},
			canRead: {{acl_Ember('api::listcustomfields')}},
			canUpdate: {{acl_Ember('api::updatecustomfield')}},
			canDelete: {{acl_Ember('api::delcustomfield')}}
		};
		
		//ACL de contactos
		App.contactACL = {
			canRead: {{acl_Ember('api::listcontacts')}},
			canUpdate: {{acl_Ember('api::updatecontact')}},
			canDelete: {{acl_Ember('api::deletecontact')}}
		}
	</script>
	<script>
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
			{{ ember_customfield_options_xeditable(field) }}
		{%endfor%}
	</script>
	{{ javascript_include('js/editable-ember-view.js')}}
{% endblock %}
{% block sectiontitle %}
	<i class="icon-book"></i> {{sdbase.name}}
{% endblock %}
{% block sectionsubtitle %} {{sdbase.description}} {% endblock %}

{% block content %}

{# Botones de navegacion interna #}
{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}

<div id="emberAppContainer">
	<script type="text/x-handlebars"> 
		<div class="row">
			<h4 class="sectiontitle">Base de datos: {{sdbase.name}}</h4>
			<div class="box-header">
				<ul class="nav nav-tabs nav-tabs-left bs-ember-href">
					{{'{{#link-to "index" tagName="li" href=false disabledWhen="readDisabled"}}<a {{bind-attr href="view.href"}}>General</a>{{/link-to}}'}}
					{{'{{#link-to "fields" tagName="li" href=false}}<a {{bind-attr href="view.href"}}>Campos</a>{{/link-to}}'}}
					{{'{{#link-to "contacts" tagName="li" href=false disabledWhen="readDisabled"}}<a {{bind-attr href="view.href"}}>Contactos</a>{{/link-to}}'}}
					{{'{{#link-to "forms" tagName="li" href=false disabledWhen="readDisabled"}}<a {{bind-attr href="view.href"}}>Formularios</a>{{/link-to}}'}}
				</ul>
			</div>
			<div class="space"></div>
			{{ "{{outlet}}" }}
		</div>
	</script>
	<script type="text/x-handlebars" data-template-name="fields/index">       
		<div class="pull-right">
			{{'{{#link-to "fields.add" class="btn btn-default btn-sm extra-padding" disabledWhen="createDisabled"}}<i class="icon-plus"></i> Agregar campo{{/link-to}}'}}
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<td>Etiqueta</td>
					<td>Tipo</td>
					<td>Requerido</td>
					<td>Valor por Defecto</td>
					<td>Accion</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Email</td>
					<td>Text</td>
					<td>
						<div class="icheckbox_flat-aero checked hover">
						</div>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Nombre</td>
					<td>Text</td>
					<td>
						<div class="icheckbox_flat-aero hover">
						</div>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Apellido</td>
					<td>Text</td>
					<td>
						<div class="icheckbox_flat-aero hover">
						</div>
					</td>
					<td></td>
					<td></td>
				</tr>
			{{'{{#each model}}'}}
				<tr>
					<td>{{'{{name}}'}}</td>
					<td>{{'{{type}}'}}</td>
					<td>
						{{ '{{#if required}}' }}
							<div class="icheckbox_flat-aero checked hover">
							</div>
						{{ '{{else}}' }}
							<div class="icheckbox_flat-aero hover">
							</div>
						{{ '{{/if}}' }}
					</td>
					<td>{{'{{defaultValue}}'}}</td>
					<td>
						{{ '{{#link-to "fields.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<i class="icon-pencil"></i> Editar{{'{{/link-to}}'}}
						{{'{{#link-to "fields.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-delete btn-sm extra-padding"}}'}}<i class="icon-trash"></i> Eliminar {{'{{/link-to}}'}}
					</td>
				</tr>
			{{'{{else}}'}}
				<tr><td colspan="5">No hay campos personalizados</td></tr>
			{{'{{/each}}'}}
			</tbody>
		</table>
	</script>

	<script type="text/x-handlebars" data-template-name="fields">
		{{'{{outlet}}'}}
	</script>
		<!------------------ Ember! ---------------------------------->
		{#   Copntenido del tab general   #}
	<div id="emberAppContainer">
	<script type="text/x-handlebars" data-template-name="index">
		<h4>Información de la base de datos</h4>
		<table class="table">
			<thead></thead>
			<tbody>
				<tr>
					<td style="background-color: {{sdbase.color}} ;">

					</td>
					<td>
						<strong>{{sdbase.name}}</strong>
						<p>{{sdbase.description}}</p>
						<p>{{sdbase.Cdescription}}</p>
					</td>
					<td>
						<p>Creada en <strong>{{date('Y-m-d', sdbase.createdon)}}</strong></p>
					</td>
					<td>
						<p>Actualizada en <strong>{{date('Y-m-d', sdbase.updatedon)}}</strong></p>
					</td>
					<td>
					<td>
						<a href="{{url('dbase/edit')}}/{{sdbase.idDbase}}" class="btn btn-default btn-sm extra-padding"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
						<a data-toggle="modal" href="#modal-simple" data-id="{{ url('dbase/delete/') }}{{sdbase.idDbase}}" class="btn btn-default btn-delete btn-sm extra-padding ShowDialog"><i class="glyphicon glyphicon-trash"></i> Eliminar </a>
						<a href="{{url('statistic/dbase')}}/{{sdbase.idDbase}}" class="btn btn-default btn-sm extra-padding"> <span class="glyphicon glyphicon-stats"> </span></a>
					</td>

				</tr>
			</tbody>
			<tfoot></tfoot>
		</table>	
		<h4>Información de contactos</h4>
		<table class="table table-striped">
			<thead></thead>
			<tbody>
				<tr>
					<td>Contactos totales</td>
					<td><span class="blue big-number pull-right">{{ sdbase.Ctotal|numberf }}</span></td>
					<td><span class="glyphicon glyphicon-user"></span></td>
				</tr>

				<tr>
					<td>Contactos Activos</td>
					<td><span class="blue big-number pull-right">{{ sdbase.Cactive|numberf }}</span></td>
					<td><span class="glyphicon glyphiconicon-ok"></span></td>
				</tr>

				<tr>
					<td>Contactos Inactivos</td>
					<td><span class="blue big-number pull-right"> {{ get_inactive(sdbase)|numberf }}</span></td>
					<td><span class="glyphicon glyphiconicon-thumbs-up"></span></td>
				</tr>

				<tr>
					<td>Contactos Desuscritos</td>
					<td><span class="blue big-number pull-right"> {{ sdbase.Cunsubscribed|numberf }}</span></td>
					<td><span class="glyphicon glyphiconicon-thumbs-down"></span></td>
				</tr>

				<tr>
					<td>Contactos Rebotados</td>
					<td><span class="blue big-number pull-right"> {{sdbase.Cbounced|numberf }}</span></td>
					<td><span class="glyphicon glyphiconicon-remove"></span></td>
				</tr>

				<tr>
					<td>Contactos Spam</td>
					<td><span class="blue big-number pull-right"> {{sdbase.Cspam|numberf }}</span></td>
					<td><span class="glyphicon glyphiconicon-exclamation-sign"></span></td>
				</tr>
			</tbody>
			<tfoot></tfoot>
		</table>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/add">
		<h4>Agregar un nuevo campo</h4>
			{{ '{{#if errors.errormsg}}' }}
				<div class="alert alert-error">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<div class="col-md-8">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Nombre del Campo</label>
						<div class="col-md-6">
							{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus" class="form-control"}} '}}
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Tipo de Formato del Campo</label>
						<div class="col-md-6">
							{{ '{{view Ember.Select
									contentBinding="App.types"
									optionValuePath="content.id"
									optionLabelPath="content.type"
									valueBinding="type" id="type"
									class="form-control"}}'
							 }}
						</div>
					</div>
						{{ '{{#if isText}}' }}
							{{ '{{partial "fields/text"}}' }}
						{{ '{{/if}}' }}
						{{ '{{#if isNumerical}}' }}
							{{ '{{partial "fields/numerical"}}' }}
						{{ '{{/if}}' }}
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Seleccione si desea que el campo sea requerido</label>
						<div class="col-md-6">
							{{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido
						</div>
					</div>

					{{ '{{#unless isDate}}' }}
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Valor por defecto </label>
						<div class="col-md-6">
							{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="defaultValue" class="form-control"}}' }}
						</div>
					</div>
					{{ '{{/unless}}' }}

					{{ '{{#if isSelect}}' }}
						{{ '{{partial "fields/select"}}' }}
					{{ '{{/if}}' }}

					<div class="form-actions">
						<div class="row">
							<div class="col-md-3 col-md-offset-5">
								<button class="btn btn-guardar btn-sm extra-padding" {{' {{action save this}} '}}>Grabar</button>
							</div>
							<div class="col-md-3">
								<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/edit">
		<h4>Editar un campo personalizado</h4>
		<div class="col-md-6">
			{{ '{{#if errors.errormsg}}' }}
				<div class="alert alert-error">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-sm-4 control-label">Nombre del Campo</label>
					<div class="col-md-6">
						{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" class="form-control"}} '}}
					</div>
				</div>
				{{ '{{#if isText}}' }}
					{{ '{{partial "fields/text"}}' }}
				{{ '{{/if}}' }}
				{{ '{{#if isNumerical}}' }}
					{{ '{{partial "fields/numerical"}}' }}
				{{ '{{/if}}' }}
				<div class="form-group">
					<label class="col-sm-4 control-label">Seleccione si desea que el Campo sea Obligatorio</label>
					<div class="col-md-5">
						{{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido
					</div>
				</div>

				{{ '{{#unless isDate}}' }}
				<div class="form-group">
					<label class="col-sm-4 control-label">Valor por defecto </label>
					<div class="col-md-6">
						{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="value_default" class="form-control"}}' }}
					</div>
				{{ '{{/unless}}' }}

				{{ '{{#if isSelect}}' }}
					{{ '{{partial "fields/select"}}' }}
				{{ '{{/if}}' }}
				</div>
				<div class="space"></div>
				<div class="form-actions pull-right">
					<div class="row">
						<div class="col-md-6">
							<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
						</div>
						<div class="col-md-6">
							<button class="btn btn-guardar btn-sm extra-padding" {{' {{action edit this}} '}}>Editar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/remove">
		<h4>Eliminar un campo personalizado</h4>
		<div class="bs-callout bs-callout-danger">			
			<p>Si elimina este campo personalizado se borrará toda la información de los contactos relacionada con este Campo</p>
		</div>
		<p>¿Esta seguro que desea eliminar el Campo <strong>{{'{{name}}'}}</strong>?</p>
			{{ '{{#if errors.errormsg}}' }}
				<div class="alert alert-error">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<button {{'{{action eliminate this}}'}} class="btn btn-danger btn-sm extra-padding">Eliminar</button>
			<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>	
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_select">
		<div class="form-group">
			<label for="values" class="col-sm-4 control-label">Opciones de la lista</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextArea valueBinding="values" placeholder="Valor" id="values"}}' }}
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_text">
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Longitud Maxima del Campo</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextField valueBinding="maxLength" placeholder="Letras" id="maxlong" class="form-control"}}' }}
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_numerical">
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Valor Minimo</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextField valueBinding="minValue" placeholder="Inferior" id="limit_Inf" class="form-control"}}' }}
			</div>
		</div>
		<div class="form-group">
			<label for="limit_Sup" class="col-sm-4 control-label">Valor Maximo</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextField valueBinding="maxValue" placeholder="Superior" id="limit_Sup" class="form-control"}}' }}
			</div>
		</div>
	</script>

	</div>

	<!---------------------- Contacts Template -------------------------->
		{{ partial("dbase/partials/contacts_partial") }}
	
	<!---------------------- Forms Template -------------------------->
		{{ partial("dbase/partials/forms_partial") }}
</div>
{% endblock %}
