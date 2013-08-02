{% extends "templates/index.volt" %}

{% block header_javascript %}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api/dbase/{{ sdbase.idDbase }}';
	</script>
	{{ super() }}
{% endblock %}

{% block content %}

{{ content() }}
<script type="text/x-handlebars">       
        <div class="row-fluid">
                <div class="span12">
                        <ul class="nav nav-pills">
                                {{'{{#linkTo "index" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>General</a>{{/linkTo}}'}}
                                {{'{{#linkTo "fields" tagName="li" href=false}}<a {{bindAttr href="view.href"}}>Campos</a>{{/linkTo}}'}}
                                <li><a href="#">Listas</a></li>
                                <li><a href="#">Segmentos</a></li>
                                <li><a href="#">Estadisticas</a></li>
                                <li><a href="#">Formularios</a></li>
                        </ul>
                </div>
        </div>
        {{ "{{outlet}}" }}
</script>
        
<script type="text/x-handlebars" data-template-name="fields">
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
									<th class="span2">
											Requerido
									</th>
									<th class="span2">
											Opcion por Defecto
									</th>
									<th class="span2">
											Opciones
									</th>
									<th class="span2">
											Accion
									</th>
                                </tr>
                        </thead>
                        <tbody>
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
									<td>{{'{{values}}'}}</td>
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
 {{ "{{outlet}}" }}
</script>


<div class="row-fluid">
        <div class="row-fluid">
                <div class="span8">
                        <div class="modal-header">
                                <h1>{{sdbase.name}}</h1>
                        </div>
                </div>
                <div class="span4" >
                        <span class="return-upper-right-corner"><a href="/emarketing/dbase"><h3>Regresar</h3></a></span>
                </div>
        </div>
        <div id="emberAppContainer"></div>

	<script type="text/x-handlebars" data-template-name="index">
		<div class="row-fluid">
				<div class="span8">
						<div class="row-fluid">
								Descripcion: {{sdbase.description}}
						</div>
						<div class="row-fluid">
								Descripcion de Contactos: {{sdbase.Cdescription}}
						</div>
						<div class="row-fluid">
								Fecha
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
								<span class="number-large text-gray-color">{{ sdbase.Cinactive|numberf }}</span>
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
					 {{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="true"}} '}}
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
					<label for="value_default">Valor por defecto </label>
						{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="value_default"}}' }}
				</div>
				<div class="row-fluid">
					{{ '{{#if isSelect}}' }}
						{{ '{{partial "fields/select"}}' }}
					{{ '{{/if}}' }}
				</div>
				<div class="row-fluid" id="SaveNewField">
					<div class="span3"><button class="btn btn-success" {{' {{action save this}} '}}>Grabar</button></div>
					<div class="span3"><button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button></div>
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
					<label for="value_default"=>Valor por defecto</label>
						{{ '{{view Ember.TextField valueBinding="defaultValue" placeholder="Valor por defecto" id="value_default"}}' }}
						{{ '{{#if isSelect}}' }}
							{{ '{{partial "fields/select"}}' }}
						{{ '{{/if}}' }}
				</div>
				<div class="row-fluid" id="SaveNewField">
					<div class="span3"><button class="btn btn-success" {{' {{action edit this}} '}}>Editar</button></div>
					<div class="span3"><button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button></div>
				</div>	
			</div>
		</form>
	</div>
</div>
</script>

<script type="text/x-handlebars" data-template-name="fields/remove">
<div class="row-fluid">
 <div class="span5">

 <p>Esta seguro que desea Eliminar el Campo <strong>{{'{{this.name}}'}}</strong></p>
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
		<label for="values">Demas opciones</label>
		{{ '{{view Ember.TextArea valueBinding="values" placeholder="Valor" id="values"}}' }}
</script>
{% endblock %}
