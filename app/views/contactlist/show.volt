{% extends "templates/index_new.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}
<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api/contactlist/{{datalist.idContactlist}}';
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
			list: DS.belongsTo('App.List'),
			isReallyActive: function () {
				if (this.get('isActive') && this.get('isSubscribed') && !(this.get('isSpam') || this.get('isBounced'))) {
					return true;
				}
				return false;
			}.property('isSubscribed,isActive')

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

	{{ javascript_include('js/app_contact.js') }}
	{{ javascript_include('js/list_model.js') }}
	{{ javascript_include('js/app_contact_list.js') }}
	
	<script>
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
		{%endfor%}
	</script>

	
{% endblock %}

{% block sectiontitle %}Lista: <strong>{{datalist.name}}</strong>{% endblock %}
{%block sectionsubtitle %}{{datalist.description}}{% endblock %}
	
{% block content %}

	<script type="text/x-handlebars" >
		<div class="box">
		<div class="clearfix">
			<ul class="inline sparkline-box" style="">

				<li class="sparkline-row">
					<h4 class="blue"><span>Contactos totales</span> {{'{{App.currentList.totalContactsF}}'}}</h4>
				</li>

				<li class="sparkline-row">
					<h4 class="green"><span>Activos</span> {{'{{App.currentList.activeContactsF}}'}}</h4>
				</li>

				<li class="sparkline-row">
					<h4 class="gray"><span>Inactivos</span> {{'{{App.currentList.inactiveContactsF}}'}}</h4>
				</li>
				<li class="sparkline-row">
					<h4 class="gray"><span>Des-suscritos</span> {{'{{App.currentList.unsubscribedContactsF}}'}}</h4>
				</li>
				<li class="sparkline-row">
					<h4 class="red"><span>Rebotados</span> {{'{{App.currentList.bouncedContactsF}}'}}</h4>
				</li>
				<li class="sparkline-row">
					<h4 class="red"><span>Spam</span> {{'{{App.currentList.spamContactsF}}'}}</h4>
				</li>

			</ul>
		</div>
		</div>

		{{'{{outlet}}'}}
	</script>
	
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppContactContainer">
		<script type="text/x-handlebars" data-template-name="contacts/index">
			<div class="pull-right" style="margin-bottom: 5px;">
				<a href="{{url('contactlist#/lists')}}" class="btn btn-blue"><i class="icon-home"></i> Todas las listas</a>
				{{'{{#linkTo "contacts.new" class="btn btn-default"}}'}}<i class="icon-plus"></i> Crear Contacto{{'{{/linkTo}}'}}
				{{'{{#linkTo "contacts.newbatch" class="btn btn-default"}}'}}<i class="icon-align-justify"></i> Crear Varios Contactos{{'{{/linkTo}}'}}
				{{ '{{#linkTo "contacts.import" class="btn btn-default"}}'}}<i class="icon-file-alt"></i> Importar Contactos{{'{{/linkTo}}'}}			
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
				{{ '{{/each}}' }}
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="contacts">
				{{'{{outlet}}'}}
		</script>
		
		<script type="text/x-handlebars" data-template-name="contacts/new">
			<div class="box span4">
				<div class="box-header"><span class="title">Crear nuevo contacto</strong></span></div>
				<div class="box-content">
					<form>
						<div class="padded">
							<label>*E-mail:
								{{' {{#if errors.email}} '}}
									<span class="text text-error">{{'{{errors.email}}'}}</span>
								{{' {{/if }} '}}
							</label>
							{{'{{view Ember.TextField valueBinding="email" required="required" autofocus="autofocus"}}'}}
							<label>Nombre:
								{{' {{#if errors.name}} '}}
									<span class="text text-error">{{'{{errors.name}}'}}</span>
								{{' {{/if }} '}}
							</label>
							{{'{{view Ember.TextField valueBinding="name"}}'}}
							<label>Apellido:
								{{' {{#if errors.lastName}} '}}
									<span class="text text-error">{{'{{errors.lastName}}'}}</span>
								{{' {{/if }} '}}
							</label>
							{{'{{view Ember.TextField valueBinding="lastName"}}'}}
							<!-- Campos Personalizados -->
							{%for field in fields%}
								<label for="{{field.name}}">{{field.name}}:</label>
								{{ember_customfield(field)}}
								{% if (field.type == "Text") %}
									Maximo {{field.maxLength}} caracteres
								{% elseif field.type == "Numerical" %}
									El valor debe estar entre {{field.minValue}} y {{field.maxValue}} numeros
								{%endif%}
							{%endfor%}
							<!--  Fin de campos personalizados -->
							<br>
						</div>
						<div class="form-actions">
							<button class="btn btn-primary" {{'{{action save this}}'}}>Guardar</button>
							<button class="btn btn-inverse" {{'{{action cancel this}}'}}>Cancelar</button>
						</div>
					</form>
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="contacts/newbatch">
		<div class="alert-error"><h4>{{ flashSession.output() }}</h4></div>
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
				<form method="Post" action="/emarketing/contacts/newbatch/{{datalist.idContactlist}}" , 'method': 'Post') }}
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
						 {{' {{view Ember.Checkbox  checkedBinding="isSubscribed" id="isSubscribed" disabledBinding="isEmailBlocked"}} '}}  Suscrito
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
				<div class="span5 message-delete">
					<p>Esta seguro que desea Eliminar el Contacto <strong>{{'{{this.name}}'}}</strong></p>
					<p>Recuerde que si el contacto solo esta asociado a esta lista se eliminara por completo de su Base de Datos</p>
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
					<td>{{'{{'~field.name~'}}'}}</td>
				</tr>
				{%endfor%}
			</table>
		</div>
		<div class="row-fluid">
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

<script type="text/x-handlebars" data-template-name="contacts/import">
	<form method="POST" action="/emarketing/contacts/import#/contacts" enctype="multipart/form-data">
		<div class="row-fluid">
			<div class="span6">
				<input name="importFile" type="file"><br>
				<input type="hidden" name="idcontactlist" value={{datalist.idContactlist}}>
				{{submit_button('class': "btn btn-primary", "Cargar")}}
			</div>
		</div>
	</form>
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
