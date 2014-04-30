{{'{{#unless isHeaderExpanded }}'}}
	{{'{{#unless isTargetExpanded }}'}}
			<div {{'{{bind-attr class=": headerEmpty:bg-warning: "}}'}}>
				<dl class="dl-horizontal" {{ '{{action "expandHeader" this}}' }}>
				{{'{{#if headerEmpty }}'}}
					<dt>De:</dt> <dd>{{'{{fromSummary}}'}} </dd>
					<dt>Asunto:</dt> <dd>_______________________________</dd>
				{{'{{else}}'}}
					<dt>De:</dt> <dd>{{'{{fromSummary}}'}}</dd>
					<dt>Asunto:</dt> <dd>{{'  {{subject}} '}}</dd>
					<dt></dt>
					<dd>
						{{'{{#if fbaccountsel }}'}}
						<img src="{{url('images')}}/share_facebook_image_24.png">
						{{'{{/if}}'}}
						{{'{{#if twaccountsel }}'}}
						<img src="{{url('images')}}/share_twitter_image_24.png">
						{{'{{/if}}'}}
					</dd>
				{{'{{/if}}'}}
				</dl>
				<dl class="dl-horizontal" {{ '{{action "expandTarget" this}}' }}>
				{{'{{#if targetEmpty }}'}}
					<dt>Para:</dt>
					<dd><i>Elija los destinatarios...</i></dd>
				{{'{{else}}'}}
					<dt>Para:</dt>
					<dd>
						{{ '{{#if dbaselist}}' }}
							{{ '{{#each dbaselist}}' }}
								Base de datos: {{' {{name}} '}}, 
							{{ '{{/each}}' }}
						{{ '{{/if}}' }}

						{{ '{{#if list}}' }}
							{{ '{{#each list}}' }}
								Lista de contactos: {{' {{name}} '}}, 
							{{ '{{/each}}' }}
						{{ '{{/if}}' }}

						{{ '{{#if segmentlist}}' }}
							{{ '{{#each segmentlist}}' }}
								Segmento: {{' {{name}} '}}, 
							{{ '{{/each}}' }}
						{{ '{{/if}}' }}
						
						{{ '{{#unless filterEmpty}}' }}
							(filtro)
						{{ '{{/unless}}' }}
					</dd>
					<dd>
						Contactos aproximados: <strong>{{ '{{totalContacts}}' }}</strong> (En el momento del envío podria variar)
					</dd>
				{{'{{/if}}'}}
				</dl>
			</div>
	{{ '{{/unless}}' }}
{{ '{{/unless}}' }}

{{ '{{#if isHeaderExpanded}}' }}
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Encabezado</h3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" id="header">
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
								<div class="add_facebook_account"><label>Seleccione una cuenta de facebook, si aún no ha configurado alguna <a onclick="new_sn_account('{{fbloginUrl}}')">haga click aqui</a></label></div>
								<br />
								{#<select name="facebookaccounts" id="accounts_facebook" class="form-control">
									<option>Cuenta de facebook 1</option>
									<option>Cuenta de facebook 2</option>
								</select>#}
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.fbaccounts"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="fbaccountsel"
										id="accounts_facebook"
										class="form-control"}}'
								 }}
								<div class="fbdescription bs-callout bs-callout-info">
									<div>
										{{'{{view Ember.TextArea valueBinding="fbmessagecontent" id="fbmessagecontent" class="form-control" placeholder="Haz un comentario..."}}'}}
									</div>
									<br>
									<div class="clearfix">
										<div style="float: left;margin-right: 15px;width: 154px;height: 154px;background-color: #FAFAFA;">
											<div>
												<div data-toggle="modal" data-target="#images" class="edit-fb-image-tool glyphicon glyphicon-pencil" style="position: relative;left: 2px;top: 4px;padding: 2px;border-radius: 4px;cursor: pointer;border: 1px solid #E4E4E4;background-color: #F5F5F5;"></div>
											</div>
											{{'{{view Ember.TextField valueBinding="fbimagepublication" id="fbimagepublication" class="form-control social-input-hide"}}'}}
											<img id="fb-share-image" src="{{'{{unbound imageUrl}}'}}/{{'{{unbound fbimagepublication}}'}}" width="154" height="154">
										</div>
										<div style="float: left;width: 67%;">
											<br>
											{{'{{view Ember.TextArea valueBinding="fbtitlecontent" id="fbtitlecontent" class="form-control" placeholder="Da un titulo a tu publicacion..."}}'}}
											<br>
											{{'{{view Ember.TextArea valueBinding="fbdescriptioncontent" id="fbdescriptioncontent" class="form-control" placeholder="Describe tu publicacion..."}}'}}
										</div>
									</div>
								</div>
								<br />
							</div>
							<div class="tab-pane fade" id="twitter">
								<br />
								<div class="add_twitter_account"><label>Seleccione una cuenta de twitter, si aún no ha configurado alguna <a onclick="new_sn_account('{{twloginUrl}}')">haga click aqui</a></div>
								<br />
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.twaccounts"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="twaccountsel"
										id="accounts_twitter"
										class="form-control"}}'
								 }}
								<br />
								<div class="twdescription bs-callout bs-callout-info">
									<label>Mensaje del Tweet: </label>
									{{'{{view Ember.TextArea valueBinding="twpublicationcontent" id="twpublicationcontent" class="form-control" placeholder="Escribe tu tweet..."}}'}}
									<div class="number-of-tweet-characters">
									<span id="tweet-char-number" class="label label-blue">1</span>
									</div>
								</div>
								<br />
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6 col-md-offset-6 text-right">
						<button class="btn btn-default" {{ '{{action "discardChanges" this}}' }}>Descartar cambios</button>
						<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
						{#
						<input type="button" class="btn btn-primary" value="Aplicar cambios" onClick="createBlock(this.form, 'header')">
						#}
					</div>
				</div>
			</form>
		</div>
	</div>
{{ '{{/if}}' }}