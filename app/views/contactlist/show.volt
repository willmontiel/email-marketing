{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ partial("partials/date_view_partial") }}
		{{ partial("partials/xeditable_view_partial") }}
		{{ partial("partials/xeditable_select_view_partial") }}
		{{ javascript_include('js/load_activecontacts.js')}}
		{{ javascript_include('js/search-reference-pagination.js') }}
		{{ javascript_include('js/mixin_config.js') }}
		{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js') }}
		{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
		{#{{ javascript_include('javascripts/moment/moment.min.js')}}#}
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

{% block sectiontitle %}Lista: <strong>{{datalist.name}}</strong>{% endblock %}

{%block sectionsubtitle %}{{datalist.description}}{% endblock %}
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
												<li><b>Crear varios contactos:</b> puede crear múltiples contactos rapidamente</li>
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
		<div class="col-md-5">
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
					<label for="Email" class="col-sm-4 control-label"><span class="required">*</span>Email:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus" id="email" class="form-control" placeholder="Email"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="nombre" class="col-sm-4 control-label">Nombre:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="name" id="name" class="form-control" placeholder="Nombre"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="apellido" class="col-sm-4 control-label">Apellido:</label>
					<div class="col-md-8">
						{{'{{view Ember.TextField valueBinding="lastName" id="lastName" class="form-control" id="Apellido" placeholder="Apellido"}}'}}
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
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-sm btn-default extra-padding" {{'{{action "cancel" this}}'}}>Cancelar</button>
						</div>
						<div class="col-xs-6">
							<button class="btn btn-sm btn-default btn-guardar extra-padding" {{'{{action "save" this}}'}}>Guardar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-6">
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
			<h4 class="sectiontitle">Crear contactos rápidamente</h4>
			<div class="col-md-5">
				<form method="post" action="{{url('contacts/newbatch')}}/{{datalist.idContactlist}}" role="form">
					<div class="form-group">
						<label for="info-cont" class="control-label">Información de los contactos:</label>
						<textarea name="arraybatch" class="form-control sm-textarea" rows="3"></textarea>
					</div>
					<div class="form-actions pull-right">
						<div class="row">
							<div class="col-xs-6">
								<button class="btn btn-sm btn-default extra-padding" {{'{{action "cancel" this}}'}}>Cancelar</button>
							</div>
							<div class="col-xs-6">
								<button class="btn btn-sm btn-default btn-guardar extra-padding">Guardar</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-6">
				<div class="alert alert-success">
					<img src="{{url('')}}b3/images/how-add-many-contacts.png" class="center-block" alt="" />
					<div class="space"></div>
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
				<p>Recuerde que si el contacto solo esta asociado a esta lista se eliminará por completo de la 
				Base de Datos</p>
				<p>¿Esta seguro que desea Eliminar el Contacto <strong>{{'{{name}}'}} ?</strong></p>
			</div>
			{{ '{{#if errors.errormsg}}' }}
				<div class="bs-callout bs-callout-danger">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<button {{'{{action delete this}}'}} class="btn btn-danger btn-sm extra-padding">Eliminar</button>
			<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>
		</div>
	</script>
		
	<script type="text/x-handlebars" data-template-name="contacts/import">
		<div class="row">
			<h4 class="sectiontitle">Importar contactos desde archivo .csv a <span>Lista de Iván</span></h4>
			<div class="col-md-6">
				<img src="{{url('')}}b3/images/step1-import.png" class="" alt="" />
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
{#
				<div class="well relative span8">
					<p>
						Aqui puede importar contactos desde un archivo 
						<a rel="tooltip" data-placement="right" data-original-title="La extensión de archivo CSV significa Comma Separated Values (Valores separados por comas). El formato es utilizado en muchos programas de bases de datos, hojas de cálculo y gestores de contactos para almacenar listas de información. Como un archivo de texto, el formato es ampliamente compatible">
							.csv
						</a>
						Haga clic en el botón más (+) elija el archivo .csv que desea cargar y a continuación haga clic en el botón cargar, o en cancelar si
						no desea continuar.
					</p>
				</div>

				<div class="">
					<div class="well relative span12">
						<div class="easy-pie-step span6"  data-percent="50"><span>1/2</span></div>
						<span class="triangle-button blue"><i class="icon-lightbulb"></i></span>
						<div class="span7"><strong>Primer paso: </strong><br />
						Seleccionar el archivo .csv que contiene los contactos
						</div>
					</div>
				</div>
#}	
			</div>

			<div class="col-md-6">
				<div class="alert alert-success">
					<div class="row">
						<div class="col-sm-2">
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-md-9">
							<p>Los programas de hojas de cálculo como Microsoft Excel u OpenOffice.org Calc permiten crear y editar archivos CSV fácilmente.</p>
						<p>El archivo debe ser una tabla con un encabezado que defina los campos que contiene, por ejemplo: email, nombre, apellido, etc</p>
						<p><img src="{{url('')}}b3/images/tabla1.jpg" class="center-block" alt="" /></p>
						<p>También puede ser una tabla sin encabezados.</p>
						<p>Al guardar el documento, seleccione tipo de archivo: (*.csv) que significa: delimitado por comas.</p>
						<p>El archivo debe incluir al menos un campo para la dirección de correo electrónico.</p>
						<p></p>
						</div>
					</div>
				</div>
			</div>

				{{ flashSession.output() }}
{#
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">Haga clic aqui para más información</a>
				</div>
				<div class="clearfix"></div>
				<div id="collapseOne" class="accordion-body collapse" style="height: 0px;">
					<div class="accordion-inner box">
					<p>
						Los programas de hojas de cálculo como Microsoft Excel u OpenOffice.org Calc 
						permiten crear y editar archivos CSV fácilmente.
					</p>
					<p>
						El formato de este archivo debe ser una tabla con una cabecera o línea de título 
						(No es obligatorio) que defina los campos que contiene, por ejemplo: nombre, dirección de correo electrónico, etc. 
						Si desea cargar un archivo existente, asegurese de que siga los criterios que le mostraremos 
						a continuación, de lo contrario, si desea crear un nuevo archivo y necesita alguna orientación,
						a continuación le ofrecemos algunas pautas que le servirán como guía:
					</p>
					<ul>
						<li>El archivo debe incluir al menos un campo para la dirección de correo electrónico, por ejemplo:</li>
					</ul>
					<div class="row">
						<h4 class="sectiontitle">Archivo .csv con cabecera</h4>				
						<table class="table table-striped table-contacts">
							<thead></thead>
							<tbody>
								<tr class="status-pending">
									<td>Cabecera</td>
									<td><strong>Email</strong></td>
								</tr>
								<tr class="status-pending">
									<td>Datos de contactos</td>
									<td>micorreo@noreply.com</td>
								</tr>
								<tr class="status-pending">
									<td></td>
									<td>micorreo2@noreply.com</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<h4 class="sectiontitle">Archivo .csv sin cabecera</h4>				
				<table class="table table-striped table-contacts">
					<thead></thead>
					<tbody>
						<tr class="status-pending">
						  <td>Datos de contactos</td>
						  <td>micorreo@noreply.com</td>
						</tr>
						<tr class="status-pending">
						  <td></td>
						  <td>micorreo2@noreply.com</td>
						</tr>
					</tbody>
				</table>
				<ul>
					<li>
						Si desea ingresar mas campos, y no desea poner cabecera puede separar por comas (,), punto 
						y coma (;), o barras (/),  cada uno de los campos, luego la aplicación se encargará de
						separarlos, eso si asegurese de cumplir los criterios, por ejemplo:
					</li>
				</ul>
				<div class="row">
					<h4 class="sectiontitle">Archivo .csv con cabecera</h4>				
					<table class="table table-striped table-contacts">
						<thead></thead>
						<tbody>
							<tr class="status-pending">
								<td>Cabecera</td>
								<td><strong>Email, Nombre, Apellido</strong></td>
							</tr>
							<tr class="status-pending">
							  <td>Datos de contactos</td>
							  <td>micorreo@noreply.com, Antonio, Lopez</td>
							</tr>
							<tr class="status-pending">
							  <td></td>
							  <td>micorreo2@noreply.com, Luz María, Rodriguez</td>
							</tr>
						</tbody>
					</table>
				</div>
				<h4 class="sectiontitle">Archivo .csv sin cabecera</h4>				
				<table class="table table-striped table-contacts">
					<thead></thead>
					<tbody>
						<tr class="status-pending">
						  <td>Datos de contactos</td>
						  <td>micorreo@noreply.com, Antonio, Caicedo</td>
						</tr>
						<tr class="status-pending">
						  <td></td>
						  <td>micorreo2@noreply.com, Luz María, Rodriguez</td>
						</tr>
					</tbody>
				</table>
				<p>
					Una vez que haya introducido todos los contactos en una tabla, guarde el documento y seleccione CSV (delimitado por comas) 
					(*.csv) como el tipo de archivo que desea guardar.
					Una vez que haya guardado el archivo, y este seguro de haber seguido los criterios anteriores puede pasar a importar sus contactos a la aplicación.
				</p>
			</div>
		</div>
#}
	</script>

{#		<script type="text/x-handlebars" data-template-name="contacts/newimport">
			<div class="row">
				{{' {{#with App.records}} '}}
				{{' {{#each row1}} '}}
										<tr>
											<td>{{' {{this}} '}}</td>
											<td>
												<select>
													<option value="email">Email</option>
													<option value="name">Nombre</option>
													<option value="lastname">Apellido</option>
													{% for field in fields %}
														<option value="{{field.idCustomField}}">{{field.name}}</option>
													{%endfor%}
												</select>
											</td>
										</tr>
									{{' {{/each}} '}}
						{{' {{/with}} '}}


							Delimitador:
							<select>
								<option value="coma" selected>,</option>
								<option value="puntocoma">;</option>
								<option value="slash">/</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="span7">

							<table>
							{{' {{#with App.records}} '}}
								<tr>
									{{' {{#each row1}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each row2}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each row3}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each row4}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each row5}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
							{{' {{/with}} '}}
							</table>
						</div>
					</div>
				</div>

				<div class="span4">
					Como queda guardada la info
				</div>
			</div>
		</script>
#}
{% endblock %}
