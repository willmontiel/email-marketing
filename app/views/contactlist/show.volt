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
		{#{{ javascript_include('javascripts/moment/moment.min.js')}}#}
<script type="text/javascript">
		var MyContactlistUrl = '{{urlManager.getApi_v1Url() ~ '/contactlist/' ~ datalist.idContactlist}}';
		var currentList = {{datalist.idContactlist}};

		var myContactModel = {
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
			mailHistory: DS.attr('string'),
			list: DS.belongsTo('list'),
			isReallyActive: function () {
				if (this.get('isActive') && this.get('isSubscribed') && !(this.get('isSpam') || this.get('isBounced'))) {
					return true;
				}
				return false;
			}.property('isSubscribed,isActive'),
			mailHistoryArray: function () {
				return JSON.parse(this.get('mailHistory'))
			}.property()

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
					<h4 class="blue">Lista de Iván </h4>
					<span>Lista de prueba para iván</span>
				</div>
				<ul class="list-inline numbers-contacts pull-right">
					<li>Contactos totales <br/> <span class="blue"> {{'{{lista.totalContactsF}}'}} </span></li>
					<li>Activos <br/> <span class="green"> {{'{{lista.activeContactsF}}'}} </span></li>
					<li>Inactivos <br/><span class="sad-blue"> {{'{{lista.inactiveContactsF}}'}} </span></li>
					<li>Desuscritos <br/><span class="gray"> {{'{{lista.unsubscribedContactsF}}'}} </span></li>
					<li>Rebotados <br/><span class="orange"> {{'{{lista.bouncedContactsF}}'}} </span></li>
					<li>Spam <br/><span class="red"> {{'{{lista.spamContactsF}}'}} </span></li>
				</ul>
			</div>
		{{'{{outlet}}'}}
		</script>

		<script type="text/x-handlebars" data-template-name="contacts/index">
			{# formulario para busqueda #}
			<div class="row">
				<div class="col-md-5 col-sm-12 form-search">
					<form role="form">
						<div class="form-group">
							<div class="input-group">
								{{' {{view Ember.TextField valueBinding="searchCriteria" onEvent="enter" action="search" type="text" autofocus="autofocus" class="form-control" id="search" placeholder="Correo, @dominio, nombres, apellidos, combinaciones"}}'}}
								<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-7 col-sm-12">
					<ul class="list-inline pull-right">
						<li>
							<a href="{{url('contactlist#/lists')}}" class="btn btn-default extra-padding btn-sm" role="button"><i class="icon-home"></i> Todas las listas</a>
						</li>
						<li>
							{{'{{#link-to "contacts.new" class="btn btn-default extra-padding btn-sm" disabledWhen="createDisabled"}}'}}<i class="icon-plus"></i> Crear Contacto{{'{{/link-to}}'}}
						</li>
						<li>
							{{'{{#link-to "contacts.newbatch" class="btn btn-default extra-padding btn-sm" disabledWhen="importBatchDisabled"}}'}}<i class="icon-align-justify"></i> Crear Varios Contactos{{'{{/link-to}}'}}
						</li>
						<li>
							{{ '{{#link-to "contacts.import" class="btn btn-default extra-padding btn-sm" disabledWhen="importDisabled"}}'}}<i class="icon-file-alt"></i> Importar Contactos{{'{{/link-to}}'}}	
						</li>

						{#	<a href="{{url('dbase/show/')}}{{datalist.idDbase}}" class="btn btn-default extra-padding btn-sm" title="Configuracion avanzada"><i class="icon-cog"></i></a> #}
					</ul>
				</div>
			</div>
			
			{# {{ partial("partials/search_contacts_partial") }} #}
			<div class="row frame-bg-pd">
				<div class="col-md-3">
					<div class="dropdown">
						<button class="btn dropdown-toggle sr-only" type="button" id="dropdownMenu1" data-toggle="dropdown">
							<span class="caret">Marcar</span>
						</button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Todos</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Activos</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Suscritos</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Rebotados</a></li>
						</ul>
					</div>
				</div>

				<div class="col-md-3">
					<div class="dropdown">
						<button class="btn dropdown-toggle sr-only" type="button" id="dropdownMenu1" data-toggle="dropdown">
							<span class="caret">Acciones</span>
						</button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Todos</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Activos</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Suscritos</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Rebotados</a></li>
						</ul>
					</div>
				</div>

				<div class="col-md-3">
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
				</div>

				<div class="col-md-3">
					{#{{' {{ view App.DropDownSelect }} '}}#}
					
					{{'{{
					view Ember.Select
					content=filters
					optionValuePath="content.value"
					optionLabelPath="content.name"
					value=filter.value
					class="span6"
					}}'}}
				</div>
			</div>

			<div class="row">
				<div class="box-content">
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
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="contacts/new">
			<div class="row-fluid">
				{{ '{{#if App.errormessage }}' }}
					<div class="alert alert-message alert-error">
						{{ '{{ App.errormessage }}' }}
					</div>
				{{ '{{/if}} '}}
				<div class="row">
					<h4 class="sectiontitle">Crear nuevo contacto</h4>
					<div class="col-md-5">
						<form  class="form-horizontal" role="form">
							{{ '{{#if errors.errormsg}}' }}
								<div class="alert alert-error">
									{{ '{{errors.errormsg}}' }}
								</div>
							{{ '{{/if}}' }}
							{{' {{#if errors.email}} '}}
									<span class="text text-error">{{'{{errors.email}}'}}</span>
							{{' {{/if }} '}}
							<div class="form-group">
								<label for="Email" class="col-sm-2 control-label">*Email:</label>
								<div class="col-md-8">
									{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus" id="email" class="form-control" placeholder="Email"}}'}}
								</div>
							</div>
							<div class="form-group">
								<label for="nombre" class="col-sm-2 control-label">Nombre:</label>
								<div class="col-md-8">
									{{'{{view Ember.TextField valueBinding="name" id="name" class="form-control" placeholder="Nombre"}}'}}
								</div>
							</div>
							<div class="form-group">
								<label for="apellido" class="col-sm-2 control-label">Apellido:</label>
								<div class="col-md-8">
									{{'{{view Ember.TextField valueBinding="lastName" id="lastName" class="form-control" id="Apellido" placeholder="Apellido"}}'}}
								</div>
							</div>
							<!-- Campos Personalizados -->
							{%for field in fields%}
								<div class="form-group">
									<label for="campo{{field.idCustomField }}" class="col-sm-2 control-label">{{field.name}}:</label>
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
					<div class="col-md-7">
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
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/newbatch">
			<div class="row">
				<h4 class="sectiontitle">Crear varios contactos</h4>
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
									<button class="btn btn-sm btn-default btn-guardar extra-padding" {{'{{action "save" this}}'}}>Guardar</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-7">
					<div class="alert alert-success">
						<div class="row">
							<div class="col-sm-2">
								<span class="glyphicon glyphicon-info-sign"></span>
							</div>
							<div class="col-md-9">
								<p>Cree varios contactos a la vez.  Escriba en el cuadro de texto una línea de contenido por cada contacto que desee crear separando los campos por comas</p>
								<p><strong>Ejemplo:</strong></p>
								<dl>
									<dd>email1@email.com,Nombre1,Apellido1</dd>
									<dd>email2@otroemail.com,Nombre2</dd>
									<dd>email3@hotmail.com</dd>
								</dl>
								<p>No es necesario incluir todos los campos, el único <strong>campo requerido es "email"</strong>.</p>
								<p class="text-success">Cree máximo 30 contactos por este medio, si requiere crear más, diríjase a "Importación desde archico .csv".</p>							
							</div>
						</div>
					</div>
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/delete">
			<div class="row-fluid">
				<div class="box">
					<div class="box-header">
						<div class="title">
							Eliminar un contacto
						</div>
					</div>
					<div class="box-content padded">
						<p>Recuerde que si el contacto solo esta asociado a esta lista se eliminara por completo de la 
						Base de Datos</p>
						<p>¿Esta seguro que desea Eliminar el Contacto <strong>{{'{{name}}'}} ?</strong></p>
						{{ '{{#if errors.errormsg}}' }}
							<div class="alert alert-error">
								{{ '{{errors.errormsg}}' }}
							</div>
						{{ '{{/if}}' }}
						<button {{'{{action delete this}}'}} class="btn btn-danger">Eliminar</button>
						<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
					</div>
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="contacts/import">
			<div class="row">
				<h4 class="sectiontitle">Importar contactos desde archivo .csv a <span>Lista de Iván</span></h4>
				<div class="col-md-6">
					<div class="">
						<img src="images/file-choice.png" alt="" class="" />
					</div>
					<div class="">
						<span>1</span>
					</div>
					<div class="">
						<p>Seleccione el archivo .csv</p> 
					</div>
				</div>	
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
				<div class="span4">
					<div class="well relative span12">
						<div class="easy-pie-step span6"  data-percent="50"><span>1/2</span></div>
						<span class="triangle-button blue"><i class="icon-lightbulb"></i></span>
						<div class="span7"><strong>Primer paso: </strong><br />
						Seleccionar el archivo .csv que contiene los contactos
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					{{ flashSession.output() }}
					<div class="accordion-heading">
					  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
						Haga clic aqui para más información
					  </a>
					</div>
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
							<br>
							<div class="row-fluid">
								<div class="span6">
									<div class="box">
										<div class="box-header">
											<span class="title">Archivo .csv con cabecera</span>				
										</div>
										<div class="box-content">
											<table class="table table-normal">
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
								</div>
								<div class="span6">
									<div class="box">
										<div class="box-header">
											<span class="title">Archivo .csv sin cabecera</span>				
										</div>
										<div class="box-content">
											<table class="table table-normal">
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
									  </div>
									</div>
								</div>
							</div>
							<ul>
								<li>
									Si desea ingresar mas campos, y no desea poner cabecera puede separar por comas (,), punto 
									y coma (;), o barras (/),  cada uno de los campos, luego la aplicación se encargará de
									separarlos, eso si asegurese de cumplir los criterios, por ejemplo:
								</li>
							</ul>
							<div class="row-fluid">
								<div class="span6">
									<div class="box">
										<div class="box-header">
											<span class="title">Archivo .csv con cabecera</span>				
										</div>
										<div class="box-content">
											<table class="table table-normal">
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
									</div>
								</div>
								<div class="span6">
									<div class="box">
										<div class="box-header">
											<span class="title">Archivo .csv sin cabecera</span>				
										</div>
										<div class="box-content">
											<table class="table table-normal">
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
									  </div>
									</div>
								</div>
							</div>
							<p>
								Una vez que haya introducido todos los contactos en una tabla, guarde el documento y seleccione CSV (delimitado por comas) 
								(*.csv) como el tipo de archivo que desea guardar.
								Una vez que haya guardado el archivo, y este seguro de haber seguido los criterios anteriores puede pasar a importar sus contactos a la aplicación.
							</p>
						</div>
					</div>
				</div>
			</div>
			<br><br>
			<div class="row-fluid">
				<div class="span6">
					<form method="POST" action="{{url('contacts/import#/contacts')}}" enctype="multipart/form-data">
						<input name="importFile" type="file"><br /><br />
						<input type="hidden" name="idcontactlist" value={{datalist.idContactlist}}>
						<a href="{{url('contactlist/show/')}}{{datalist.idContactlist}}#/contacts" class="btn btn-default">Cancelar</a>
						{{submit_button('class': "btn btn-blue", "Cargar")}}
					</form>
				</div>
			</div>
		</script>

		<script type="text/x-handlebars" data-template-name="contacts/newimport">
			<div class="row-fluid">
				<div class="span8">
					<div class="row-fluid">
						<div class="span7">
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
					<div class="row-fluid">
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
	</div>
{% endblock %}
