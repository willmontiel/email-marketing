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
<div id="emberAppContainer">
	<script type="text/x-handlebars"> 
		<div class="row">
			<div class="box-header">
				<ul class="nav nav-tabs nav-tabs-left">
					{{'{{#link-to "index" tagName="li" href=false disabledWhen="readDisabled"}}<a {{bind-attr href="view.href"}}>General</a>{{/link-to}}'}}
					{{'{{#link-to "fields" tagName="li" href=false}}<a {{bind-attr href="view.href"}}>Campos</a>{{/link-to}}'}}
					{{'{{#link-to "contacts" tagName="li" href=false disabledWhen="readDisabled"}}<a {{bind-attr href="view.href"}}>Contactos</a>{{/link-to}}'}}
					{{'{{#link-to "forms" tagName="li" href=false disabledWhen="readDisabled"}}<a {{bind-attr href="view.href"}}>Formularios</a>{{/link-to}}'}}
				</ul>
			</div>
			{{ "{{outlet}}" }}
		</div>
	</script>
	<script type="text/x-handlebars" data-template-name="fields/index">       
		<div class="row">
			<div class="span12">
				<div class="box">
					<div class="box-content">
						<div class="box-section news with-icons">
							<div class="avatar blue">
								<i class="icon-tags icon-2x"></i>
							</div>
							<div class="news-time">
							</div>
							<div class="news-content">
								<div class="news-title">
									Campos de la Base de Datos
								</div>
								<div class="news-text">
									Esta seccion esta dedicada a la Lectura
									y Edicion de los Campos Personalizados
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span12 padded text-right">
				{{'{{#link-to "fields.add" class="btn btn-default" disabledWhen="createDisabled"}}<i class="icon-plus"></i> Agregar campo{{/link-to}}'}}
			</div>
		</div>
		<div class="row">
						<table class="table table-normal">
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
										<div class="pull-right">
											<div class="btn-group">
												<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li>{{ '{{#link-to "fields.edit" this disabledWhen="controller.updateDisabled"}}' }}<i class="icon-pencil"></i> Editar{{'{{/link-to}}'}}</li>
													<li>{{'{{#link-to "fields.remove" this disabledWhen="controller.deleteDisabled"}}'}}<i class="icon-trash"></i> Eliminar {{'{{/link-to}}'}}</li>
												</ul>
											</div>
										</div>


									</td>
								</tr>
							{{'{{else}}'}}
								<tr><td colspan="5">No hay campos personalizados</td></tr>
							{{'{{/each}}'}}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields">
		{{'{{outlet}}'}}
	</script>
	<div class="row">
		<div class="row">
			<div class="span12 text-right">
				<a href="{{url('dbase')}}" class="btn btn-default"><i class="icon-reply"></i> Bases de datos</a>
			</div>
		</div>
	</div>
		<!------------------ Ember! ---------------------------------->
	<div id="emberAppContainer">
	<script type="text/x-handlebars" data-template-name="index">
		<div class="row">
			<div class="span6">
				<div class="box">
					<div class="box-header">
						<div class="title">
							Información de la base de datos
						</div>
					</div>
					<div class="box-content padded">
						<div class="box-section news with-icons">
							<div class="avatar purple">
								<i class="icon-book icon-2x"></i>
							</div>
							<div class="news-content">
								<div class="news-title">
									{{sdbase.name}}
								</div>
								<div class="news-text">
									<p>{{sdbase.description}}</p>
									<p>{{sdbase.Cdescription}}</p>
									Creada en {{date('Y-m-d', sdbase.createdon)}}<br />
									Actualizada en {{date('Y-m-d', sdbase.updatedon)}}
								</div>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="span2">
								<a href="{{url('dbase/edit')}}/{{sdbase.idDbase}}" class="btn btn-default">Editar</a>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div class="span6">
				<div class="box">
					<div class="box-header">
						<div class="title">Información de contactos</div>
					</div>
					<div class="box-content">
						<table class="table table-normal">
							<tbody>
								<tr>
									<td><i class="icon-user"></i> Contactos totales</td>
									<td class="status-success"><span class="label label-blue">{{ sdbase.Ctotal|numberf }}</span></td>
								</tr>

								<tr>
									<td><i class="icon-ok"></i> Contactos Activos</td>
									<td class="status-success"><span class="label label-green">{{ sdbase.Cactive|numberf }}</span></td>
								</tr>

								<tr>
									<td><i class="icon-question-sign"></i> Contactos Inactivos</td>
									<td class="status-error"><span class="label label-gray"> {{ get_inactive(sdbase)|numberf }}</span></td>
								</tr>

								<tr>
									<td><i class="icon-check-empty"></i> Contactos Desuscritos</td>
									<td class="status-success"><span class="label label-warning"> {{ sdbase.Cunsubscribed|numberf }}</span></td>
								</tr>

								<tr>
									<td><i class="icon-retweet"></i> Contactos Rebotados</td>
									<td class="status-success"><span class="label label-red"> {{sdbase.Cbounced|numberf }}</span></td>
								</tr>

								<tr>
									<td><i class="icon-exclamation-sign"></i> Contactos Spam</td>
									<td class="status-success"><span class="label label-red"> {{sdbase.Cspam|numberf }}</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/add">
		<div class="row">
			<div class="box span4">
				<div class="box-header">
					<div class="title">
						Agregar un nuevo campo
					</div>
				</div>
				<div class="box-content padded">
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					<form>
						<label>Nombre del Campo</label>
						{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus"}} '}}

						<label>Tipo de Formato del Campo</label>
							{{ '{{view Ember.Select
									contentBinding="App.types"
									optionValuePath="content.id"
									optionLabelPath="content.type"
									valueBinding="type" id="type"}}'
							 }}
						<br />
							{{ '{{#if isText}}' }}
								{{ '{{partial "fields/text"}}' }}
							{{ '{{/if}}' }}
							{{ '{{#if isNumerical}}' }}
								{{ '{{partial "fields/numerical"}}' }}
							{{ '{{/if}}' }}

						<label>Seleccione si desea que el campo sea requerido</label>
						{{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido

						<br /><br />
						{{ '{{#unless isDate}}' }}
							<label>Valor por defecto </label>
							{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="defaultValue"}}' }}
						{{ '{{/unless}}' }}

						{{ '{{#if isSelect}}' }}
							{{ '{{partial "fields/select"}}' }}
						{{ '{{/if}}' }}

						<br />
						<button class="btn btn-blue" {{' {{action save this}} '}}>Grabar</button>
						<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
					</form>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/edit">
		<div class="row">
			<div class="box span4">
				<div class="box-header">
					<div class="title">
						Editar un campo personalizado
					</div>
				</div>
				<div class="box-content padded">
					<form>
						{{ '{{#if errors.errormsg}}' }}
							<div class="alert alert-error">
								{{ '{{errors.errormsg}}' }}
							</div>
						{{ '{{/if}}' }}
						<label>Nombre del Campo</label>
						{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name"}} '}}

						<br />
						{{ '{{#if isText}}' }}
							{{ '{{partial "fields/text"}}' }}
						{{ '{{/if}}' }}
						{{ '{{#if isNumerical}}' }}
							{{ '{{partial "fields/numerical"}}' }}
						{{ '{{/if}}' }}

						<label>Seleccione si desea que el Campo sea Obligatorio</label>
						{{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido

						<br /><br />
						{{ '{{#unless isDate}}' }}
							<label>Valor por defecto </label>
							{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="value_default"}}' }}
						{{ '{{/unless}}' }}

						{{ '{{#if isSelect}}' }}
							{{ '{{partial "fields/select"}}' }}
						{{ '{{/if}}' }}
						<br />	
						<button class="btn btn-blue" {{' {{action edit this}} '}}>Editar</button>
						<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/remove">
		<div class="row">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Eliminar un campo personalizado
					</div>
				</div>
				<div class="box-content padded">			
					<p>Si elimina este campo personalizado se borrará toda la información de los contactos relacionada con este Campo</p>
					<p>¿Esta seguro que desea eliminar el Campo <strong>{{'{{name}}'}}</strong>?</p>
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					<button {{'{{action eliminate this}}'}} class="btn btn-danger">Eliminar</button>
					<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>	
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_select">
		<label for="values">Opciones de la lista</label>
		{{ '{{view Ember.TextArea valueBinding="values" placeholder="Valor" id="values"}}' }}
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_text">
		<label>Longitud Maxima del Campo</label>
		{{ '{{view Ember.TextField valueBinding="maxLength" placeholder="Letras" id="maxlong"}}' }}
	</script>

	<script type="text/x-handlebars" data-template-name="fields/_numerical">
		<label>Valor Minimo</label>
		{{ '{{view Ember.TextField valueBinding="minValue" placeholder="Inferior" id="limit_Inf"}}' }}

		<label for="limit_Sup">Valor Maximo</label>
		{{ '{{view Ember.TextField valueBinding="maxValue" placeholder="Superior" id="limit_Sup"}}' }}
	</script>

	</div>

	<!---------------------- Contacts Template -------------------------->
		{{ partial("dbase/partials/contacts_partial") }}
	
	<!---------------------- Forms Template -------------------------->
		{{ partial("dbase/partials/forms_partial") }}
</div>
{% endblock %}
