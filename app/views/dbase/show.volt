{% extends "templates/index_new.volt" %}

{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ partial("partials/date_view_partial") }}
	{{ javascript_include('js/mixin_pagination.js') }}

	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url ~ '/dbase/' ~ sdbase.idDbase }}';
		
		var myContactModel = {
			list: DS.belongsTo('list'),
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
{% endblock %}
{% block sectiontitle %}
	<i class="icon-book"></i> {{sdbase.name}}
{% endblock %}
{%block sectionsubtitle %} {{sdbase.description}} {% endblock %}
{% block content %}
<script type="text/x-handlebars"> 
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<ul class="nav nav-tabs nav-tabs-left">
						{{'{{#linkTo "index" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>General</a>{{/linkTo}}'}}
						{{'{{#linkTo "fields" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>Campos</a>{{/linkTo}}'}}
						{{'{{#linkTo "contacts" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>Contactos</a>{{/linkTo}}'}}                                                                
						<li><a href="#">Segmentos</a></li>
						<li><a href="#">Estadisticas</a></li>
						<li><a href="#">Formularios</a></li>
					</ul>
				</div>
				<div class="box-content padded">
					{{ "{{outlet}}" }}
				</div>
			</div>
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="fields/index">       
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-content">
					<div class="box-section news with-icons">
						<div class="avatar green">
							<i class="icon-tags icon-2x"></i>
						</div>
						<div class="news-time">
						</div>
						<div class="news-content">
							<div class="news-title">
								Campos de la Base de Datos
							</div>
							<div class="news-text">
								Esta seccion esta dedicada a la Lectura
								y Edicion de los Campos Personalizados
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 padded text-right">
			{{'{{#linkTo "fields.add" class="btn btn-default"}}<i class="icon-plus"></i> Agregar campo{{/linkTo}}'}}
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Etiqueta</td>
								<td>Tipo</td>
								<td>Requerido</td>
								<td>Valor por Defecto</td>
								<td>Accion</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Email</td>
								<td>Text</td>
								<td>
									<div class="icheckbox_flat-aero checked hover">
									</div>
								</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Nombre</td>
								<td>Text</td>
								<td>
									<div class="icheckbox_flat-aero hover">
									</div>
								</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Apellido</td>
								<td>Text</td>
								<td>
									<div class="icheckbox_flat-aero hover">
									</div>
								</td>
								<td></td>
								<td></td>
							</tr>
						{{'{{#each controller}}'}}
							<tr>
								<td>{{'{{name}}'}}</td>
								<td>{{'{{type}}'}}</td>
								<td>
									{{ '{{#if required}}' }}
										<div class="icheckbox_flat-aero checked hover">
										</div>
									{{ '{{else}}' }}
										<div class="icheckbox_flat-aero hover">
										</div>
									{{ '{{/if}}' }}
								</td>
								<td>{{'{{defaultValue}}'}}</td>
								<td>
									<div class="pull-right">
										<div class="btn-group">
											<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li>{{ '{{#linkTo "fields.edit" this}}' }}<i class="icon-pencil"></i> Editar{{'{{/linkTo}}'}}</li>
												<li>{{'{{#linkTo "fields.remove" this}}'}}<i class="icon-trash"></i> Eliminar {{'{{/linkTo}}'}}</li>
											</ul>
										</div>
									</div>
									
									
								</td>
							</tr>
						{{'{{else}}'}}
							<tr><td colspan="4">No hay campos personalizados</td></tr>
						{{'{{/each}}'}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
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
		<div class="span12 text-right">
			<a href="{{url('dbase')}}" class="btn btn-default">Regresar</a>
		</div>
	</div>
</div>
	<!------------------ Ember! ---------------------------------->
<div id="emberAppContainer">
<script type="text/x-handlebars" data-template-name="index">
	<div class="row-fluid">
		<div class="span6">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Información de la base de datos
					</div>
				</div>
				<div class="box-content padded">
					<div class="box-section news with-icons">
						<div class="avatar purple">
							<i class="icon-book icon-2x"></i>
						</div>
						<div class="news-time">
							<span>14</span>feb
						</div>
						<div class="news-content">
							<div class="news-title">
								{{sdbase.description}}
							</div>
							<div class="news-text">
								<p>Descripcion de Contactos: {{sdbase.Cdescription}}</p>
								Creada el {{date('Y-m-d', sdbase.createdon)}}<br />
								Actualizada el {{date('Y-m-d', sdbase.updatedon)}}
							</div>
						</div>
					</div>
					<br />
					<div class="row-fluid">
						<div class="span2">
							<a href="{{url('dbase/edit')}}/{{sdbase.idDbase}}" class="btn btn-default">Editar</a>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<div class="span6">
			<div class="box">
				<div class="box-header">
					<div class="title">Información de contactos</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<tbody>
							<tr>
								<td><i class="icon-user"></i> Contactos totales</td>
								<td class="status-success"><span class="label label-blue">{{ sdbase.Ctotal|numberf }}</span></td>
							</tr>

							<tr>
								<td><i class="icon-ok"></i> Contactos Activos</td>
								<td class="status-success"><span class="label label-green">{{ sdbase.Cactive|numberf }}</span></td>
							</tr>

							<tr>
								<td><i class="icon-question-sign"></i> Contactos Inactivos</td>
								<td class="status-error"><span class="label label-gray"> {{ get_inactive(sdbase)|numberf }}</span></td>
							</tr>

							<tr>
								<td><i class="icon-check-empty"></i> Contactos Des-suscritos</td>
								<td class="status-success"><span class="label label-warning"> {{ sdbase.Cunsubscribed|numberf }}</span></td>
							</tr>

							<tr>
								<td><i class="icon-retweet"></i> Contactos Rebotados</td>
								<td class="status-success"><span class="label label-red"> {{sdbase.Cbounced|numberf }}</span></td>
							</tr>

							<tr>
								<td><i class="icon-exclamation-sign"></i> Contactos Spam</td>
								<td class="status-success"><span class="label label-red"> {{sdbase.Cspam|numberf }}</span></td>
							</tr>
						</tbody>
					</table>
				</div>
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
						{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="defaultValue"}}' }}
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
