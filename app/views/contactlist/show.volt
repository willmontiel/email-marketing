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
			}.property('isSubscribed,isActive')

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
{% endblock %}

{% block sectiontitle %}Lista: <strong>{{datalist.name}}</strong>{% endblock %}

{%block sectionsubtitle %}{{datalist.description}}{% endblock %}
{% block sectionContactLimit %}
	{{ partial("partials/contactlimitinfo_partial") }}
{%endblock%}	
{% block content %}
	<script type="text/x-handlebars" >
		{{' {{#if errors.email}} '}}
			<span class="text text-error">{{'{{errors.email}}'}}</span>
		{{' {{/if }} '}}
		{{'{{outlet}}'}}
	</script>
	<div id="emberAppContactContainer">
		<script type="text/x-handlebars" data-template-name="contacts">
			<div class="padded">
				<div class="clearfix">
					<ul class="inline sparkline-box" style="">

						<li class="sparkline-row">
							<h4 class="blue"><span>Contactos totales</span> {{'{{lista.totalContactsF}}'}}</h4>
						</li>

						<li class="sparkline-row">
							<h4 class="green"><span>Activos</span> {{'{{lista.activeContactsF}}'}}</h4>
						</li>

						<li class="sparkline-row">
							<h4 class="gray"><span>Inactivos</span> {{'{{lista.inactiveContactsF}}'}}</h4>
						</li>
						<li class="sparkline-row">
							<h4 class="gray"><span>Desuscritos</span> {{'{{lista.unsubscribedContactsF}}'}}</h4>
						</li>
						<li class="sparkline-row">
							<h4 class="red"><span>Rebotados</span> {{'{{lista.bouncedContactsF}}'}}</h4>
						</li>
						<li class="sparkline-row">
							<h4 class="red"><span>Spam</span> {{'{{lista.spamContactsF}}'}}</h4>
						</li>

					</ul>
				</div>
			</div>
			{{'{{outlet}}'}}
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/index">
			<div class="pull-right" style="margin-bottom: 5px;">
				<a href="{{url('contactlist#/lists')}}" class="btn btn-blue"><i class="icon-home"></i> Todas las listas</a>
				{{'{{#link-to "contacts.new" class="btn btn-default" disabledWhen="createDisabled"}}'}}<i class="icon-plus"></i> Crear Contacto{{'{{/link-to}}'}}
				{{'{{#link-to "contacts.newbatch" class="btn btn-default" disabledWhen="importBatchDisabled"}}'}}<i class="icon-align-justify"></i> Crear Varios Contactos{{'{{/link-to}}'}}
				{{ '{{#link-to "contacts.import" class="btn btn-default" disabledWhen="importDisabled"}}'}}<i class="icon-file-alt"></i> Importar Contactos{{'{{/link-to}}'}}			
				<a href="{{url('dbase/show/')}}{{datalist.idDbase}}" class="btn btn-default" title="Configuracion avanzada"><i class="icon-cog"></i></a>
			</div>
			<div class="clearfix"></div>
			<br />
			
			{{ partial("partials/search_contacts_partial") }}
			
			<div class="box">
				<div class="box-header">
					<span class="title">Contactos</span>
 					<ul class="box-toolbar">
						<li><span class="label label-green">{{'{{totalrecords}}'}}</span></li>
					</ul>
				</div>
				<div class="box-content">
					<table class="table table-bordered" style="border: 0px !important;">
						<thead></thead>
						<tbody>
							{{'{{#each model}}'}}
								{{ partial("partials/contact_view_partial") }}
							{{ '{{else}}' }}
								<tr>
									<td>
										No tiene contactos en esta lista, para crearlos haga click en "crear contacto", para crear
										un solo contacto, "crear varios contactos", para crear hasta 30 contactos e "importar contactos",
										para importar un archivo .csv con hasta 100000 contactos.
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
				<div class="box">
					<div class="box-content">
						<div class="box-section news with-icons">
							<div class="avatar green">
								<i class="icon-lightbulb icon-2x"></i>
							</div>
							<div class="news-content">
								<div class="news-title">
									Crear un nuevo contacto
								</div>
								<div class="news-text">
									Cree un nuevo contacto, basta con una dirección de correo electrónico y si desea otros
									datos báscicos como nombre y apellido.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			{{ '{{#if App.errormessage }}' }}
				<div class="alert alert-message alert-error">
					{{ '{{ App.errormessage }}' }}
				</div>
			{{ '{{/if}} '}}
			<div class="box span4">
				<div class="box-header"><span class="title">Crear nuevo contacto</strong></span></div>
				<div class="box-content">
					<form>
						<div class="padded">
							{{ '{{#if errors.errormsg}}' }}
								<div class="alert alert-error">
									{{ '{{errors.errormsg}}' }}
								</div>
							{{ '{{/if}}' }}
							{{' {{#if errors.email}} '}}
									<span class="text text-error">{{'{{errors.email}}'}}</span>
							{{' {{/if }} '}}
							<label>*E-mail:</label>
							{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus" id="email"}}'}}
							
							<label>Nombre:</label>
							{{'{{view Ember.TextField valueBinding="name" id="name"}}'}}
								
							<label>Apellido:</label>
							{{'{{view Ember.TextField valueBinding="lastName" id="lastName"}}'}}
								
							<!-- Campos Personalizados -->
							{%for field in fields%}
								<label for="campo{{field.idCustomField }}">{{field.name}}:</label>
								{{ember_customfield(field)}}
							{%endfor%}
							<!--  Fin de campos personalizados -->
						</div>
						<div class="form-actions">
							<button class="btn btn-default" {{'{{action "cancel" this}}'}}>Cancelar</button>
							<button class="btn btn-blue" {{'{{action "save" this}}'}}>Guardar</button>
{#
							<button  data-loading-text="saving..." {{'{{bindAttr class=":btn :btn-blue isSaving:loading"}}'}} {{'{bindAttr disabled="isSaving"}}'}} {{'{{action "save" this}}'}}>Guardar</button>
#}
						</div>
					</form>
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/newbatch">
			<div class="box span8">
				<div class="box-header"><span class="title">Crear multiples Contactos</span></div>
				<form method="post" action="{{url('contacts/newbatch')}}/{{datalist.idContactlist}}">
					<div class="box-content padded">
						<div class="alert-info padded">
							<p>A través de esta función puede crear varios contactos al mismo tiempo.</p>
							<p>Simplemente escriba en el cuadro de texto una línea de contenido por cada contacto que desee crear separando los campos por comas.</p>
							<p><strong>Ejemplo:</strong></p>
							<dl>
								<dd>email1@email.com,Nombre1,Apellido1</dd>
								<dd>email2@otroemail.com,Nombre2</dd>
								<dd>email3@hotmail.com</dd>
							</dl>
							<p>Note que no es necesario incluir todos los campos, el único <strong>campo requerido es "email"</strong>.</p>
							<p class="text-success">El sistema validará los registros repetidos y los correos inválidos.</p>
							<p class="text-success">Recuerde que solo puede crear máximo 30 contactos por este medio, si requiere crear más, diríjase a "Importación desde archico .csv".</p>
						</div>
						<br/>
						<label>
							Información de los contactos
						</label>
						{{ text_area("arraybatch", '', 'cols': '40', 'rows': '6', 'class': 'span10') }}
					</div>
					<div class="box-footer flat padded">
						{{ '{{#link-to "contacts"}}<button class="btn btn-sm btn-default">Cancelar</button>{{/link-to}}' }}
						<input class="btn btn-blue" type="submit" value="Continuar">
					</div>
				</form>
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
			<div class="row-fluid">
				<div class="span12">
					<h3 class="title">
					Importar contactos desde archivo .csv
					</h3>
				</div>
			</div>
			<div class="row-fluid">
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
