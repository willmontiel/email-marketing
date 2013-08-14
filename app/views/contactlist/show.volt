{% extends "templates/index.volt" %}
{% block header_javascript %}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api/';
	</script>
	{{ super() }}
	{{ partial("partials/embercontact_partial") }}
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
	<div id="emberAppcontactContainer">
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
										<dd>Ver</dd>
										<dd>Editar</dd>
										<dd>Eliminar</dd>
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
				<div class="text-right">
					{{'{{#linkTo "contacts.new"}}'}}<button class="btn btn-primary" >Agregar</button>{{'{{/linkTo}}'}}
					<button class="btn btn-primary" >Agregar Lotes</button>
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="contacts">
				{{ '{{#if Appcontacts.errormessage }}' }}
					<div class="alert alert-message alert-error">
				{{ '{{ Appcontacts.errormessage }}' }}
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
	</div>
{% endblock %}
