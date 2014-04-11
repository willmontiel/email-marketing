<div {{'{{bind-attr class=":bs-callout headerEmpty:bs-callout-warning:bs-callout-success"}}'}}>
	<div class="panel-body">
		{{'{{#if headerEmpty }}'}}
		<p><strong>De:</strong> _______________________________</p>
		<p><strong>Asunto:</strong> ______________________________ </p>
		{{'{{else}}'}}
		<p><strong>De:</strong> {{' {{fromName}}  &lt; {{fromEmail}} &gt; '}}</p>
		<p><strong>Asunto:</strong> {{'  {{subject}} '}}</p>
		{{'{{/if}}'}}
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Nuevo correo</h3>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" role="form" id="header">
			{{'{{mail.id}}'}}
			<div class="form-group">
				<label for="fromName" class="col-sm-2 control-label">De: </label>
				<div class="col-sm-5">
					{{'{{view Ember.TextField valueBinding="fromName" id="fromName" required="required" autofocus="autofocus" class="form-control"}}'}}
					{#
					<input type="text" class="form-control" name="fromName" id="fromName" placeholder="Enviar desde este nombre">
					#}
				</div>
				<div class="col-sm-5">
					{{'{{view Ember.TextField valueBinding="fromEmail" id="fromEmail" class="form-control"}}'}}
					{#
					<input type="email" class="form-control" name="fromEmail" id="fromEmail" placeholder="Enviar desde esta dirección de correo">
					#}
				</div>
			</div>
			<div class="form-group">
				<label for="replyTo" class="col-sm-2 control-label">Responder a: </label>
				<div class="col-sm-10">
					{{'{{view Ember.TextField valueBinding="replyTo" id="replyTo" class="form-control"}}'}}
					{#
					<input type="text" class="form-control" name="replyTo" id="replyTo" placeholder="Responder a este correo">
					#}
				</div>
			</div>
			<div class="form-group">
				<label for="subject" class="col-sm-2 control-label">Asunto: </label>
				<div class="col-sm-10">
					{{'{{view Ember.TextField valueBinding="subject" id="subject" class="form-control"}}'}}
					{#
					<input type="text" class="form-control" name="subject" id="subject" placeholder="Asunto">
					#}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-md-offset-2">
					<h4>Redes sociales <small>Configure cuentas de facebook y twitter</small></h4>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 col-md-offset-2">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#facebook" data-toggle="tab">Facebook</a></li>
						<li><a href="#twitter" data-toggle="tab">Twitter</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="facebook">
							<br />
							<label>Seleccione una cuenta de facebook, si aún no ha configurado alguna <a href="">haga click aqui </a></label>
							<br />
							<select name="facebookaccounts" id="accounts_facebook" class="form-control">
								<option>Cuenta de facebook 1</option>
								<option>Cuenta de facebook 2</option>
							</select>
							<br />
							<div class="fbdescription" style="display: none">
								<div class="form-group">
									<label for="postTitle" class="col-sm-4 control-label">Titulo de la Publicacion: </label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="postTitle" placeholder="Titulo de la Publicacion">
									</div>
								</div>

								<div class="form-group">
									<label for="postDesc" class="col-sm-4 control-label">Descripcion de la Publicacion: </label>
									<div class="col-sm-8">
									  <input type="text" class="form-control" id="postDesc" placeholder="Descripcion de la Publicacion:">
									</div>
								</div>

								<div class="form-group">
									<label for="postMessage" class="col-sm-4 control-label">Mensaje de la Publicacion: </label>
									<div class="col-sm-8">
										<textarea class="form-control" id="postMessage" rows="5">Mensaje de la Publicacion</textarea>
									</div>
								</div>
							</div>
							<br />
						</div>
						<div class="tab-pane fade" id="twitter">
							<br />
							<label>Seleccione una cuenta de twitter, si aún no ha configurado alguna <a href="">haga click aqui </a></label>
							<br />
							<select name="facebookaccounts" id="accounts_facebook" class="form-control">
								<option>Cuenta de twitter 1</option>
								<option>Cuenta de twitter 2</option>
							</select>
							<br />
							<div class="fbdescription" style="display: none">
								<div class="form-group">
									<label for="postMessage" class="col-sm-4 control-label">Mensaje del tweet: </label>
									<div class="col-sm-8">
										<textarea class="form-control" id="postMessage" rows="5">Mensaje del tweet</textarea>
									</div>
								</div>
							</div>
							<br />
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-md-offset-6 text-right">
					<a href="#" class="btn btn-default">Descartar cambios</a>
					<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
					{#
					<input type="button" class="btn btn-primary" value="Aplicar cambios" onClick="createBlock(this.form, 'header')">
					#}
				</div>
			</div>
		</form>
	</div>
</div>