<script type="text/x-handlebars" data-template-name="contacts/index">
	<div class="row-fluid">
		<div class="span7">
			<h2>DashBoard Contactos</h2>
			<form class="form-inline">
				<div class="span5">
					<input class="form-search" type="search" value="" placeholder="Buscar">
				</div>
				<div class="span1">
					<!--- <button class="btn btn-primary" type="Submit"><span class="fui-search"></span></button> -->
				</div>
			</form>
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
				<span class="text-green-color">{{ sdbase.Cactive|numberf }}</span>
				<span class="text-right">Activos</span>
				<br>
				<span class="text-gray-color text-left">{{ sdbase.Cinactive|numberf }}</span>
				<span class="regular-text">Inactivos</span>
				<br>
				<span class="text-gray-color text-left">{{ sdbase.Cunsubscribed|numberf }}</span>
				<span class="regular-text">Des-suscritos</span>
				<br>
				<span class="text-brown-color text-left">{{sdbase.Cbounced|numberf }}</span>
				<span class="regular-text">Rebotados</span>
				<br>
				<span class="text-red-color text-left">{{sdbase.Cspam|numberf }}</span>
				<span class="regular-text">Spam</span>
				<br>
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
						<th class="span2">
							E-mail
						</th>
						<th class="span2">
							Nombre
						</th>
						<th class="span2">
							Apellido
						</th>
						<th class="span2">
							Estado
						</th>
						<th class="span2">
						</th>
						<th class="span2">
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
								<p>Estado: {{' {{status}} '}}</p>
								<p>Rebotado: {{' {{bounced}} '}}</p>
								<p>Desuscrito: {{' {{unsubscribed}} '}}</p>
							</td>
							<td>
								<p class="text-left">Spam: {{' {{spam}} '}}</p>
								<p class="text-left">Activado desde IP: {{' {{ipActived}} '}}</p>
								<p class="text-left">Desuscrito desde IP: {{'{{ipSubscribed}}'}}</p>
							</td>
							<td>
								Ver
								Editar
								Eliminar
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
							{{' {{view Ember.TextField valueBinding="email" placeholder="E-mail" id="email" required="required" }} '}}
						</p>
						<p>
							<label>Nombre: </label>
						</p>
						<p>	
							{{' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" autofocus="autofocus"}} '}}
						</p>
						<p>
							<label>Apellido: </label>
						</p>
						<p>
							{{' {{view Ember.TextField valueBinding="lastName" placeholder="Apellido" id="lastName" required="required"}} '}}
						</p>
						<p>
							<label>Estado: </label>
						</p>
						<p>
							{{ '{{view Ember.Select
									contentBinding="App.status"
									optionValuePath="content.id"
									optionLabelPath="content.state"
									valueBinding="status"}}'
							}}
						</p>
						<p>
							<button class="btn btn-success" {{' {{action save this}} '}}>Grabar</button>
							<button class="btn btn-inverse" {{ '{{action cancel this}}' }}>Cancelar</button>
						</p>	
					</div>
				</div>
			</form>
</script>
