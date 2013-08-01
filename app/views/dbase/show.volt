{% extends "templates/index.volt" %}

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
									<th class="span3">
											Etiqueta
									</th>
									<th class="span2">
											Tipo
									</th>
									<th class="span2">
											Requerido
									</th>
									<th class="span3">
											Valor
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
		<div class="row-fluid">
				<div class="span12">
						<table class="table table-hover">
								<thead>
										<tr>
												<th>Direccion de Correo</th>
												<th>Nombre</th>
												<th>Estado</th>
												<th>Añadido en la Fecha</th>
										</tr>
								</thead>
								<tbody>
										<tr>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
										</tr>
								</tbody>            
						</table>
				</div>    
		</div>
	</script>

<script type="text/x-handlebars" data-template-name="fields/add">
<div class="row-fluid">
	<div class="span12">
		<form {{' {{action "save" on="submit"}} '}}>
			<table class="table table-hover">
				<tbody>
					<tr>
						<td class="span3">
							{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre"}} '}}	
						</td>
						<td class="span2">
						
							{{ '{{view Ember.Select
								contentBinding="App.types"
								optionValuePath="content.id"
								optionLabelPath="content.type"
								valueBinding="type"}}'
							}}
							{{ '{{#if isSelect}}' }}
								{{ '{{partial "fields/select"}}' }}
							{{ '{{/if}}' }}
						</td>
						<td class="span2">	
							{{' {{view Ember.Checkbox  checkedBinding="required"}} '}}			
						</td>
						<td class="span2">
						</td>
						<td class="span3">
							<button class="btn btn-success">Grabar</button>
							<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
						</td>
					</tr>
				</tbody>	
			</table>
		</form>
	</div>
</div>	
</script>

<script type="text/x-handlebars" data-template-name="fields/edit">
<div class="row-fluid">
	<div class="span12">
		<form {{' {{action "save" on="submit"}} '}}>
			<table class="table table-hover">
				<tbody>
					<tr>
						<td class="span3">
							{{' {{view Ember.TextField valueBinding="name"}} '}}	
						</td>
						<td class="span2">
							{{ '{{type}}'}}
						</td>
						<td class="span2">
							{{' {{view Ember.Checkbox  checkedBinding="required"}} '}}Requerido
						</td>
						<td class="span3">
							{{' {{view Ember.TextField valueBinding="values"}} '}}	
						</td>
						<td class="span2">
							<button class="btn btn-success">Editar</button>
							<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
						</td>
					</tr>
				</tbody>	
			</table>
		{{ "{{outlet}}" }}
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
		{{ '{{view Ember.TextArea valueBinding="values" placeholder="Valor"}}' }}
</script>
{% endblock %}
