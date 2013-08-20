{% extends "templates/index.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api/contactlist/{{datalist.idList}}';
		
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
			isActive: DS.attr('boolean')
			{%for field in fields%}
			,
				{% if field.type == "Text" %}
					{{field.name|lower }}: DS.attr('string')
				{% elseif field.type == "Date" %}
					{{field.name|lower }}: DS.attr('string')
				{% elseif field.type == "TextArea" %}
					{{field.name|lower }}: DS.attr('string')
				{% elseif field.type == "Numerical" %}
					{{field.name|lower }}: DS.attr('number')
				{% elseif field.type == "Select" %}
					{{field.name|lower }}: DS.attr('string')
				{% elseif field.type == "MultiSelect" %}
					{{field.name|lower }}: DS.attr('string')
				{% endif %}
			
			{%endfor%}
		};
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/app_contact.js') }}
	<script>
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
		{%endfor%}
	</script>

	
{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div class="span12">
					<h1>{{datalist.name}}</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span8">
					{{datalist.description}}
				</div>
				<div class="span1"></div>
				<div class="span3">
					<div class="badge-number-light">
						<table class="offset4">
							<tr>
								<td>
									<span class="text-green-color"></span>
								</td>
								<td class="text-left">
									<span class="regular-text">Activos</span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="text-gray-color text-left"></span>
								</td>
								<td class="text-left">
									<span class="regular-text">Inactivos</span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="text-gray-color text-left"></span>
								</td>	
								<td class="text-left">
									<span class="regular-text">Des-suscritos</span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="text-brown-color text-left"></span>
								</td>
								<td class="text-left">
									<span class="regular-text">Rebotados</span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="text-red-color text-left"></span>
								</td>
								<td class="text-left">
									<span class="regular-text">Spam</span>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
	<br>
	
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppContactContainer">
		<script type="text/x-handlebars" data-template-name="contacts/index">
			<div class="row-fluid">
				<div class="text-right">
					{{'{{#linkTo "contacts.new"}}'}}<button class="btn btn-primary">Agregar</button>{{'{{/linkTo}}'}}
					{{'{{#linkTo "contacts.newbatch"}} <button class="btn btn-primary" >Agregar Lotes</button> {{/linkTo}}'}}
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12">
					<table class="table table-striped">
						<thead>
							 <tr>
								<th class="span3">
									E-mail
								</th>
								<th class="span3">
									Nombre
								</th>
								<th class="span3">
									Apellido
								</th>
								<th class="span2">
									Estado
								</th>
								<th class="span1">
									Acciones
								</th>
							</tr>
						</thead>
						<tbody>
					{{'{{#each model}}'}}
							<tr>
								<td>{{'{{email}}'}}</td>
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
					{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span8">
					<div class="pagination">
						<ul>
							<li class="previous"><span class="fui-arrow-left" {{ '{{action prevPage this}}' }} style="cursor: pointer;"></span></li>
								{{ '{{#each AvailablePages}}' }}
										<li>{{ '{{view Applist.AvailablePages contentBinding="this"}}' }}</li>
								{{ '{{/each}}' }}
							<li class="next"><span class="fui-arrow-right" {{ '{{action nextPage this}}' }}></span></li>
						</ul>
					 </div>
				</div>
				<div class="span1">
					{{  '{{totalrecords}}' }}
					{{  '{{currentpage}}' }}<br>
					{{  '{{availablepages}}' }}
				</div>
				<div class="span3 text-right">
					<br>
					{{'{{#linkTo "contacts.new"}}'}}<button class="btn btn-primary" >Agregar</button>{{'{{/linkTo}}'}}
					<button class="btn btn-primary" >Agregar Lotes</button>
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
		
		<script type="text/x-handlebars" data-template-name="contacts/new">
			<div class="row-fluid">
				<div class="span3">
					<form>
						<label>*E-mail:</label>
						<p>
							{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus"}}'}}
						</p>
						<label>Nombre:</label>
						<p>
							{{'{{view Ember.TextField valueBinding="name"}}'}}
						</p>
						<label>Apellido:</label>
						<p>
							{{'{{view Ember.TextField valueBinding="lastName"}}'}}
						</p>
						<br>
						<p>
							<button class="btn btn-primary" {{'{{action save this}}'}}>Guardar</button>
							<button class="btn btn-inverse" {{'{{action cancel this}}'}}>Cancelar</button>
						</p>
					</form>
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/newbatch">
		<div class="row-fluid">
				<div class="span2">
					<div class="tooltip fade top in" display: block;">
						<div class="tooltip-arrow">
						</div>
						<div class="tooltip-inner infobatch">
							Ingrese los Contactos separados por saltos de linea
							y los campos por COMAS ",".
						</div>
					</div>
				</div>
				<form method="Post" action="/emarketing/contacts/newbatch/{{datalist.idList}}" , 'method': 'Post') }}
				<div class="span5">
					{{ text_area("arraybatch") }}
					<input class="btn btn-sm btn-inverse" type="submit" value="Guardar">
					{{ '{{#linkTo "contacts"}}<button class="btn btn-sm btn-inverse">Cancelar</button>{{/linkTo}}' }}
				</div>
				</form>
		</div>
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
								<p><label for="{{field.name}}">{{field.name}}:</label></p>
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
		<div class="span5">
			<p>Esta seguro que desea Eliminar el Contacto <strong>{{'{{this.name}}'}}</strong></p>
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
					<td>
						<dl>
							<dd>
								Email:
							</dd>
							<dd>
								Nombre:
							</dd>
							<dd>
								Apellido:
							</dd>
							<dd>
								{{ '{{#if isActive}}' }}
									<span class="green-label">Activo</span>
								{{ '{{else}}' }}
									<span class="orange-label">Inactivo</span>
								{{ '{{/if}}' }}
							</dd>
							<!-- Campos Personalizados -->
							{%for field in fields%}
								<dd> {{field.name}} </dd>
							{%endfor%}
							<!--  Fin de campos personalizados -->
						</dl>
					</td>
					<td>
						<dl>
							<dd>
								{{'{{email}}'}}
							</dd>
							<dd>
								{{'{{name}}'}}
							</dd>
							<dd>
								{{'{{lastName}}'}}
							</dd>
							<dd>
								{{ '{{#if isSubscribed}}' }}
									<span class="green-label">Suscrito</span>
								{{ '{{else}}' }}
									<span class="orange-label">Des-Suscrito</span>
								{{ '{{/if}}' }}	
							</dd>
							<!-- Campos Personalizados -->
							{%for field in fields%}
								<dd> Valor del campo </dd>
							{%endfor%}
							<!--  Fin de campos personalizados -->
						</dl>
					</td>
				</tr>
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
</div>
{% endblock %}
