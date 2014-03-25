<script type="text/x-handlebars" data-template-name="contacts/index">
	<div class="row-fluid">
		<div class="span8">
			<div class="box">
				<div class="box-content">
					<div class="box-section news with-icons">
						<div class="avatar green">
							<i class="icon-user icon-2x"></i>
						</div>
						<div class="news-content">
							<div class="news-title">
								Contactos
							</div>
							<div class="news-text">
								Aqui esta toda la información necesaria para gestionar los datos de tus contactos, recuerda que al editar, 
								eliminar o desuscribir un contacto, estos cambios se aplicaran a nivel de todas las listas con las que este
								relacionado
								ese contacto, que a su vez estan relacionadas con esta base de datos. 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span4">
			<ul class="inline pull-right sparkline-box">
				<li class="sparkline-row">
					<h4 class="blue"><span>Contactos Totales</span>{{sdbase.Ctotal|numberf}}</h4>
				</li>

				<li class="sparkline-row">
					<h4 class="green"><span>Activos</span>{{ sdbase.Cactive|numberf }}</h4>
				</li>

				<li class="sparkline-row">
					<h4 class="gray"><span>Inactivos</span>{{ get_inactive(sdbase)|numberf }}</h4>
				</li>
			</ul>
			<ul class="inline pull-right sparkline-box">
				<li class="sparkline-row">
					<h4 class="orange"><span>Desuscritos</span>{{ sdbase.Cunsubscribed|numberf }}</h4>
				</li>

				<li class="sparkline-row">
					<h4 class="red"><span>Rebotados</span>{{sdbase.Cbounced|numberf }}</h4>
				</li>

				<li class="sparkline-row">
					<h4 class="red"><span>Spam</span>{{sdbase.Cspam|numberf }}</h4>
				</li>
			</ul>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8">
			{{ partial("partials/search_contacts_partial") }}
		</div>
	</div>
	<div class="row-fluid">
        <div class="span12">
			<div class="box">
				<div class="box-content">
						<table class="table table-normal">
							<thead>
								 <tr>
									<td colspan="3">E-mail</td>
									<td>Acciones</td>
								</tr>
							</thead>
							<tbody>
							{{'{{#each model}}'}}
								{{ partial("partials/contact_view_partial") }}
							{{ '{{else}}' }}
								<div class="box-section news with-icons">
									<div class="avatar green">
										<i class="icon-lightbulb icon-2x"></i>
									</div>
									<div class="news-content">
										<div class="news-title">
											No hay contactos en esta base de datos
										</div>
										<div class="news-text">
											<p>
												Para empezar a administrar contactos, puede crear una lista de contactos en la base de datos,
												y asociar contactos a la misma.
											</p>
										</div>
									</div>
								</div>
							{{'{{/each}}'}}
						</tbody>
					 </table>
				</div>
				<div class="box-footer">
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
			
        </div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="contacts">
	{{'{{outlet}}'}}
</script>
{#
<script type="text/x-handlebars" data-template-name="contacts/edit">	
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
			{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}
	<div class="row-fluid">
		<div class="span4">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Agrega un nuevo contacto, con sus datos más básicos
					</div>
				</div>
				<div class="box-content padded">
					<form>
						{{ '{{#if errors.errormsg}}' }}
							<div class="alert alert-error">
								{{ '{{errors.errormsg}}' }}
							</div>
						{{ '{{/if}}' }}
						<label>E-mail: </label>
						{{' {{view Ember.TextField valueBinding="email" placeholder="E-mail" id="email" required="required" autofocus="autofocus"}} '}}

						<label>Nombre: </label>
						{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" }} '}}

						<label>Apellido: </label>
						{{' {{view Ember.TextField valueBinding="lastName" placeholder="Apellido" id="lastName" required="required"}} '}}

						<label>Estado: </label>
						{{ '{{#if isSubscribed}}' }}
							{{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed"}} '}}  Suscrito
						{{ '{{else}}' }}
							{{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed"}} '}}  Suscrito
						{{ '{{/if}}' }}
						<br /><br />
						<!-- Campos Personalizados -->
						{%for field in fields%}
							<label>{{field.name}}:</label>
							{{ember_customfield(field)}}
							{% if (field.type == "Text" and field.maxLength != "") %}
								Maximo {{field.maxLength}} caracteres
							{% elseif field.type == "Numerical" and field.minValue != "" and field.maxValue != 0 %}
								El valor debe estar entre {{field.minValue}} y {{field.maxValue}} numeros
							{%endif%}
						{%endfor%}
						<!--  Fin de campos personalizados -->
						<br />
						<button class="btn btn-blue" {{' {{action edit this}} '}}>Editar</button>
						<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
#}
<script type="text/x-handlebars" data-template-name="contacts/delete">
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Eliminar un contacto
					</div>
				</div>
				<div class="box-content padded">
					<p>Si elimina un contacto, se eliminará de todas las listas y de Bases de datos a las que pertenece</p>
					<p>¿Esta seguro que desea eliminar el Contacto <strong>{{'{{email}}'}}</strong>?</p>
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					{{ '{{#if App.errormessage }}' }}
						<div class="alert alert-message alert-error">
							{{ '{{ App.errormessage }}' }}
						</div>
					{{ '{{/if}} '}}	
					<button {{'{{action delete this}}'}} class="btn btn-danger">Eliminar</button>
					<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</script>
{#
<script type="text/x-handlebars" data-template-name="contacts/show">
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
						{{ '{{#link-to "contacts.edit" this}}<button class="btn btn-sm btn-info">Editar</button>{{/link-to}}' }}
						{{ '{{#link-to "contacts"}}<button class="btn btn-default">Regresar</button>{{/link-to}}' }}
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
#}