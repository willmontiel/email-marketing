<script type="text/x-handlebars" data-template-name="contacts">
	<div class="row-fluid">
		<div class="span7">
			<h2>DashBoard Contactos</h2>
			<form class="form-inline">
				<div class="span5">
					<input class="form-search" type="search" value="" placeholder="Buscar">
				</div>
				<div class="span1">
					<button class="btn btn-primary" type="Submit"><span class="fui-search"></span></button>
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
						<th class="span4">
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
							<td>{{'{{status}}'}}</td>
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
</script>


