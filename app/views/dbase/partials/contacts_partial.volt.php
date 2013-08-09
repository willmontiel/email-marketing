<script type="text/x-handlebars" data-template-name="contacts/index">
	<div class="row-fluid">
		<div class="span7">
			<div class="row-fluid">
				<div class="span12">
					<h2>Contactos</h2>
					<form>
						<p>
							<?php echo ' {{view Ember.TextField valueBinding="searchText" placeholder="Buscar" autofocus="autofocus"}} '; ?>
							<button class="btn btn-primary" <?php echo '{{action search this}}'; ?>>Buscar</button>
					
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
							<span class="text-green-color"><?php echo number_format($sdbase->Cactive, 0, ',', '.'); ?></span>
						</td>
						<td class="text-left">
							<span class="regular-text">Activos</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-gray-color text-left"><?php echo number_format($sdbase->Cinactive, 0, ',', '.'); ?></span>
						</td>
						<td class="text-left">
							<span class="regular-text">Inactivos</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-gray-color text-left"><?php echo number_format($sdbase->Cunsubscribed, 0, ',', '.'); ?></span>
						</td>	
						<td class="text-left">
							<span class="regular-text">Des-suscritos</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-brown-color text-left"><?php echo number_format($sdbase->Cbounced, 0, ',', '.'); ?></span>
						</td>
						<td class="text-left">
							<span class="regular-text">Rebotados</span>
						</td>
					</tr>
					<tr>
						<td>
							<span class="text-red-color text-left"><?php echo number_format($sdbase->Cspam, 0, ',', '.'); ?></span>
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
				<?php echo '{{#each controller}}'; ?>
					<tbody>
						<tr>
							<td><?php echo '{{#linkTo "contacts.show" this}}{{email}}{{/linkTo}}'; ?></td>
							<td><?php echo '{{name}}'; ?></td>
							<td><?php echo '{{lastName}}'; ?></td>
							<td>
								<dl>
									<dd>
										<?php echo '{{#if isActived}}'; ?>
											<span class="green-label">Activo</span>
										<?php echo '{{else}}'; ?>
											<span class="yellow-label">Inactivo</span>
										<?php echo '{{/if}}'; ?>
									</dd>
									<dd>
										<?php echo '{{#if isBounced}}'; ?>
											Rebotado
										<?php echo '{{/if}}'; ?>
									</dd>
									<?php echo '{{#unless isSubscribed}}'; ?>
									<dd>
											Desuscrito
									</dd>
									<?php echo '{{/unless}}'; ?>
									<?php echo '{{#if isSpam}}'; ?>
									<dd>
										<span class="red-label">SPAM</span>
									</dd>
									<?php echo '{{/if}}'; ?>
									
								</dl>
							</td>
							<td>
								<dl>
									<dd><?php echo '{{#linkTo "contacts.show" this}}Ver{{/linkTo}}'; ?></dd>
									<dd><?php echo '{{#linkTo "contacts.edit" this}}Editar{{/linkTo}}'; ?></dd>
									<dd><?php echo '{{#linkTo "contacts.delete" this}}Eliminar{{/linkTo}}'; ?></dd>
								</dl>
							</td>
						</tr>
					</tbody>
				<?php echo '{{/each}}'; ?>
			 </table>
        </div>
	</div>
	<div class="row-fluid">
		<div class="text-right">
			<?php echo '{{#linkTo "contacts.new"}} <button class="btn btn-primary" >Agregar</button> {{/linkTo}}'; ?>
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="contacts">
	<?php echo '{{outlet}}'; ?>
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
							<?php echo ' {{view Ember.TextField valueBinding="email" placeholder="E-mail" id="email" required="required" autofocus="autofocus"}} '; ?>
						</p>
						<p>
							<label>Nombre: </label>
						</p>
						<p>	
							<?php echo ' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" }} '; ?>
						</p>
						<p>
							<label>Apellido: </label>
						</p>
						<p>
							<?php echo ' {{view Ember.TextField valueBinding="lastName" placeholder="Apellido" id="lastName" required="required"}} '; ?>
						</p>
						<p>
							<label>Estado: </label>
							<?php echo '{{#if isActived}}'; ?>
								<label class="checkbox checked" for="isActived">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
							<?php echo ' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '; ?>  Activo
								</label>
							<?php echo '{{else}}'; ?>
								<label class="checkbox" for="isActived">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
						 <?php echo ' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '; ?>  Activo
								</label>
					<?php echo '{{/if}}'; ?>
						</p>
						<p>
							<button class="btn btn-success" <?php echo ' {{action save this}} '; ?>>Grabar</button>
							<button class="btn btn-inverse" <?php echo '{{action cancel this}}'; ?>>Cancelar</button>
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
					<?php echo ' {{view Ember.TextField valueBinding="email" placeholder="E-mail" id="email" required="required" autofocus="autofocus"}} '; ?>
				</p>
				<p>
					<label>Nombre: </label>
				</p>
				<p>	
					<?php echo ' {{view Ember.TextField valueBinding="name" placeholder="Nombre" id="name" required="required" }} '; ?>
				</p>
				<p>
					<label>Apellido: </label>
				</p>
				<p>
					<?php echo ' {{view Ember.TextField valueBinding="lastName" placeholder="Apellido" id="lastName" required="required"}} '; ?>
				</p>
				<p>
					<label>Estado: </label>
					<?php echo '{{#if isActived}}'; ?>
						<label class="checkbox checked" for="isActived">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
					<?php echo ' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '; ?>  Activo
						</label>
					<?php echo '{{else}}'; ?>
						<label class="checkbox" for="isActived">
							<span class="icons">
								<span class="first-icon fui-checkbox-unchecked"></span>
								<span class="second-icon fui-checkbox-checked"></span>
							</span>
				 <?php echo ' {{view Ember.Checkbox  checkedBinding="isActived" id="isActived"}} '; ?>  Activo
						</label>
			<?php echo '{{/if}}'; ?>
				</p>
				<p>
					<button class="btn btn-success" <?php echo ' {{action edit this}} '; ?>>Editar</button>
					<button class="btn btn-inverse" <?php echo '{{action cancel this}}'; ?>>Cancelar</button>
				</p>	
			</div>
		</div>
	</form>
