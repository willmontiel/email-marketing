{% extends "templates/index.volt" %}

{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('js/mixin_pagination.js') }}

	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url ~ '/dbase/' ~ sdbase.idDbase }}';
		
		var myContactModel = {
			list: DS.belongsTo('App.List'),
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
	
	{{ javascript_include('js/app.js') }}
	
	<script>
		{%for field in fields %}
			{{ ember_customfield_options(field) }}
		{%endfor%}
	</script>
	
	<script>
		App.ListObjectDB = App.store.findAll(App.List);
	</script>
{% endblock %}

{% block content %}
<script type="text/x-handlebars">       
        <div class="row-fluid">
                <div class="span12">
                        <ul class="nav nav-pills">
                                {{'{{#linkTo "index" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>General</a>{{/linkTo}}'}}
								{{'{{#linkTo "fields" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>Campos</a>{{/linkTo}}'}}
								{{'{{#linkTo "contacts" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>Contactos</a>{{/linkTo}}'}}                                                                
                                <li><a href="#">Segmentos</a></li>
                                <li><a href="#">Estadisticas</a></li>
                                <li><a href="#">Formularios</a></li>
                        </ul>
                </div>
        </div>
        {{ "{{outlet}}" }}
</script>
        
<script type="text/x-handlebars" data-template-name="fields/index">
<div class="row-fluid">
	<div class="span12">
			<h4>Campos de la Base de Datos</h4>
			<p>Esta seccion esta dedicada a la Lectura
			y Edicion de los Campos Personalizados
			</p>
	</div>
</div>
<div class="row-fluid">

	<div class="span12"></div>
</div>
<div class="row-fluid">
        <div class="span12">
                <table class="table table-hover">
                        <thead>
                                 <tr>
									<th class="span2">
											Etiqueta
									</th>
									<th class="span2">
											Tipo
									</th>
									<th class="span1">
											Requerido
									</th>
									<th class="span2">
											Valor por Defecto
									</th>
									<th class="span2">
											Accion
									</th>
                                </tr>
                        </thead>
                        <tbody>
								<tr>
									<td>
										Email
									</td>
									<td>
										Text
									</td>
									<td>
										<label class="checkbox checked" for="required">
											<span class="icons">
												<span class="first-icon fui-checkbox-unchecked"></span>
												<span class="second-icon fui-checkbox-checked"></span>
											</span>
										</label>
									</td>
									<td>
											
									</td>
									<td>
											
									</td>
                                </tr>
								<tr>
									<td>
										Nombre
									</td>
									<td>
										Text
									</td>
									<td>
										<label class="checkbox">
											<span class="icons">
												<span class="first-icon fui-checkbox-unchecked"></span>
												<span class="second-icon fui-checkbox-checked"></span>
											</span>
										</label>
									</td>
									<td>
											
									</td>
									<td>
											
									</td>
                                </tr>
								<tr>
									<td>
										Apellido
									</td>
									<td>
										Text
									</td>
									<td>
										<label class="checkbox">
											<span class="icons">
												<span class="first-icon fui-checkbox-unchecked"></span>
												<span class="second-icon fui-checkbox-checked"></span>
											</span>
										</label>
									</td>
									<td>
											
									</td>
									<td>
											
									</td>
                                </tr>
							{{'{{#each controller}}'}}
                                <tr>
									<td>{{'{{name}}'}}</td>
									<td>{{'{{type}}'}}</td>
									<td>
										{{ '{{#if required}}' }}
											<label class="checkbox checked" for="required">
												<span class="icons">
													<span class="first-icon fui-checkbox-unchecked"></span>
													<span class="second-icon fui-checkbox-checked"></span>
												</span>
											</label>
										{{ '{{else}}' }}
											<label class="checkbox" for="required">
												<span class="icons">
													<span class="first-icon fui-checkbox-unchecked"></span>
													<span class="second-icon fui-checkbox-checked"></span>
												</span>
											</label>
										{{ '{{/if}}' }}
									</td>
									<td>{{'{{defaultValue}}'}}</td>
									<td>
										{{ '{{#linkTo "fields.edit" this}}' }}Editar{{'{{/linkTo}}'}}
										{{'{{#linkTo "fields.remove" this}}'}} Eliminar {{'{{/linkTo}}'}}
									</td>
                                </tr>
								
							{{'{{else}}'}}
								<tr><td colspan="4">No hay campos personalizados</td></tr>
							{{'{{/each}}'}}
                        </tbody>
                </table>
        </div>
</div>
<div class="row-fluid">
	{{'{{#linkTo "fields.add"}} Agregar {{/linkTo}}'}}
</div>
</script>

<script type="text/x-handlebars" data-template-name="fields">
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
			{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}	
		
	{{'{{outlet}}'}}
</script>

<div class="row-fluid">
	<div class="row-fluid">
			<div class="span8">
					<div class="modal-header">
							<h1>{{sdbase.name}}</h1>
					</div>
			</div>
			<div class="span4" >
					<span class="return-upper-right-corner"><a href="{{url('dbase')}}"><h3>Regresar</h3></a></span>
			</div>
	</div>
</div>

	<!------------------ Ember! ---------------------------------->
<div id="emberAppContainer">
		
<script type="text/x-handlebars" data-template-name="index">
	<div class="row-fluid">
			<div class="span8">
							<p>Descripcion: {{sdbase.description}}</p>
							<p>Descripcion de Contactos: {{sdbase.Cdescription}}</p>
							<p>Fecha de Creacion: {{date('Y-m-d', sdbase.createdon)}}</p>
							<p>Ultima Fecha de Actualizacion: {{date('Y-m-d', sdbase.updatedon)}}</p>
					<div class="row-fluid">
						<div class="span2">
							<a href="{{url('dbase/edit')}}/{{sdbase.idDbase}}" class="btn btn-large btn-block btn-info">Editar</a>
						</div>
					</div>
			</div>
			<div class="span4">
					<div class="badge-number-dark">
							<span class="number-huge">{{ sdbase.Ctotal|numberf }}</span>
							<br/>
							<span class="regular-text">Contactos totales</span>
					</div>
					<div class="badge-number-light">
							<span class="number-large text-green-color">{{ sdbase.Cactive|numberf }}</span>
							<br/>
							<span class="regular-text">Contactos Activos</span>
							<br/>
							<span class="number-large text-gray-color">{{ get_inactive(sdbase)|numberf }}</span>
							<br/>
							<span class="regular-text">Contactos Inactivos</span>
							<br/>
							<span class="number-large text-gray-color">{{ sdbase.Cunsubscribed|numberf }}</span>
							<br/>
							<span class="regular-text">Contactos Des-suscritos</span>
							<br/>
							<span class="number-large text-brown-color">{{sdbase.Cbounced|numberf }}</span>
							<br/>
							<span class="regular-text">Contactos Rebotados</span>
							<br/>
							<span class="number-large text-red-color">{{sdbase.Cspam|numberf }}</span>
							<br/>
							<span class="regular-text">Contactos Spam</span>
							<br/>
					</div>
			</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="fields/add">
		<div class="row-fluid">
	<div class="span12">
		<form>
			<div class="span4">
				<div class="row-fluid" id="nameNewField">
					<label for="name">Nombre del Campo</label>
					 {{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus"}} '}}
				</div>
				<div class="row-fluid" id="typeNewField">
					<label for="type">Tipo de Formato del Campo</label>
						<div class="row-fluid">
							{{ '{{view Ember.Select
									contentBinding="App.types"
									optionValuePath="content.id"
									optionLabelPath="content.type"
									valueBinding="type" id="type"}}'
							 }}
						</div>
						<div class="row-fluid">
							{{ '{{#if isText}}' }}
								{{ '{{partial "fields/text"}}' }}
							{{ '{{/if}}' }}
							{{ '{{#if isNumerical}}' }}
								{{ '{{partial "fields/numerical"}}' }}
							{{ '{{/if}}' }}
						</div>
					</label>
				</div>
				<div class="row-fluid" id="requiredNewField">
					<p>Seleccione si desea que el Campo sea Obligatorio</p>
					<div class="row-fluid">
					{{ '{{#if required}}' }}
						<label class="checkbox checked" for="required">
						<span class="icons">
							<span class="first-icon fui-checkbox-unchecked">
							</span>
							<span class="second-icon fui-checkbox-checked">
							</span>
						</span>
						 {{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido
						</label>
					{{ '{{else}}' }}
						<label class="checkbox" for="required">
						<span class="icons">
							<span class="first-icon fui-checkbox-unchecked">
							</span>
							<span class="second-icon fui-checkbox-checked">
							</span>
						</span>
						 {{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido
						</label>
					{{ '{{/if}}' }}
					</div>
				</div>	
				<div class="row-fluid" id="defaultNewField">
					{{ '{{#unless isDate}}' }}
						<label for="value_default">Valor por defecto </label>
						{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="value_default"}}' }}
					{{ '{{/unless}}' }}
				</div>
				<div class="row-fluid">
					{{ '{{#if isSelect}}' }}
						{{ '{{partial "fields/select"}}' }}
					{{ '{{/if}}' }}
				</div>
				<div class="row-fluid" id="SaveNewField">
					<button class="btn btn-success" {{' {{action save this}} '}}>Grabar</button>
					<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
				</div>	
			</div>
		</form>
	</div>
</div>
</script>

<script type="text/x-handlebars" data-template-name="fields/edit">
<div class="row-fluid">
	<div class="span12">
		<form>
			<div class="span4">
				<div class="row-fluid" id="nameNewField">
					<label for="name">Nombre del Campo</label>
					 {{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name"}} '}}</div>
				<div class="row-fluid">
					<div class="row-fluid" id="typeNewField">
							{{ '{{#if isText}}' }}
								{{ '{{partial "fields/text"}}' }}
							{{ '{{/if}}' }}
							{{ '{{#if isNumerical}}' }}
								{{ '{{partial "fields/numerical"}}' }}
							{{ '{{/if}}' }}
					</div>
				</div>
				<div class="row-fluid" id="requiredNewField">
					<p>Seleccione si desea que el Campo sea Obligatorio</p>
					<div class="row-fluid">
					{{ '{{#if required}}' }}
						<label class="checkbox checked" for="required">
						<span class="icons">
							<span class="first-icon fui-checkbox-unchecked">
							</span>
							<span class="second-icon fui-checkbox-checked">
							</span>
						</span>
						 {{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido
						</label>
					{{ '{{else}}' }}
						<label class="checkbox" for="required">
						<span class="icons">
							<span class="first-icon fui-checkbox-unchecked">
							</span>
							<span class="second-icon fui-checkbox-checked">
							</span>
						</span>
						 {{' {{view Ember.Checkbox  checkedBinding="required" id="required"}} '}}  Requerido
						</label>
					{{ '{{/if}}' }}
					</div>
				</div>	
				<div class="row-fluid" id="defaultNewField">
					{{ '{{#unless isDate}}' }}
						<label for="value_default">Valor por defecto </label>
						{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="value_default"}}' }}
					{{ '{{/unless}}' }}
				</div>
				<div class="row-fluid">
					{{ '{{#if isSelect}}' }}
						{{ '{{partial "fields/select"}}' }}
					{{ '{{/if}}' }}
				</div>
				<div class="row-fluid" id="SaveNewField">
					<button class="btn btn-success" {{' {{action edit this}} '}}>Editar</button>
					<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
				</div>	
			</div>
		</form>
	</div>
</div>
</script>

<script type="text/x-handlebars" data-template-name="fields/remove">
<div class="row-fluid">
 <div class="span5 message-delete">

 <p>Esta seguro que desea Eliminar el Campo <strong>{{'{{this.name}}'}}</strong></p>
 <p>Recuerde que se <strong>PERDERA LA INFORMACION</strong> de los contactos relacionada con este Campo</p>
  <button {{'{{action eliminate this}}'}} class="btn btn-danger">
		Eliminar
  </button>
  <button class="btn btn-inverse" {{ '{{action cancel this}}' }}>
		Cancelar
  </button>

 </div>
</div>
</script>

<script type="text/x-handlebars" data-template-name="fields/_select">
		<label for="values">Opciones de la lista</label>
		{{ '{{view Ember.TextArea valueBinding="values" placeholder="Valor" id="values"}}' }}
</script>

<script type="text/x-handlebars" data-template-name="fields/_text">
	<label for="maxlong">Longitud Maxima del Campo</label>
	<div class="span5">
		{{ '{{view Ember.TextField valueBinding="maxLength" placeholder="Letras" id="maxlong"}}' }}
	</div>
</script>

<script type="text/x-handlebars" data-template-name="fields/_numerical">
	<div class="span5">
		<label for="limit_Inf">Valor Minimo</label>
		{{ '{{view Ember.TextField valueBinding="minValue" placeholder="Inferior" id="limit_Inf"}}' }}
	</div>
	<div class="span1"></div>
	<div class="span5">
		<label for="limit_Sup">Valor Maximo</label>
		{{ '{{view Ember.TextField valueBinding="maxValue" placeholder="Superior" id="limit_Sup"}}' }}
	</div>
</script>

</div>

<!---------------------- Contacts Template -------------------------->
	{{ partial("dbase/partials/contacts_partial") }}

{% endblock %}
