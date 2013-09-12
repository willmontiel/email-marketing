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
								eliminar o des-suscribir un contacto, estos cambios se aplicaran a nivel de todas las listas con las que este
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
					<h4 class="orange"><span>Des-suscritos</span>{{ sdbase.Cunsubscribed|numberf }}</h4>
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
		<div class="span3">
			<form>
				<p>
					<label class="input-with-submit">
						{{' {{view Ember.TextField valueBinding="searchText" type="text" placeholder="Buscar" autofocus="autofocus"}} '}}
						<button class="submit-icon" {{ '{{action search this}}' }}><i class="icon-search"></i></button>
					</label>
				</p>
			</form>
		</div>
	</div>
	<div class="row-fluid">
        <div class="span12">
			<div class="box">
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							 <tr>
								<td>E-mail</td>
								<td>Nombre</td>
								<td>Apellido</td>
								<td>Estado</td>
								<td>Acciones</td>
							</tr>
						</thead>
						<tbody>
							{{'{{#each controller}}'}}
							<tr>
								<td>{{ '{{#linkTo "contacts.show" this}}{{email}}{{/linkTo}}' }}</td>
								<td>{{'{{name}}'}}</td>
								<td>{{'{{lastName}}'}}</td>
								<td>
									<dl>
										<dd>
											{{ '{{#if isActive}}' }}
												<span class="green-label">Activo</span>
											{{ '{{else}}' }}
												<span class="orange-label">Inactivo</span>
											{{ '{{/if}}' }}
										</dd>
										<dd>
											{{ '{{#if isBounced}}' }}
												Rebotado
											{{ '{{/if}}' }}
										</dd>
										{{ '{{#unless isSubscribed}}' }}
										<dd>
												Desuscrito
										</dd>
										{{ '{{/unless}}' }}
										{{ '{{#if isSpam}}' }}
										<dd>
											<span class="red-label">SPAM</span>
										</dd>
										{{ '{{/if}}' }}

									</dl>
								</td>
								<td>
									<dl>
										<dd>{{ '{{#linkTo "contacts.show" this}}Ver{{/linkTo}}' }}</dd>
										<dd>{{ '{{#linkTo "contacts.edit" this}}Editar{{/linkTo}}' }}</dd>
										<dd>{{ '{{#linkTo "contacts.delete" this}}Eliminar{{/linkTo}}' }}</dd>
									</dl>
								</td>
							</tr>
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
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
			{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}
	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="contacts/edit">
