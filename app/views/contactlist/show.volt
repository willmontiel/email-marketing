{% extends "templates/index_new.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ partial("partials/date_view_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}
		{{ javascript_include('js/mixin_config.js') }}
		{{ javascript_include('js/load_activecontacts.js')}}
<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url ~ '/contactlist/' ~ datalist.idContactlist}}';
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
		{%endfor%}
	</script>
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
	
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppContactContainer">
		{# {{ router.getRewriteUri()}} #}
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
					<h4 class="gray"><span>Des-suscritos</span> {{'{{lista.unsubscribedContactsF}}'}}</h4>
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
				{{'{{#linkTo "contacts.new" class="btn btn-default" disabledWhen="createDisabled"}}'}}<i class="icon-plus"></i> Crear Contacto{{'{{/linkTo}}'}}
				{{'{{#linkTo "contacts.newbatch" class="btn btn-default" disabledWhen="importBatchDisabled"}}'}}<i class="icon-align-justify"></i> Crear Varios Contactos{{'{{/linkTo}}'}}
				{{ '{{#linkTo "contacts.import" class="btn btn-default" disabledWhen="importDisabled"}}'}}<i class="icon-file-alt"></i> Importar Contactos{{'{{/linkTo}}'}}			
			</div>
			<div class="clearfix"></div>

			<div class="box">
				<div class="box-header">
					<span class="title">Contactos</span>
 					<ul class="box-toolbar">
						<li><span class="label label-green">{{'{{totalrecords}}'}}</span></li>
					</ul>
				</div>
				<div class="box-content">
				{{'{{#each model}}'}}
					{{ partial("partials/contact_view_partial") }}
				{{ '{{else}}' }}
					<div class="padded">
						No tiene contactos en esta lista, para crearlos haga click en "crear contacto", para crear
						un solo contacto, "crear varios contactos", para crear hasta 30 contactos e "importar contactos",
						para importar un archivo .csv con hasta 100000 contactos.
					</div>
				{{ '{{/each}}' }}
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="contacts/new">
			{{ '{{#if App.errormessage }}' }}
				<div class="alert alert-message alert-error">
				<h4>Error!</h4>
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
							<label>*E-mail:
								{{' {{#if errors.email}} '}}
									<span class="text text-error">{{'{{errors.email}}'}}</span>
								{{' {{/if }} '}}
							</label>
					
							{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus"}}'}}
							<label>Nombre:</label>
							{{'{{view Ember.TextField valueBinding="name"}}'}}
								
							<label>Apellido:</label>
							{{'{{view Ember.TextField valueBinding="lastName"}}'}}
								
							<!-- Campos Personalizados -->
							{%for field in fields%}
								<label for="campo{{field.idCustomField }}">{{field.name}}:</label>
								{{ember_customfield(field)}}
								{% if (field.type == "Text" and field.maxLength != "") %}
									Maximo {{field.maxLength}} caracteres
								{% elseif field.type == "Numerical" and field.minValue != "" and field.maxValue != 0 %}
									El valor debe estar entre {{field.minValue}} y {{field.maxValue}} numeros
								{%endif%}
							{%endfor%}
							<!--  Fin de campos personalizados -->
							<br>
						</div>
						<div class="form-actions">
							<button  data-loading-text="saving..." {{'{{bindAttr class=":btn :btn-blue isSaving:loading"}}'}} {{'{{bindAttr disabled="isSaving"}}'}} {{'{{action save this}}'}}>Guardar</button>
							<button class="btn btn-default" {{'{{action cancel this}}'}}>Cancelar</button>
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
							<p>A través de esta función puede varios contactos al mismo tiempo.</p>
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
						<input class="btn btn-blue" type="submit" value="Continuar">
						{{ '{{#linkTo "contacts"}}<button class="btn btn-sm btn-default">Cancelar</button>{{/linkTo}}' }}
					</div>
				</form>
			</div>
		</script>
	<script type="text/x-handlebars" data-template-name="contacts/edit">
		<div class="row-fluid">
			<div class="box">
				<div class="box-content">
					<div class="box-section news with-icons">
						<div class="avatar blue">
							<i class="icon-lightbulb icon-2x"></i>
						</div>
						<div class="news-content">
							<div class="news-title">
								Editar el contacto {{ '{{email}}' }}
							</div>
							<div class="news-text">
								Aqui podrá editar/actualizar la información un contacto,  como nombre, apellido o simplemente
								des-suscribirlo, recuerde que al editar cualquier dato, esto se actualizará a nivel de base de datos
								sin importar en que lista este.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{{ '{{#if App.errormessage }}' }}
			<div class="alert alert-message alert-error">
			<h4>Error!</h4>
			{{ '{{ App.errormessage }}' }}
			</div>
		{{ '{{/if}} '}}
		<div class="row-fluid">
			<div class="box span3">
				<div class="box-header">
					<div class="title">
						Editar un contacto
					</div>
				</div>
				<div class="box-content padded">
					<form>
						{{ '{{#if errors.errormsg}}' }}
							<div class="alert alert-error">
								{{ '{{errors.errormsg}}' }}
							</div>
						{{ '{{/if}}' }}
						<label>E-mail</label>
						{{' {{#if errors.email}} '}}
							<span class="text text-error">{{'{{errors.email}}'}}</span>
						{{' {{/if }} '}}
						{{' {{view Ember.TextField valueBinding="email" placeholder="E-mail" id="email" required="required" autofocus="autofocus"}} '}}
						<label>Nombre: </label>
						{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" }} '}}
						<label>Apellido: </label>
						{{' {{view Ember.TextField valueBinding="lastName" placeholder="Apellido" id="lastName" required="required"}} '}}
						<label>Estado: </label>
						{{ '{{#if isSubscribed}}' }}
							{{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed"}} '}}  Suscrito<br />
						{{ '{{else}}' }}
							{{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed" disabledBinding="isEmailBlocked"}} '}}  Suscrito<br />
						{{ '{{/if}}' }}
						<br />
						<!-- Campos Personalizados -->
						{%for field in fields%}
							<p><label for="{{field.name}}">{{field.name}}:</label></p>
							<p>{{ember_customfield(field)}}</p>
							{% if (field.type == "Text") %}
								Maximo {{field.maxLength}} caracteres
							{% elseif field.type == "Numerical" %}
								El valor debe estar entre {{field.minValue}} y {{field.maxValue}} numeros
							{%endif%}
						{%endfor%}
						<!--  Fin de campos personalizados -->
						<br />
						<button class="btn btn-deafult" {{ '{{action cancel this}}' }}>Cancelar</button>
						<button class="btn btn-blue" {{' {{action edit this}} '}}>Grabar</button>
					</form>
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
<script type="text/x-handlebars" data-template-name="contacts/show">
	<br />
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar blue">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Información detallada de contacto
						</div>
						<div class="news-text">
							Aqui podrá ver en detalle los datos de cada contacto, como cuando fue activado o suscrito, 
							información sobre campañas que ha recibido y mucho más. Tambien podrá desuscribirlo o suscribirlo,
							y editar la mayoría de los datos.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6"> 
			<div class="box">
				<div class="box-header">
					<div class="title">
						Detalles de Contacto
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<tr>
							<td>Email:</td>
							<td>{{'{{email}}'}}
								{{' {{#if isEmailBlocked}} '}}
									<span class="badge badge-dark-red">Correo bloqueado</span>
								{{' {{/if }} '}}
								{{' {{#if errors.email}} '}}
									<span class="text text-error">{{'{{errors.email}}'}}</span>
								{{' {{/if }} '}}
							</td>
						</tr>
						<tr>
							<td>Nombre:</td>
							<td>{{'{{name}}'}}</td>
						</tr>
						<tr>
							<td>Apellido:</td>
							<td>{{'{{lastName}}'}}</td>
						</tr>
						<tr>
							<td>
								{{ '{{#if isActive}}' }}
									<span class="green-label">Activo</span>
								{{ '{{else}}' }}
									<span class="orange-label">Inactivo</span>
								{{ '{{/if}}' }}
							</td>
							<td>
								{{ '{{#if isSubscribed}}' }}

									<span class="green-label">Suscrito</span>
								{{ '{{else}}' }}

									<span class="orange-label">Des-Suscrito</span>
								{{ '{{/if}}' }}
							</td>
						</tr>
					{%for field in fields%}
						<tr>
							<td>{{field.name}}</td>
								<td>{{'{{campo'~field.idCustomField~'}}'}}</td>
						</tr>
					{%endfor%}
					</table>
				</div>
				<div class="box-footer padded">
					{{ '{{#if isSubscribed}}' }}
					<button class="btn btn-sm btn-info" {{' {{action unsubscribedcontact this}} '}}>Des-suscribir</button>
				{{ '{{else}}' }}
					{{'{{#unless isEmailBlocked}}'}}
					<button class="btn btn-sm btn-info" {{' {{action subscribedcontact this}} '}}>Suscribir</button>
					{{'{{/unless}}'}}
				{{ '{{/if}}' }}

				{{ '{{#linkTo "contacts.edit" this}}<button class="btn btn-sm btn-info">Editar</button>{{/linkTo}}' }}
				{{ '{{#linkTo "contacts"}}<button class="btn btn-sm btn-inverse">Regresar</button>{{/linkTo}}' }}
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Historial
					</div>
				</div>
				<div class="box-content padded">
					<div class="box-section news with-icons">
						<div class="avatar green">
							<i class="icon-globe icon-2x"></i>
						</div>
							<div class="news-time">	
							</div>
						<div class="news-content">
							<div class="news-title">
								Ultimas Campañas
							</div>
							<div class="news-text">
								----------------------------------
							</div>
						</div>
					 </div>

					 <div class="box-section news with-icons">
						<div class="avatar green">
							<i class="icon-lightbulb icon-2x"></i>
						</div>
							<div class="news-time">	
							</div>
						<div class="news-content">
							<div class="news-title">
								Ultimos Eventos
							</div>
							<div class="news-text">
								----------------------------------
							</div>
						</div>
					 </div>

					 <div class="box-section news with-icons">
						<div class="avatar blue">
							<i class="icon-info-sign icon-2x"></i>
						</div>
						<div class="news-time">	
						</div>
						<div class="news-content">
							<div class="news-title">
								Información de Contacto
							</div>
							<div class="news-text">
								{{ '{{#if subscribedOn}}' }}
									<span class="text-green-color">Suscrito:&nbsp</span> 
									<span class="number-small">{{'{{subscribedOn}}'}}</span>
									<br />
									<span class="text-green-color">IP de Suscripcion:&nbsp</span>
									<span class="number-small">{{'{{ipSubscribed}}'}}</span>
								{{ '{{/if}}' }}
								<br />
								{{ '{{#if isActive}}' }}
									<span class="text-blue-color">Activado:&nbsp</span>
									<span class="number-small">{{'{{activatedOn}}'}}</span>
									<br />
									<span class="text-blue-color">IP de Activacion:&nbsp</span> 
									<span class="number-small">{{'{{ipActive}}'}}</span>
								{{ '{{/if}}' }}
								<br />
								{{ '{{#if isBounced}}' }}
									<span class="text-brown-color">Rebotado:&nbsp</span>
									<span class="number-small">{{'{{bouncedOn}}'}}</span>
									<br />
								{{ '{{/if}}' }}

								{{ '{{#if isSpam}}' }}
									<span class="text-red-color">Reportado Spam:&nbsp</span>
									<span class="number-small">{{'{{spamOn}}'}}</span>
									<br />
								{{ '{{/if}}' }}

								<span class="text-gray-color">Des-suscrito:&nbsp</span>
								<span class="number-small">{{'{{unsubscribedOn}}'}}</span>
							</div>
						</div>
					 </div>
				</div>
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
				<input name="importFile" type="file"><br>
				<input type="hidden" name="idcontactlist" value={{datalist.idContactlist}}>
				{{submit_button('class': "btn btn-blue", "Cargar")}}
				<a href="{{url('contactlist/show/')}}{{datalist.idContactlist}}#/contacts" class="btn btn-default">Cancelar</a>
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
