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
	<h4 class="paneltitle">Encabezado</h4>
			<form class="form-horizontal" role="form" id="header">
				<div class="form-group">
					<label for="fromName" class="col-sm-2 control-label">De: </label>
					<div class="col-sm-4">
						{{'{{view Ember.TextField valueBinding="fromName" id="fromName" placeholder="Enviar desde este nombre" required="required" autofocus="autofocus" class="form-control"}}'}}
					</div>
					<label for="fromName" class="col-sm-2 control-label">Email: </label>
					<div class="col-sm-4">
						{{'{{view Ember.TextField valueBinding="fromEmail" id="fromEmail" placeholder="Enviar desde esta dirección de correo" class="form-control"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="replyTo" class="col-sm-2 control-label">Responder a: </label>
					<div class="col-sm-10">
						{{'{{view Ember.TextField valueBinding="replyTo" id="replyTo" placeholder="Responder a este correo" class="form-control"}}'}}
					</div>
				</div>
				<div class="form-group">
					<label for="subject" class="col-sm-2 control-label">Asunto: </label>
					<div class="col-sm-10">
						{{'{{view Ember.TextField valueBinding="subject" id="subject" placeholder="Asunto" class="form-control"}}'}}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6 col-md-offset-2">
						<h4>Redes sociales <small>Comparta en facebook y twitter</small></h4>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-10 col-md-offset-2">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#facebook" data-toggle="tab"><img src="{{url('')}}b3/images/icon-face-color.png" class="center-block" alt="" /></a></li>
							<li><a href="#twitter" data-toggle="tab"><img src="{{url('')}}b3/images/icon-teewt-color.png" class="center-block" alt="" /></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="facebook">
								<br />
								{#
								{% if fbsocials %}
								#}
									<div class="add_facebook_account"><label>Seleccione una cuenta de facebook, si aún no ha configurado alguna <a onclick="new_sn_account('{{fbloginUrl}}')">haga click aqui</a></label></div>
									<br />
									{{ '{{view Ember.Select
											multiple="true"
											contentBinding="App.fbaccounts"
											optionValuePath="content.id"
											optionLabelPath="content.name"
											selectionBinding="fbaccountsel"
											id="accounts_facebook"
											class="form-control"}}'
									 }}
									<div class="fbdescription bg-info">
										<div class="pad-marg">
											<div>
												{{'{{view Ember.TextArea valueBinding="fbmessagecontent" id="fbmessagecontent" class="form-control" placeholder="Comentario..."}}'}}
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
													{{'{{view Ember.TextArea valueBinding="fbtitlecontent" id="fbtitlecontent" class="form-control" placeholder="Título de la publicación..."}}'}}
													<br>
													{{'{{view Ember.TextArea valueBinding="fbdescriptioncontent" id="fbdescriptioncontent" class="form-control" placeholder="Descripción de la publicación..."}}'}}
												</div>
											</div>
										</div>
									</div>
									<br />
								{#
								{% else %}
									No tiene una cuenta de facebook configurada, si desea puede configurar una.
								{% endif %}
								#}
							</div>
							<div class="tab-pane fade" id="twitter">
								{% if twsocials %}
									<br />
									<div class="add_twitter_account"><label>Seleccione una cuenta de twitter, si aún no ha configurado alguna <a onclick="new_sn_account('{{twloginUrl}}')">haga click aquí</a></div>
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
									<div class="twdescription">
										<label>Mensaje del Tweet: </label>
										{{'{{view Ember.TextArea valueBinding="twpublicationcontent" id="twpublicationcontent" class="form-control" placeholder="Tweet..."}}'}}
										<div class="number-of-tweet-characters">
										<span id="tweet-char-number" class="label label-blue">1</span>
										</div>
									</div>
									<br />
								{% else %}
									No tiene una cuenta de twitter configurada, si desea puede configurar una.
								{% endif %}
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6 col-md-offset-6 text-right">
						<button class="btn btn-default btn-sm extra-padding" {{ '{{action "discardChanges" this}}' }}>Descartar cambios</button>
						<button class="btn btn-default btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
					</div>
				</div>
			</form>
		</div>
	</div>
{{ '{{/if}}' }}