</script>
<script type="text/x-handlebars" data-template-name="contacts/delete">
	<div class="row-fluid">
		<div class="span5">
			<p>Esta seguro que desea Eliminar el Contacto <strong><?php echo '{{this.name}}'; ?></strong></p>
			<button <?php echo '{{action delete this}}'; ?> class="btn btn-danger">
				Eliminar
			</button>
			<button class="btn btn-inverse" <?php echo '{{action cancel this}}'; ?>>
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
								<?php echo '{{#if isActived}}'; ?>
									<label class="checkbox checked" for="isActived">
										Activo <span class="icons">
											<span class="first-icon fui-checkbox-unchecked"></span>
											<span class="second-icon fui-checkbox-checked"></span>
										</span>
									</label>
								<?php echo '{{else}}'; ?>
									<label class="checkbox" for="isActived">
										Activo <span class="icons">
											<span class="first-icon fui-checkbox-unchecked"></span>
											<span class="second-icon fui-checkbox-checked"></span>
										</span>
									</label> 
								<?php echo '{{/if}}'; ?>	
							</dd>
							<dd>
								Campo:
							</dd>
						</dl>
					</td>
					<td>
						<dl>
							<dd>
								<?php echo '{{email}}'; ?>
							</dd>
							<dd>
								<?php echo '{{name}}'; ?>
							</dd>
							<dd>
								<?php echo '{{lastName}}'; ?>
							</dd>
							<dd>
								<?php echo '{{#if isSubscribed}}'; ?>
									<label class="checkbox checked" for="unSubscribed">
										Suscrito <span class="icons">
											<span class="first-icon fui-checkbox-unchecked"></span>
											<span class="second-icon fui-checkbox-checked"></span>
										</span>
									</label>
								<?php echo '{{else}}'; ?>
									<label class="checkbox" for="unSubscribed">
										Suscrito <span class="icons">
											<span class="first-icon fui-checkbox-unchecked"></span>
											<span class="second-icon fui-checkbox-checked"></span>
										</span>
									</label> 
								<?php echo '{{/if}}'; ?>	
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
			<?php echo '{{#if isActived}}'; ?>
				<button class="btn btn-sm btn-info" <?php echo ' {{action deactivated this}} '; ?>>Desactivar</button>
			<?php echo '{{else}}'; ?>
				<button class="btn btn-sm btn-info" <?php echo ' {{action activated this}} '; ?>>Activar</button>
			<?php echo '{{/if}}'; ?>
			<?php echo '{{#if isSubscribed}}'; ?>
				<button class="btn btn-sm btn-info" <?php echo ' {{action unsubscribedcontact this}} '; ?>>Des-suscribir</button>
			<?php echo '{{else}}'; ?>
				<button class="btn btn-sm btn-info" <?php echo ' {{action subscribedcontact this}} '; ?>>Suscribir</button>
			<?php echo '{{/if}}'; ?>
			
			<?php echo '{{#linkTo "contacts.edit" this}}<button class="btn btn-sm btn-info">Editar</button>{{/linkTo}}'; ?>
			<?php echo '{{#linkTo "contacts"}}<button class="btn btn-sm btn-inverse">Regresar</button>{{/linkTo}}'; ?>
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
							<?php echo '{{createdOn}}'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Direccion IP Suscrito: 
						</td>
						<td>
							<?php echo '{{ipSubscribed}}'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Activado: 
						</td>
						<td>
							<?php echo '{{activatedon}}'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Direccion IP Activado: 
						</td>
						<td>
							<?php echo '{{ipActived}}'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Rebotado: 
						</td>
						<td>
							<?php echo '{{bouncedOn}}'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Reportado Spam: 
						</td>
						<td>
							<?php echo '{{spamOn}}'; ?>
						</td>
					</tr>
					<tr>
						<td>
							Des-suscrito: 
						</td>
						<td>
							<?php echo '{{unsubscribedon}}'; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</script>