{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{#
			{{ partial("partials/date_view_partial") }}
			{{ partial("partials/date_picker_partial") }}
		#}
		{{ partial("partials/xeditable_view_partial") }}
		{{ partial("partials/xeditable_select_view_partial") }}
		{{ javascript_include('js/load_activecontacts.js')}}
		{{ javascript_include('js/search-reference-pagination.js') }}
		{{ javascript_include('js/mixin_config.js') }}
		{{ javascript_include('javascripts/moment/moment.min.js')}}
		{{ javascript_include('date-time-picker-b3/bootstrap-datepicker.js')}}
		{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js') }}
		{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
		{#
		{{ javascript_include('javascripts/moment/moment.min.js')}}
		
		#}
	<script type="text/javascript">
		var MyContactlistUrl = '{{urlManager.getApi_v1Url() ~ '/contactlist/' ~ datalist.idContactlist}}';
		var currentList = {{datalist.idContactlist}};

		{{ partial('partials/contact_model_definition', ['include_list' : true]) }}


	</script>
	{{ javascript_include('js/app_contactlist_contacts.js') }}
	{{ javascript_include('js/app_contact.js') }}
	{{ javascript_include('js/list_model.js') }}
	{{ javascript_include('js/app_contact_list.js') }}
	<script type="text/javascript">
		App.contactACL = {
			canCreate: {{acl_Ember('api::createcontactbylist')}},
			canImportBatch: {{acl_Ember('contacts::importbatch')}},
			canImport: {{acl_Ember('contacts::import')}},
			canUpdate: {{acl_Ember('api::updatecontactbylist')}},
			canDelete: {{acl_Ember('api::deletecontactbylist')}}
		};
	</script>
	<script>
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
			{{ ember_customfield_options_xeditable(field) }}
		{%endfor%}
	</script>
	{{ javascript_include('js/editable-ember-view.js')}}
	<script type="text/x-handlebars" data-template-name="dropdown" >
		<div class="dropdown">
			<button class="btn dropdown-toggle sr-only" type="button" id="dropdownMenu1" data-toggle="dropdown">
				  <span class="caret">Mostrar</span>
			</button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Todos</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Activos</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Suscritos</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Rebotados</a></li>
			</ul>
		</div>
	</script>

{% endblock %}
{% block sectionContactLimit %}
	{{ partial("partials/contactlimitinfo_partial") }}
{%endblock%}	
{% block content %}

	{# Botones de navegacion pequeños #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}
	{# /Botones de navegacion pequeños  #}

	<script type="text/x-handlebars" >
		{{' {{#if errors.email}} '}}
			<span class="text text-error">{{'{{errors.email}}'}}</span>
		{{' {{/if }} '}}
		{{'{{outlet}}'}}
	</script>
	<div id="emberAppContactContainer">
		<script type="text/x-handlebars" data-template-name="contacts">
			<div class="row wrap">
				<div class="sparkline-row col-xs-3">
					<h4 class="blue"> {{ datalist.name }}</h4>
					<span>{{ datalist.description}}</span>
				</div>
				<ul class="list-inline numbers-contacts pull-right">
					<li>Contactos totales <br/> <span class="blue big-number"> {{'{{lista.totalContactsF}}'}} </span></li>
					<li>Activos <br/> <span class="green big-number"> {{'{{lista.activeContactsF}}'}} </span></li>
					<li>Inactivos <br/><span class="sad-blue big-number"> {{'{{lista.inactiveContactsF}}'}} </span></li>
					<li>Desuscritos <br/><span class="gray big-number"> {{'{{lista.unsubscribedContactsF}}'}} </span></li>
					<li>Rebotados <br/><span class="orange big-number"> {{'{{lista.bouncedContactsF}}'}} </span></li>
					<li>Spam <br/><span class="red big-number"> {{'{{lista.spamContactsF}}'}} </span></li>
				</ul>
			</div>
		{{'{{outlet}}'}}
		</script>

		<script type="text/x-handlebars" data-template-name="contacts/index">
			{# formulario para busqueda #}
			<div class="row">

			{#   Busqueda   #}
			{{ partial("partials/search_contacts_partial") }}
					<div class="col-sm-12 col-md-12 col-lg-7">
						<ul class="list-inline pull-right">
							<li>
								{{'{{#link-to "contacts.new" class="btn btn-default extra-padding btn-sm" disabledWhen="createDisabled"}}'}} Crear contacto{{'{{/link-to}}'}}
							</li>
							<li>
								{{'{{#link-to "contacts.newbatch" class="btn btn-default extra-padding btn-sm" disabledWhen="importBatchDisabled"}}'}} Crear contactos rápidamente{{'{{/link-to}}'}}
							</li>
							<li>
								{{ '{{#link-to "contacts.import" class="btn btn-default extra-padding btn-sm" disabledWhen="importDisabled"}}'}} Importar contactos{{'{{/link-to}}'}}	
							</li>

							{#	<a href="{{url('dbase/show/')}}{{datalist.idDbase}}" class="btn btn-default extra-padding btn-sm" title="Configuracion avanzada"><i class="icon-cog"></i></a> #}
						</ul>
					</div>
				</div>
			</div>


			{#   seleccion de contactos a mostrar   #}
			{{ partial("partials/select_contacts_search_partial") }}

			<div class="row">
				<table class="table table-striped table-contacts">
					<thead></thead>
					<tbody>
						{{'{{#each model}}'}}
							{{ partial("partials/contact_view_partial") }}
						{{ '{{else}}' }}
							<tr>
								<td>
									<div class="bs-callout bs-callout-warning">
										<h4>No se encontraron contactos</h4>
										<p>Puede ser que su búsqueda no arrojó resultados, que no existen contactos que cumplan todas las condiciones de filtro o que no hay contactos en esta lista.</p>
										<p>Si desea crear nuevos contactos elija una de las opciones de arriba.
											<ul>
												<li><b>Crear un nuevo contacto:</b> para ingresar datos de un solo contacto</li>
												<li><b>Crear contactos rápidamente:</b> puede crear múltiples contactos rápidamente</li>
												<li><b>Importar contactos:</b> copie contactos de otros sistemas fácilmente</li>
											</ul>
										</p>
									</div>
								</td>
							</tr>
						{{ '{{/each}}' }}
					</tbody>
				</table>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</script>
		
	<script type="text/x-handlebars" data-template-name="contacts/new">
		<div class="row">
			{{ '{{#if App.errormessage }}' }}
				<div class="bs-callout bs-callout-danger">
					{{ '{{ App.errormessage }}' }}
				</div>
		</div>
		{{ '{{/if}} '}}
		<h4 class="sectiontitle">Crear nuevo contacto</h4>
		<div class="col-sm-12 hidden-md hidden-lg">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Cree un nuevo contacto, basta con una dirección de correo electrónico y si desea otros datos básicos como nombre y apellido.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<form  class="form-horizontal" role="form">
				{{ '{{#if errors.errormsg}}' }}
					<div class="bs-callout bs-callout-danger">
						{{ '{{errors.errormsg}}' }}
					</div>
				{{ '{{/if}}' }}
				{{' {{#if errors.email}} '}}
						<span class="text text-error">{{'{{errors.email}}'}}</span>
				{{' {{/if }} '}}
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label"><span class="required">*</span>Email:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus" id="email" class="form-control" placeholder="Email"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="name" class="col-sm-4 control-label">Nombre:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="name" id="name" class="form-control" placeholder="Nombre"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="lastName" class="col-sm-4 control-label">Apellido:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="lastName" id="lastName" class="form-control" placeholder="Apellido"}}'}}
					</div>
				</div>
				
				<div class="form-group">
					<label for="birthDate" class="col-sm-4 control-label">Fecha de nacimiento:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="birthDate" class="form-control date_view_picker"}}'}}
					</div>
				</div>
				<!-- Campos Personalizados -->
				{%for field in fields%}
					<div class="form-group">
						<label for="campo{{field.idCustomField }}" class="col-sm-4 control-label">{{field.name}}:</label>
						<div class="col-md-8">
							{{ember_customfield(field, ['class': 'form-control'] )}}
						</div>
					</div>
				{%endfor%}
				<!--  Fin de campos personalizados -->

				<div class="form-actions pull-right">
					<button class="btn btn-sm btn-default extra-padding" {{'{{action "cancel" this}}'}}>Cancelar</button>
					<button class="btn btn-sm btn-default btn-guardar extra-padding" {{'{{action "save" this}}'}}>Guardar</button>
				</div>
			</form>
			<div class="clearfix"></div>
		</div>
		<div class="hidden-xs hidden-sm col-md-5">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Cree un nuevo contacto, basta con una dirección de correo electrónico y si desea otros datos básicos como nombre y apellido.</p>
					</div>
				</div>
			</div>
		</div>

	</script>
	<script type="text/x-handlebars" data-template-name="contacts/newbatch">
		<div class="row">
			<div class="col-sm-12">
				{{flashSession.output()}}
			</div>
		</div>
		
		<div class="row">
			<h4 class="sectiontitle">Crear contactos rápidamente</h4>
			<div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
				<div class="alert alert-success">
					<img src="{{url('')}}b3/images/how-add-many-contacts.png" class="center-block width-fix" alt="" />
					<div class="space"></div>
					<p>Utilice una línea para cada contacto</p>
					<p>No es necesario incluir todos los campos, el único campo requerido es "email" </p>
					<p>Cree hasta 30 contactos por este medio</p>
				</div>
			</div>
			<div class="col-md-6">
				<form method="post" action="{{url('contacts/newbatch')}}/{{datalist.idContactlist}}" role="form">
					<div class="form-group">
						<label for="info-cont" class="control-label">Información de los contactos:</label>
						<textarea name="arraybatch" class="form-control sm-textarea" rows="3"></textarea>
					</div>
					<div class="form-actions">
						<button class="btn btn-sm btn-default extra-padding" {{'{{action "cancel" this}}'}}>Cancelar</button>
						<button class="btn btn-sm btn-default btn-guardar extra-padding">Guardar</button>
					</div>
				</form>
			</div>
			<div class="hidden-xs hidden-sm col-md-6">
				<div class="alert alert-success">
					<img src="{{url('')}}b3/images/how-add-many-contacts.png" class="center-block width-fix" alt="" />
					<div class="space"></div>
					<p>Utilice una línea para cada contacto</p>
					<p>No es necesario incluir todos los campos, el único campo requerido es "email" </p>
					<p>Cree hasta 30 contactos por este medio</p>
				</div>
			</div>
		</div>
	</script>

	<script type="text/x-handlebars" data-template-name="contacts/delete">
		<div class="row">
			<h4 class="sectiontitle">Eliminar un contacto</h4>
								
							
			<div class="bs-callout bs-callout-danger">
				<p>Recuerde que si el contacto solo esta asociado a ésta lista se eliminará por completo de la 
				base de datos</p>
				<p>¿Está seguro que desea eliminar el contacto <strong>{{'{{name}}'}} ?</strong></p>
			</div>
			{{ '{{#if errors.errormsg}}' }}
				<div class="bs-callout bs-callout-danger">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<button {{'{{action delete this}}'}} class="btn btn-default btn-delete btn-sm extra-padding">Eliminar</button>
			<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
		</div>
	
	</script>
		
	<script type="text/x-handlebars" data-template-name="contacts/import">
		<div class="row">
			<h4 class="sectiontitle">Importar contactos desde archivo .csv</h4>
			<div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
				<div class="alert alert-success">
					<div class="row">
						<div class="col-sm-2">
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-md-9">
							<p>Los programas de hojas de cálculo como Microsoft Excel u OpenOffice.org Calc permiten crear y editar archivos CSV fácilmente.</p>
							<p>El archivo puede ser una tabla con un encabezado que defina los campos que contiene, por ejemplo: email, nombre, apellido, etc</p>
							<p><img src="{{url('')}}b3/images/tabla1.jpg" class="center-block width-fix" alt="" /></p>
							<p>También puede ser una tabla sin encabezados.</p>
							<p>Al guardar el documento, seleccione tipo de archivo: (*.csv) que significa: delimitado por comas.</p>
							<p>El archivo debe incluir al menos un campo para la dirección de correo electrónico.</p>
							<p>Importe máximo hasta 100.000 contactos</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<img src="{{url('')}}b3/images/step1-import.png" class="width-fix" alt="" />
				<div class="space"></div>
				<form method="post" class="form-horizontal"  action="{{url('contacts/import#/contacts')}}" enctype="multipart/form-data" role="form">
					<div class="form-group">
						<input name="importFile" type="file" >
						<input type="hidden" name="idcontactlist" value={{datalist.idContactlist}}>
					</div>
					<a href="{{url('contactlist/show/')}}{{datalist.idContactlist}}#/contacts" class="btn btn-default btn-sm extra-padding">Cancelar</a>
					{{submit_button('class': "btn btn-default btn-sm btn-guardar extra-padding", "Cargar")}}
				</form>
				<div class="clearfix"></div>
				<div class="space"></div>
			</div>
			<div class="hidden-xs hidden-sm col-md-6">
				<div class="alert alert-success">
					<div class="row">
						<div class="col-sm-2">
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-md-9">
							<p>Los programas de hojas de cálculo como Microsoft Excel u OpenOffice.org Calc permiten crear y editar archivos CSV fácilmente.</p>
							<p>El archivo debe ser una tabla con un encabezado que defina los campos que contiene, por ejemplo: email, nombre, apellido, etc</p>
							<p><img src="{{url('')}}b3/images/tabla1.jpg" class="center-block width-fix" alt="" /></p>
							<p>También puede ser una tabla sin encabezados.</p>
							<p>Al guardar el documento, seleccione tipo de archivo: (*.csv) que significa: delimitado por comas.</p>
							<p>El archivo debe incluir al menos un campo para la dirección de correo electrónico.</p>
							<p>Importe máximo hasta 100.000 contactos</p>
						</div>
					</div>
				</div>
			</div>
			{{ flashSession.output() }}
		</div>
	</script>
{% endblock %}