<p>Agrega un nuevo contacto, con sus datos más básicos. </p>
	<form>
		<div class="row-fluid">
			<div class="span3">
				<p>
					<label>E-mail: </label>
				</p>
				<p>
					{{' {{view Ember.TextField valueBinding="email" placeholder="E-mail" id="email" required="required" autofocus="autofocus"}} '}}
				</p>
				<p>
					<label>Nombre: </label>
				</p>
				<p>	
					{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" }} '}}
				</p>
				<p>
					<label>Apellido: </label>
				</p>
				<p>
					{{' {{view Ember.TextField valueBinding="lastName" placeholder="Apellido" id="lastName" required="required"}} '}}
				</p>
				<p>
					<label>Estado: </label>
					{{ '{{#if isSubscribed}}' }}
						<label class="checkbox checked" for="isActive">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
					{{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed"}} '}}  Suscrito
						</label>
					{{ '{{else}}' }}
						<label class="checkbox" for="isActive">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
				 {{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed"}} '}}  Suscrito
						</label>
			{{ '{{/if}}' }}
				</p>
				<!-- Campos Personalizados -->
							{%for field in fields%}
								<p><label for="campo{{field.idCustomField}}">{{field.name}}:</label></p>
								<p>{{ember_customfield(field)}}</p>
								{% if (field.type == "Text") %}
									Maximo {{field.maxLength}} caracteres
								{% elseif field.type == "Numerical" %}
									El valor debe estar entre {{field.minValue}} y {{field.maxValue}} numeros
								{%endif%}
							{%endfor%}
						</p>
				<!--  Fin de campos personalizados -->
				<p>
					<button class="btn btn-success" {{' {{action edit this}} '}}>Editar</button>
					<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
				</p>	
			</div>
		</div>
	</form>
</script>
<script type="text/x-handlebars" data-template-name="contacts/delete">
	<div class="row-fluid">
		<div class="span6 message-delete">
			<p>Esta seguro que desea Eliminar el Contacto <strong>{{'{{this.name}}'}}</strong></p>
			<p>Recuerde que se eliminara de <strong>TODAS</strong> sus listas y de su Base de datos</p>
			<button {{'{{action delete this}}'}} class="btn btn-danger">
				Eliminar
			</button>
			<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>
				Cancelar
			</button>
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="contacts/show">
<div class="row-fluid">
	<div class="span7 well well-small">
	<h3>Detalles de Contacto</h3>
		<div class="row-fluid">
			<table class="contact-info">
				<tr>
					<td>Email:</td>
					<td>{{'{{email}}'}}</td>
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
		<div class="row-fluid">
			{{ '{{#if isSubscribed}}' }}
				<button class="btn btn-sm btn-info" {{' {{action unsubscribedcontact this}} '}}>Des-suscribir</button>
			{{ '{{else}}' }}
				<button class="btn btn-sm btn-info" {{' {{action subscribedcontact this}} '}}>Suscribir</button>
			{{ '{{/if}}' }}
			
			{{ '{{#linkTo "contacts.edit" this}}<button class="btn btn-sm btn-info">Editar</button>{{/linkTo}}' }}
			{{ '{{#linkTo "contacts"}}<button class="btn btn-sm btn-inverse">Regresar</button>{{/linkTo}}' }}
		</div>
	</div>
		
	<div class="span3">
		<div class="row-fluid badge-show-dark well well-small">
			Ultimas Campañas
			<br>
			----------------------------------
			<br>
		</div>
		<div class="row-fluid badge-show-medium well well-small">
			Ultimos Eventos
			<br>
			----------------------------------
			<br>
		</div>
		<div class="row-fluid badge-show-ligth well well-small">
			<table>
				<tbody>
					{{ '{{#if subscribedOn}}' }}
					<tr>
						<td class="text-right">
							<span class="text-green-color">Suscrito:&nbsp</span> 
						</td>
						<td>
							<span class="number-small">{{'{{subscribedOn}}'}}</span>
						</td>
					</tr>
					<tr>
						<td class="text-right">
							<span class="text-green-color">IP de Suscripcion:&nbsp</span>
						</td>
						<td>
							<span class="number-small">{{'{{ipSubscribed}}'}}</span>
						</td>
					</tr>
					{{ '{{/if}}' }}
					{{ '{{#if isActive}}' }}
					<tr>
						<td class="text-right">
							<span class="text-blue-color">Activado:&nbsp</span>
						</td>
						<td>
							<span class="number-small">{{'{{activatedOn}}'}}</span>
						</td>
					</tr>
					<tr>
						<td class="text-right">
							<span class="text-blue-color">IP de Activacion:&nbsp</span> 
						</td>
						<td>
							<span class="number-small">{{'{{ipActive}}'}}</span>
						</td>
					</tr>
					{{ '{{/if}}' }}
					{{ '{{#if isBounced}}' }}
					<tr>
						<td class="text-right">
							<span class="text-brown-color">Rebotado:&nbsp</span>
						</td>
						<td>
							<span class="number-small">{{'{{bouncedOn}}'}}</span>
						</td>
					</tr>
					{{ '{{/if}}' }}
					{{ '{{#if isSpam}}' }}
					<tr>
						<td class="text-right"> 
							<span class="text-red-color">Reportado Spam:&nbsp</span>
						</td>
						<td>
							<span class="number-small">{{'{{spamOn}}'}}</span>
						</td>
					</tr>
					{{ '{{/if}}' }}
					<tr>
						<td class="text-right">
							<span class="text-gray-color">Des-suscrito:&nbsp</span>
						</td>
						<td>
							<span class="number-small">{{'{{unsubscribedOn}}'}}</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</script>