<script type="text/x-handlebars" data-template-name="contacts/index">
	<div class="row-fluid">
		<div class="span7">
			<div class="row-fluid">
				<div class="span12">
					<h2>Contactos</h2>
					<form>
						<p>
							{{' {{view Ember.TextField valueBinding="searchText" placeholder="Buscar" autofocus="autofocus"}} '}}
							<button class="btn btn-primary" {{ '{{action search this}}' }}>Buscar</button>
					
						</p>
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
							<td>{{ '{{#linkTo "contacts.show" this}}{{email}}{{/linkTo}}' }}</td>
							<td>{{'{{name}}'}}</td>
							<td>{{'{{lastName}}'}}</td>
							<td>
								<dl>
									<dd>
										{{ '{{#if isActived}}' }}
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
							{{ '{{#if isActived}}' }}
								<label class="checkbox checked" for="isActived">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
							{{' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '}}  Activo
								</label>
							{{ '{{else}}' }}
								<label class="checkbox" for="isActived">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
						 {{' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '}}  Activo
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
					{{ '{{#if isActived}}' }}
						<label class="checkbox checked" for="isActived">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
					{{' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '}}  Activo
						</label>
					{{ '{{else}}' }}
						<label class="checkbox" for="isActived">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
				 {{' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '}}  Activo
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
<script type="text/x-handlebars" data-template-name="contacts/show">
<div class="row-fluid">
	<div class="span7">
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
								{{ '{{#if isActived}}' }}
									<span class="green-label">Activo</span>
								{{ '{{else}}' }}
									<span class="orange-label">Inactivo</span>
								{{ '{{/if}}' }}
							</dd>
							<dd>
								Campo:
							</dd>
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
							<dd>
								info
							</dd>
						</dl>
					</td>
				</tr>
			</table>
		</div>
		<div class="row-fluid">
			{{ '{{#if isActived}}' }}
				<button class="btn btn-sm btn-info" {{' {{action deactivated this}} '}}>Desactivar</button>
			{{ '{{else}}' }}
				<button class="btn btn-sm btn-info" {{' {{action activated this}} '}}>Activar</button>
			{{ '{{/if}}' }}
			{{ '{{#if isSubscribed}}' }}
				<button class="btn btn-sm btn-info" {{' {{action unsubscribedcontact this}} '}}>Des-suscribir</button>
			{{ '{{else}}' }}
				<button class="btn btn-sm btn-info" {{' {{action subscribedcontact this}} '}}>Suscribir</button>
			{{ '{{/if}}' }}
			
			{{ '{{#linkTo "contacts.edit" this}}<button class="btn btn-sm btn-info">Editar</button>{{/linkTo}}' }}
			{{ '{{#linkTo "contacts"}}<button class="btn btn-sm btn-inverse">Regresar</button>{{/linkTo}}' }}
		</div>
	</div>
		
	<div class="span5">
		<div class="row-fluid">
			Ultimas Campañas
			<br>
			----------------------------------
			<br>
		</div>
		<div class="row-fluid">
			Ultimos Eventos
			<br>
			----------------------------------
			<br>
		</div>
		<div class="row-fluid">
			<table>
				<tbody>
					<tr>
						<td>
							Suscrito: 
						</td>
						<td>
							{{'{{subscribedOn}}'}}
						</td>
					</tr>
					<tr>
						<td>
							Direccion IP Suscrito: 
						</td>
						<td>
							{{'{{ipSubscribed}}'}}
						</td>
					</tr>
					<tr>
						<td>
							Activado: 
						</td>
						<td>
							{{'{{activedOn}}'}}
						</td>
					</tr>
					<tr>
						<td>
							Direccion IP Activado: 
						</td>
						<td>
							{{'{{ipActived}}'}}
						</td>
					</tr>
					<tr>
						<td>
							Rebotado: 
						</td>
						<td>
							{{'{{bouncedOn}}'}}
						</td>
					</tr>
					<tr>
						<td>
							Reportado Spam: 
						</td>
						<td>
							{{'{{spamOn}}'}}
						</td>
					</tr>
					<tr>
						<td>
							Des-suscrito: 
						</td>
						<td>
							{{'{{unsubscribedOn}}'}}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</script>