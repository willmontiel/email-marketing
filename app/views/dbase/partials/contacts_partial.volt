<script type="text/x-handlebars" data-template-name="contacts/index">
	<div class="row-fluid">
		<div class="span7">
			<div class="row-fluid">
				<div class="span12">
					<h2>DashBoard Contactos</h2>
					<form>
						<div class="col-lg-6">
							<div class="input-group">
								<input type="text" class="form-control">
								<div class="input-group-btn">
									<button type="button" class="btn btn-primary" tabindex="-1">Action</button>
									<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" tabindex="-1">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu pull-right">
										<li><a href="#">Action</a></li>
										<li><a href="#">Another action</a></li>
										<li><a href="#">Something else here</a></li>
									</ul>
								 </div>
							</div>
						 </div>
					</form>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12"></div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<p>
						Aqui esta toda la información necesaria para gestionar los datos de tus contactos, sientete a gusto. 
					</p>
				</div>
			</div>
		</div>
		<div class="span2"></div>
		<div class="span3">
			<div class="badge-number-light">
				<table class="offset4">
					<tr>
						<td>
							<span class="text-green-color">{{ sdbase.Cactive|numberf }}</span>
						</td>
						<td class="text-left">
							<span class="regular-text">Activos</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-gray-color text-left">{{ sdbase.Cinactive|numberf }}</span>
						</td>
						<td class="text-left">
							<span class="regular-text">Inactivos</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-gray-color text-left">{{ sdbase.Cunsubscribed|numberf }}</span>
						</td>	
						<td class="text-left">
							<span class="regular-text">Des-suscritos</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-brown-color text-left">{{sdbase.Cbounced|numberf }}</span>
						</td>
						<td class="text-left">
							<span class="regular-text">Rebotados</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-red-color text-left">{{sdbase.Cspam|numberf }}</span>
						</td>
						<td class="text-left">
							<span class="regular-text">Spam</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
	
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
				{{'{{#each controller}}'}}
					<tbody>
						<tr>
							<td>{{'{{email}}'}}</td>
							<td>{{'{{name}}'}}</td>
							<td>{{'{{lastName}}'}}</td>
							<td>
								<dl>
									<dd>
										{{ '{{#if status}}' }}
											<span class="green-label">Activo</span>
										{{ '{{else}}' }}
											<span class="yellow-label">Inactivo</span>
										{{ '{{/if}}' }}
									</dd>
									<dd>
										{{ '{{#if isBounced}}' }}
											Rebotado
										{{ '{{/if}}' }}
									</dd>
									{{ '{{#if isUnsubscribed}}' }}
									<dd>
											Desuscrito
									</dd>
									{{ '{{/if}}' }}
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
									<dd>{{ '{{#linkTo "contacts.edit" this}}Editar{{/linkTo}}' }}</dd>
									<dd></dd>
								</dl>
							</td>
						</tr>
					</tbody>
				{{'{{/each}}'}}
			 </table>
        </div>
	</div>
	<div class="row-fluid">
		<div class="text-right">
			{{'{{#linkTo "contacts.new"}} <button class="btn btn-primary" >Agregar</button> {{/linkTo}}'}}
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="contacts">
	{{'{{outlet}}'}}
</script>
<script type="text/x-handlebars" data-template-name="contacts/new">
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
							{{ '{{#if status}}' }}
								<label class="checkbox checked" for="status">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
							{{' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '}}  Activo
								</label>
							{{ '{{else}}' }}
								<label class="checkbox" for="status">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
						 {{' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '}}  Activo
								</label>
					{{ '{{/if}}' }}
						</p>
						<p>
							<button class="btn btn-success" {{' {{action save this}} '}}>Grabar</button>
							<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
						</p>	
					</div>
				</div>
			</form>
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
					{{ '{{#if status}}' }}
						<label class="checkbox checked" for="status">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
					{{' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '}}  Activo
						</label>
					{{ '{{else}}' }}
						<label class="checkbox" for="status">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
				 {{' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '}}  Activo
						</label>
			{{ '{{/if}}' }}
				</p>
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