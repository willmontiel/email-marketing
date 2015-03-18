{% extends "templates/index_b3.volt" %}

{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ partial("partials/date_view_partial") }}
	{{ partial("partials/xeditable_view_partial") }}
	{{ partial("partials/xeditable_select_view_partial") }}
	{{ partial("statistic/partials/partial_graph") }}
	{{ javascript_include('vendors/bootstrap_v2/spectrum/js/spectrum.js') }}
	{{ javascript_include('js/search-reference-pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/mixin_save_form.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('vendors/datetime_picker_jquery/jquery.datetimepicker.js')}}
	{{ stylesheet_link('vendors/bootstrap_v2/spectrum/css/spectrum.css') }}
	{{ stylesheet_link('vendors/datetime_picker_jquery/jquery.datetimepicker.css') }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}

	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1Url() ~ '/dbase/' ~ sdbase.idDbase }}';
		
		var myContactModel = {
			list: DS.belongsTo('list'),
			email: DS.attr('string'),
			name: DS.attr('string'),
			lastName: DS.attr('string'),
			birthDate: DS.attr('string'),
			status: DS.attr('number'),
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
	{{ javascript_include('js/forms/email_block.js') }}
	{{ javascript_include('js/forms/text_block.js') }}
	{{ javascript_include('js/forms/select_block.js') }}
	{{ javascript_include('js/forms/multiple_select_block.js') }}
	{{ javascript_include('js/forms/date_block.js') }}
	{{ javascript_include('js/forms/form_zone.js') }}
	{{ javascript_include('js/forms/zone_creator.js') }}
	{{ javascript_include('js/forms/header_zone.js') }}
	{{ javascript_include('js/forms/tools_zone.js') }}
	
	<script type="text/javascript">
		//Contactos por dominios
		App.data = [];
		{%for domain in domains %}
			var obj = new Object;
				obj.name = '{{ domain.domain }}';
				obj.y = {{ domain.total }};

				App.data.push(obj);
		{%endfor%}
		
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
	
	<script type="text/javascript">
		App.formfields = new Array();
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
			{{ ember_customfield_options_xeditable(field) }}
			App.formfields.push({id: {{field.idCustomField}}, 
								name: '{{field.name}}', 
								type: '{{field.type}}',
								required: '{{field.required}}',
								values: '{{field.values}}'});
		{%endfor%}
		
		var config = {baseUrl: "{{url('')}}"};
		
		function iframeResize() {
			var iFrame = document.getElementById('iframeEditor');
			iFrame.height = iFrame.contentWindow.document.body.scrollHeight + "px";
		};
	</script>
	{{ javascript_include('js/editable-ember-view.js')}}
{% endblock %}
{% block content %}

{# Botones de navegacion interna #}
{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}

<div id="emberAppContainer">
	<script type="text/x-handlebars"> 
		<div class="row header-background" style="border-top: 1px solid {{sdbase.color}};">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<div class="header">
					<div class="title">{{sdbase.name}}</div>
					<div class="title-info">Creada el {{date('d/M/Y', sdbase.createdon)}}</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
				<div class="contact-indicator">
					<span class="total-contacts">{{ sdbase.Ctotal|numberf }}</span><br /> 
					<span class="text-contacts">Contactos</span>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="space"></div>
		
		<hr>
		
		<div class="row">
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
		<div class="bs-callout bs-callout-info">
			Cree hasta 10 campos personalizados, para completar información de sus contactos
			<div class="text-right">
				{{'{{#link-to "fields.add" class="btn btn-default btn-sm extra-padding" disabledWhen="createDisabled"}}<span class="glyphicon glyphicon-plus"></span> Agregar campo{{/link-to}}'}}
			</div>
		</div>
		
		<div class="clearfix"></div>
		<div class="space"></div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Etiqueta</th>
					<th>Tipo</th>
					<th>Requerido</th>
					<th>Valor por defecto</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<tr class="primary-field">
					<td>Email</td>
					<td>Text</td>
					<td>
						<input type="checkbox" checked="checked" disabled="disabled">
					</td>
					<td> ------ </td>
					<td> ------ </td>
				</tr>
				<tr class="primary-field">
					<td>Nombre</td>
					<td>Text</td>
					<td>
						<input type="checkbox" disabled="disabled">
					</td>
					<td> ------ </td>
					<td> ------ </td>
				</tr>
				<tr class="primary-field">
					<td>Apellido</td>
					<td>Text</td>
					<td>
						<input type="checkbox" disabled="disabled">
					</td>
					<td> ------ </td>
					<td> ------ </td>
				</tr>
				<tr class="primary-field">
					<td>Fecha de nacimiento</td>
					<td>Date</td>
					<td>
						<input type="checkbox" disabled="disabled">
					</td>
					<td> ------ </td>
					<td> ------ </td>
				</tr>
			{{'{{#each model}}'}}
				<tr>
					<td>{{'{{name}}'}}</td>
					<td>{{'{{type}}'}}</td>
					<td>
						{{ '{{#if required}}' }}
							<input type="checkbox" checked="checked" disabled="disabled">
						{{ '{{else}}' }}
							<input type="checkbox" disabled="disabled">
						{{ '{{/if}}' }}
					</td>
					<td>
						{{ '{{#if defaultValue}}' }}
							{{'{{defaultValue}}'}}
						{{ '{{else}}' }}
							 ------ 
						{{ '{{/if}}' }}
					</td>
					<td>
						{{ '{{#link-to "fields.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<span class="ghyphicon ghyphicon-pencil"></span> Editar{{'{{/link-to}}'}}
						{{'{{#link-to "fields.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-delete btn-sm extra-padding"}}'}}<span class="ghyphicon ghyphicon-trash"></span> Eliminar {{'{{/link-to}}'}}
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
		{#   Contenido del tab general   #}
	<script type="text/x-handlebars" data-template-name="index">
		<div class="space"></div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="text-block">
					<div class="medium-title" style="text-align: left !important;">Descripción:</div>
					<p style="font-size: 12px; color: #777;">
						{{sdbase.description}} <br />
						{{sdbase.Cdescription}} 
					</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
				<a href="{{url('dbase/edit')}}/{{sdbase.idDbase}}" class="btn btn-default btn-sm">
					<span class="glyphicon glyphicon-pencil"></span> Editar
				</a>

				<a data-toggle="modal" href="#modal-simple" data-id="{{ url('dbase/delete/') }}{{sdbase.idDbase}}" class="btn btn-default btn-delete btn-sm">
					<span class="glyphicon glyphicon-trash"></span> Eliminar
				</a>

				<a href="{{url('statistic/dbase')}}/{{sdbase.idDbase}}" class="btn btn-default btn-sm"> 
					<span class="glyphicon glyphicon-stats"> </span> Estadísticas
				</a>
			</div>
		</div>
		
		<div class="clearfix"></div>
		<div class="space"></div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="big-title">Información de contactos</div>
				<table class="table table-contacts table-striped">
					<thead></thead>
					<tbody>
						<tr>
							<td class="">Contactos totales</td>
							<td><span class="blue big-number pull-right">{{ sdbase.Ctotal|numberf }}</span></td>
						</tr>

						<tr>
							<td>Contactos Activos</td>
							<td><span class="green big-number pull-right">{{ sdbase.Cactive|numberf }}</span></td>
						</tr>

						<tr>
							<td>Contactos Inactivos</td>
							<td><span class="sad-blue big-number pull-right"> {{ get_inactive(sdbase)|numberf }}</span></td>
						</tr>

						<tr>
							<td>Contactos Desuscritos</td>
							<td><span class="gray big-number pull-right"> {{ sdbase.Cunsubscribed|numberf }}</span></td>
						</tr>

						<tr>
							<td>Contactos Rebotados</td>
							<td><span class="orange big-number pull-right"> {{sdbase.Cbounced|numberf }}</span></td>
						</tr>

						<tr>
							<td>Contactos Spam</td>
							<td><span class="red big-number pull-right"> {{sdbase.Cspam|numberf }}</span></td>
						</tr>
					</tbody>
					<tfoot></tfoot>
				</table>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-right">
				{{'{{view App.TimeGraphView}}'}}
			</div>
		</div>

		<div class="clearfix"></div>
		<div class="space"></div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/add">
	{#   Tab campos   #}
			{{ '{{#if errors.errormsg}}' }}
				<div class="bs-callout bs-callout-danger">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<div class="col-md-8">
				<h4 class="text-center">Agregar un nuevo campo</h4>
				<div class="space"></div>
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">*Nombre del campo</label>
						<div class="col-md-6">
							{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus" class="form-control"}} '}}
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Tipo de formato del campo</label>
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
						<div class="col-md-6 padding-top">
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
						<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
						<button class="btn btn-default btn-guardar btn-sm extra-padding" {{' {{action "save" this}} '}}>Guardar</button>
					</div>
				</form>
			</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/edit">
		<h4>Editar un campo personalizado</h4>
		<div class="col-md-6">
			{{ '{{#if errors.errormsg}}' }}
				<div class="bs-callout bs-callout-danger">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-sm-4 control-label">Nombre del campo</label>
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
					<label class="col-sm-4 control-label">Seleccione si desea que el campo sea obligatorio</label>
					<div class="col-md-5 padding-top">
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
				<div class="form-actions">
					<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
					<button class="btn btn-default btn-guardar btn-sm extra-padding" {{' {{action edit this}} '}}>Guardar</button>
				</div>
			</form>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/remove">
		<h4>Eliminar un campo personalizado</h4>
		<div class="bs-callout bs-callout-danger">			
			<p>Si elimina este campo personalizado, se borrará toda la información de los contactos relacionada con este campo</p>
		</div>
		<p>¿Está seguro que desea eliminar el campo <strong>{{'{{name}}'}}</strong>?</p>
			{{ '{{#if errors.errormsg}}' }}
				<div class="bs-callout bs-callout-danger">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<button {{'{{action eliminate this}}'}} class="btn btn-default btn-delete btn-sm extra-padding">Eliminar</button>
			<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>	
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_select">
		<div class="form-group">
			<label for="values" class="col-sm-4 control-label">Opciones de la lista</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextArea valueBinding="values" placeholder="Cada línea es un valor" id="values" class="form-control"}}' }}
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_text">
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Longitud máxima del campo</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextField valueBinding="maxLength" placeholder="Letras" id="maxlong" class="form-control"}}' }}
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_numerical">
		<div class="form-group">
			<label for="" class="col-sm-4 control-label">Valor mínimo</label>
			<div class="col-md-6">
				{{ '{{view Ember.TextField valueBinding="minValue" placeholder="Inferior" id="limit_Inf" class="form-control"}}' }}
			</div>
		</div>
		<div class="form-group">
			<label for="limit_Sup" class="col-sm-4 control-label">Valor máximo</label>
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


{#  Este es el modal (lightbox) que se activa al hacer clic en el boton eliminar   #}
<div id="modal-simple" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Eliminar base de datos</h4>
			</div>
			<div class="modal-body">
				<p>
					¿Está seguro que desea eliminar esta base de datos?
				</p>
				<p>
					Recuerde que si elimina la base de datos se perderán todos los contactos, listas de contactos y segmentos que pertenezcan a ella
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
				<a href="" id="deleteDb" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteDb").attr('href', myURL );
	});
</script>

</div>
{% endblock %}
