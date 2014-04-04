<tr>
	<td>
		<label {{ '{{action "expand" this}}' }}>
		{#<label>#}
			{#{{ '{{#link-to "contacts.show" this}}' }}{{ '{{email}}' }}{{ '{{/link-to}}' }}#}
			{{ '{{email}}' }}
			{{' {{#if isEmailBlocked}} '}}
				<span class="badge badge-dark-red">Correo bloqueado</span>
			{{' {{/if }} '}}
			{{ '{{#if isBounced}}' }}
				<span class="badge badge badge-red">Rebotado</span>
			{{ '{{/if}}' }}	
			{{ '{{#if isSpam}}' }}
				<span class="badge badge badge-red">Reportado Spam:&nbsp</span>
			{{ '{{/if}}' }}
			{{' {{#if errors.email}} '}}
				<span class="text text-error">{{'{{errors.email}}'}}</span>
			{{' {{/if }} '}}
		</label>
	</td>
	<td>
		{{'{{name}}'}} {{'{{lastName}}'}}
	</td>
	<td>
		<div class="text-right">
	{{ '{{#if isSubscribed}}' }}
			<button class="btn btn-sm btn-default" {{' {{action "unsubscribedcontact" this}} '}}>Desuscribir</button>
	{{ '{{else}}' }}
		{{'{{#unless isEmailBlocked}}'}}
			<button class="btn btn-sm btn-info" {{' {{action "subscribedcontact" this}} '}}>Suscribir</button>
		{{'{{/unless}}'}}
	{{ '{{/if}}' }}
			{% if datasegment is not defined%}
				{{ '{{#link-to "contacts.delete" this disabledWhen="controller.deleteDisabled" class="btn btn-danger"}}' }}Eliminar{{ '{{/link-to}}' }}
			{% endif %}
		</div>
	</td>
</tr>
{{ '{{#if isExpanded}}' }}
<tr>
	<td colspan="3">
		<div class="row-fluid">
			<div class="span12">
				<h4>Información de contacto</h4>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span10 offset1">
				<div class="box">
					<div class="box-content">
						<table class="table table-condensed">
							<thead></thead>
							<tbody>
								<tr>
									<td>Dirección de correo: </td>
									<td>{{' {{ view App.EmberXEditableTextView value=email field="email" title="Editar correo del contacto" }} '}}</td>
{#									
									<td>{{' {{view Ember.TextField valueBinding="email" required="required" class="edit-contact-online"}} '}}</td>
#}
								</tr>
								<tr>
									<td>Nombre {#<a href="#/contacts" class="username" data-type="text" data-pk="1" data-url="/post" data-title="Enter username">superuser</a>#}</td>
{#
									<td>{{' {{view Ember.TextField valueBinding="name" required="required" class="edit-contact-online"}} '}}</td>
#}
									<td>{{' {{ view App.EmberXEditableTextView value=name field="name" title="Editar nombre de contacto" }} '}}</td>
								</tr>
								<tr>
									<td>Apellido</td>
									<td>{{' {{ view App.EmberXEditableTextView value=lastName field="lastName" title="Editar apellido de contacto" }} '}}</td>
{#
									<td>{{' {{view Ember.TextField valueBinding="lastName" required="required" class="edit-contact-online"}} '}}</td> 
#}
								</tr>
								{%for field in fields%}
								<tr>
									<td>{{field.name}}</td>
									<td>{{ember_customfield_xeditable(field)}}</td>
								</tr>
								{%endfor%}
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
							</tbody>
						</table>
					</div>
				</div>
			</div>	
			{#
			<div class="span6">
				<div class="box">
					<div class="box-content">
						<table class="table table-hover">
							<thead></thead>
							<tbody>
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
							</tbody>
						</table>
					</div>
				</div>
			</div>
			#}
		</div>
		{{ '{{#if App.isEditable}}' }}
		<div class="row-fluid">
			<div class="span10 offset1 text-right">
				<button class="btn btn-default" {{ '{{action "discard" this}}' }}>descartar<br />cambios</button>
				<button class="btn btn-green" {{ '{{action "edit" this}}' }}>guardar<br />cambios</button>
			</div>
		</div>
		{{ '{{/if}}' }}
		<div id="collapse-history-contact-{{'{{unbound id}}'}}" class="panel-collapse collapse">
			<div class="row-fluid">
				<div class="span10 offset1">
					<h4>Historial</h4>
					<div class="box">
						<div class="box-content padded">
					{{ '{{#if subscribedOn}}' }}
							<span class="text-blue-color">Suscrito:&nbsp</span>
							<span class="number-small">{{'{{subscribedOn}}'}}</span>&nbsp&nbsp&nbsp&nbsp&nbsp
					{{ '{{/if}}' }}

					{{ '{{#if ipSubscribed}}' }}
							<span class="text-blue-color">IP de Suscripcion:&nbsp</span>
							<span class="number-small">{{'{{ipSubscribed}}'}}</span>&nbsp&nbsp&nbsp&nbsp&nbsp
					{{ '{{/if}}' }}

					{{ '{{#if isActive}}' }}
							<span class="text-blue-color">Activado:&nbsp</span>
							<span class="number-small">{{'{{activatedOn}}'}}</span>&nbsp&nbsp&nbsp&nbsp&nbsp
					{{ '{{/if}}' }}

					{{ '{{#if ipActive}}' }}
							<span class="text-blue-color">IP de Activacion:&nbsp</span>
							<span class="number-small">{{'{{ipActive}}'}}</span>&nbsp&nbsp&nbsp&nbsp&nbsp
					{{ '{{/if}}' }}
						</div>	
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span10 offset1">
					<div class="box">
						<div class="box-content">
							<h5 class="padded">Últimas campañas</h5>
							<table class="table table-condensed">
								<thead></thead>
								<tbody>
									<tr>
										{{' {{#each mailHistory}} '}}
										<tr><td>{{' {{name}} '}}</td></tr>
										{{' {{else}} '}}
										<tr><td class="padded">Este contacto no tiene un historial de envíos</td></tr>
										{{' {{/each}} '}}
									</tr>
								</tbody>
							</table>
							<h5 class="padded">Últimos eventos</h5>
							<table class="table table-condensed">
							{{' {{#each mailHistory}} '}}
								<tbody>
									<!-- Historial de Aperturas -->
									{{' {{#if opening}} '}}
									<tr>
										<td class="contact-history-event-text">Apertura</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{opening}} '}}</td>
									</tr>
									{{ '{{/if}}' }}

									<!-- Historial de Clics -->
									{{' {{#if clicks}} '}}
									<tr>
										<td class="contact-history-event-text">Clic</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{clicks}} '}}</td>
									</tr>
									{{ '{{/if}}' }}

									<!-- Historial de Desuscripciones -->		
									{{' {{#if unsubscribe}} '}}
									<tr>
										<td class="contact-history-event-text">Desuscripcion</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{unsubscribe}} '}}</td>
									</tr>
									{{ '{{/if}}' }}

									<!-- Historial de Rebotes -->
									{{' {{#if bounced}} '}}
									<tr>
										<td class="contact-history-event-text">Rebote</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{bounced}} '}}</td>
									</tr>
									{{ '{{/if}}' }}

									<!-- Historial de Spam -->
									{{' {{#if spam}} '}}
									<tr>
										<td class="contact-history-event-text">Spam</td>
										<td>{{' {{name}} '}}</td>
										<td class="contact-history-event-text">{{' {{spam}} '}}</td>
									</tr>
									{{ '{{/if}}' }}
								</tbody>
							{{' {{else}} '}}
								</tbody>
									<tr>
										<td colspan="3" class="padded">Este contacto no tiene un historial de eventos</td>
								   </tr>
								</tbody>
							{{' {{/each}} '}}
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12 text-center">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse-history-contact-{{'{{unbound id}}'}}">
					<span {{ '{{action "collapse" this}}' }}>- Colapsar</span>
					<span onclick="collapse_contact({{'{{unbound id}}'}});" class="collapse-link-{{'{{unbound id}}'}}">+ Ver Historial</span>
				</a>
			</div>
		</div>
	</td>
</tr>
{{ '{{/if}}' }}

{#
<div class="box-section news with-icons relative">
		{{'{{#if isSpam}}'}}
		<span class="triangle-button red">
			<i class="icon-warning-sign"></i>
		</span>
		{{'{{/if}}'}}
	<div {{'{{bind-attr class=":avatar isReallyActive:green:blue"}}'}}>
		<i class="icon-user icon-2x"></i>
	</div>
	<div class="news-content">
		<div class="pull-right">
			<div class="btn-group">
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li>{{ '{{#link-to "contacts.edit" this disabledWhen="controller.updateDisabled"}}' }}<i class="icon-pencil"></i> Editar{{ '{{/link-to}}' }}</li>
					<li>{{ '{{#link-to "contacts.show" this}}' }}<i class="icon-search"></i> Ver detalles{{ '{{/link-to}}' }}</li>
					<li>{{ '{{#link-to "contacts.delete" this disabledWhen="controller.deleteDisabled"}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/link-to}}' }}</li>
				</ul>
			</div>
		</div>
		<div class="news-title">{{ '{{#link-to "contacts.show" this}}{{email}}{{/link-to}}' }}</div>
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
#}