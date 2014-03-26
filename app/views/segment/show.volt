{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ partial("partials/date_view_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}
		{{ javascript_include('js/mixin_config.js') }}
		{{ javascript_include('js/load_activecontacts.js')}}
<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1Url() ~ '/segment/' ~ datasegment.idSegment}}';

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
{% block sectiontitle %}Segmento: <strong>{{datasegment.name}}</strong>{% endblock %}

{%block sectionsubtitle %}{{datasegment.description}}{% endblock %}	
	
{% block content %}
<div id="emberAppContactContainer">
	<script type="text/x-handlebars" data-template-name="contacts">
		{{'{{outlet}}'}}
	</script>
	<script type="text/x-handlebars" data-template-name="contacts/index">
		<div class="pull-right" style="margin-bottom: 5px;">
			<a href="{{url('contactlist#/segments')}}" class="btn btn-blue"><i class="icon-home"></i> Todos los segmentos</a>
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
			<div class="box-section news with-icons relative">
				{{'{{#if isSpam}}'}}
				<span class="triangle-button red">
					<i class="icon-warning-sign"></i>
				</span>
				{{'{{/if}}'}}
				<div {{'{{bindAttr class=":avatar isReallyActive:green:blue"}}'}}>
					<i class="icon-user icon-2x"></i>
				</div>
				<div class="news-content">
					<div class="pull-right">
						<div class="btn-group">
							<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li>{{ '{{#linkTo "contacts.edit" this disabledWhen="controller.updateDisabled"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/linkTo}}' }}</li>
								<li>{{ '{{#linkTo "contacts.show" this}}' }}<i class="icon-search"></i> Ver detalles{{ '{{/linkTo}}' }}</li>
							</ul>
						</div>
					</div>
					<div class="news-title">{{ '{{#linkTo "contacts.show" this}}{{email}}{{/linkTo}}' }}</div>
					{{ '{{#if isEmailBlocked}}' }}
					<span class="badge badge-dark-red">Correo bloqueado</span>
					{{ '{{/if}}' }}
					{{ '{{#if isSpam}}' }}
					<span class="badge badge-dark-red">Spam</span>
					{{ '{{/if}}' }}
					{{ '{{#if isBounced}}' }}
					<span class="badge badge-red">Rebotado</span>
					{{ '{{/if}}' }}
					{{ '{{#unless isSubscribed}}' }}
					<span class="badge badge-gray">Desuscrito</span>
					{{ '{{/unless}}' }}
					{{ '{{#unless isActive}}' }}
					<span class="badge badge-blue">Sin confirmar</span>	
					{{ '{{/unless}}' }}
					<div class="news-text">
						{{'{{name}}'}}<br/>
						{{'{{lastName}}'}}
					</div>
				</div>
			</div>
			{{ '{{else}}' }}
				<div class="padded">
					No existen coincidencias para el criterio de busqueda.
				</div>
			{{ '{{/each}}' }}
			</div>
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
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
		<div class="span12">
			<div class="full-contact-information clearfix">
				<div class="contact-information">
					<div class="box">
						<div class="box-header">
							<div class="title">
								Informacion de Contacto
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

											<span class="orange-label">Desuscrito</span>
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
								<button class="btn btn-sm btn-info" {{' {{action unsubscribedcontact this}} '}}>Desuscribir</button>
							{{ '{{else}}' }}
								{{'{{#unless isEmailBlocked}}'}}
									<button class="btn btn-sm btn-info" {{' {{action subscribedcontact this}} '}}>Suscribir</button>
								{{'{{/unless}}'}}
							{{ '{{/if}}' }}
						{{ '{{#linkTo "contacts.edit" this}}<button class="btn btn-sm btn-info">Editar</button>{{/linkTo}}' }}
						{{ '{{#linkTo "contacts"}}<button class="btn btn-default">Regresar</button>{{/linkTo}}' }}
						</div>
					</div>
				</div>
				<div class="contact-details">
					<div class="box">
						<div class="box-header">
							<div class="title">
								Detalles de Contacto
							</div>
						</div>
						<div class="box-content">
							<table class="table table-normal">
								{{ '{{#if subscribedOn}}' }}
									<tr>
										<td><span class="text-green-color">Suscrito:&nbsp</span></td>
										<td><span class="number-small">{{'{{subscribedOn}}'}}</span></td>
									</tr>
								{{ '{{/if}}' }}
								{{ '{{#if ipSubscribed}}' }}
									<tr>
										<td><span class="text-green-color">IP de Suscripcion:&nbsp</span></td>
										<td><span class="number-small">{{'{{ipSubscribed}}'}}</span></td>
									</tr>
								{{ '{{/if}}' }}
								{{ '{{#if isActive}}' }}
									<tr>
										<td><span class="text-blue-color">Activado:&nbsp</span></td>
										<td><span class="number-small">{{'{{activatedOn}}'}}</span></td>
									</tr>
								{{ '{{/if}}' }}
								{{ '{{#if ipActive}}' }}
									<tr>
										<td><span class="text-blue-color">IP de Activacion:&nbsp</span></td>
										<td><span class="number-small">{{'{{ipActive}}'}}</span></td>
									</tr>
								{{ '{{/if}}' }}
								{{ '{{#if isBounced}}' }}
									<tr>
										<td><span class="text-brown-color">Rebotado:&nbsp</span></td>
										<td><span class="number-small">{{'{{bouncedOn}}'}}</span></td>
									</tr>
								{{ '{{/if}}' }}
								{{ '{{#if isSpam}}' }}
									<tr>
										<td><span class="text-red-color">Reportado Spam:&nbsp</span></td>
										<td><span class="number-small">{{'{{spamOn}}'}}</span></td>
									<tr>
								{{ '{{/if}}' }}
								{{ '{{#if unsubscribedOn}}' }}
									<tr>
										<td><span class="text-gray-color"><strong>Desuscrito:&nbsp</strong></span></td>
										<td><span class="number-small">{{'{{unsubscribedOn}}'}}</span></td>
									</tr>
								{{ '{{/if}}' }}
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span12 contact-history-span">
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
							<br />
							<div class="news-text">
								<table class="table table-normal table-for-contact-mails">
								{{' {{#each history}} '}}
									<tr><td>{{' {{name}} '}}</td></tr>
								{{' {{/each}} '}}
								</table>
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
							<br />
							<div class="news-text">
							<!-- Historial de Aperturas -->
								<table class="table table-normal table-for-contact-events">
								<thead>
									<tr>
										<td class="contact-event-type">Evento</td>
										<td>Correo</td>
										<td class="contact-event-date">Fecha</td>
								   </tr>
								</thead>
								<tbody>
								{{' {{#each history}} '}}
									{{' {{#if opening}} '}}
									<tr>
										<td class="contact-history-event-text">Apertura</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{opening}} '}}</td>
									</tr>
									{{ '{{/if}}' }}
								{{' {{/each}} '}}

								<!-- Historial de Clics -->
								{{' {{#each history}} '}}
									{{' {{#if clicks}} '}}
									<tr>
										<td class="contact-history-event-text">Clic</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{clicks}} '}}</td>
									</tr>
									{{ '{{/if}}' }}
								{{' {{/each}} '}}

								<!-- Historial de Desuscripciones -->
								{{' {{#each history}} '}}
									{{' {{#if unsubscribe}} '}}
									<tr>
										<td class="contact-history-event-text">Desuscripcion</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{unsubscribe}} '}}</td>
									</tr>
									{{ '{{/if}}' }}
								{{' {{/each}} '}}

								<!-- Historial de Rebotes -->
								{{' {{#each history}} '}}
									{{' {{#if bounced}} '}}
									<tr>
										<td class="contact-history-event-text">Rebote</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{bounced}} '}}</td>
									</tr>
									{{ '{{/if}}' }}
								{{' {{/each}} '}}

								<!-- Historial de Spam -->
								{{' {{#each history}} '}}
									{{' {{#if spam}} '}}
									<tr>
										<td class="contact-history-event-text">Spam</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{spam}} '}}</td>
									</tr>
									{{ '{{/if}}' }}
								{{' {{/each}} '}}
								</tbody>
								</table>
							</div>
						</div>
					 </div>
				</div>
			</div>
		</div>
	</div>
	</script>
	<script type="text/x-handlebars" data-template-name="contacts/edit">
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
						<button class="btn btn-blue" {{' {{action edit this}} '}}>Editar</button>
						<button class="btn btn-deafult" {{ '{{action cancel this}}' }}>Cancelar</button>
					</form>
				</div>
			</div>
		</div>
	</script>
</div>
{% endblock%}