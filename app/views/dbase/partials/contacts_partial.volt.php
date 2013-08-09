<script type="text/x-handlebars" data-template-name="contacts/index">
	<div class="row-fluid">
		<div class="span7">
			<div class="row-fluid">
				<div class="span12">
					<h2>DashBoard Contactos</h2>
					<form>
						<div class="col-lg-6">
							<div class="input-append">
								<input type="text" class="form-control span-2" placeholder="Escriba termino a buscar"/>
								<div class="btn-group">
									<button class="btn btn-primary">Buscar: Email</button>
									<button class="btn btn-primary dropdown-toggle"><span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="#">Buscar: Email</a></li>
										<li><a href="#">Buscar: Nombre</a></li>
										<li><a href="#">Buscar: Apellidos</a></li>
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
							<td><?php echo '{{email}}'; ?></td>
							<td><?php echo '{{name}}'; ?></td>
							<td><?php echo '{{lastName}}'; ?></td>
							<td>
								<dl>
									<dd>
										<?php echo '{{#if status}}'; ?>
											Activo
										<?php echo '{{else}}'; ?>
											Inactivo
										<?php echo '{{/if}}'; ?>
									</dd>
									<dd>
										<?php echo '{{#if isBounced}}'; ?>
											Rebotado
										<?php echo '{{/if}}'; ?>
									</dd>
									<?php echo '{{#if isUnsubscribed}}'; ?>
									<dd>
											Desuscrito
									</dd>
									<?php echo '{{/if}}'; ?>
									<?php echo '{{#if isSpam}}'; ?>
									<dd>
										<span class="red-label">SPAM</span>
									</dd>
									<?php echo '{{/if}}'; ?>
									
								</dl>
							</td>
							<td>
								<dl>
									<dd>Ver</dd>
									<dd><?php echo '{{#linkTo "contacts.edit" this}}Editar{{/linkTo}}'; ?></dd>
									<dd>Eliminar</dd>
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
							<?php echo '{{#if status}}'; ?>
								<label class="checkbox checked" for="status">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
							<?php echo ' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '; ?>  Activo
								</label>
							<?php echo '{{else}}'; ?>
								<label class="checkbox" for="status">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
						 <?php echo ' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '; ?>  Activo
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
							<?php echo '{{#if status}}'; ?>
								<label class="checkbox checked" for="status">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
							<?php echo ' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '; ?>  Activo
								</label>
							<?php echo '{{else}}'; ?>
								<label class="checkbox" for="status">
									<span class="icons">
										<span class="first-icon fui-checkbox-unchecked"></span>
										<span class="second-icon fui-checkbox-checked"></span>
									</span>
						 <?php echo ' {{view Ember.Checkbox  checkedBinding="status" id="status"}} '; ?>  Activo
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