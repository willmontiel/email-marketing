{{'{{#unless isHeaderExpanded }}'}}
	{{'{{#unless isTargetExpanded }}'}}
			<div {{'{{bind-attr class=": headerEmpty:bg-warning: "}}'}}>
				<div class="wrapper">
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
							Contactos aproximados: <strong>{{ '{{totalContacts}}' }}</strong> (En el momento del envío podría variar)
						</dd>
					{{'{{/if}}'}}
					</dl>
				</div>	
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
							<li class="active"><a href="#facebook" data-toggle="tab"><img src="{{url('b3/images/icon-face-color.png')}}" class="center-block" alt="" /></a></li>
							<li><a href="#twitter" data-toggle="tab"><img src="{{url('b3/images/icon-teewt-color.png')}}" class="center-block" alt="" /></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="facebook">
								{% if fbsocials %}
								<div class="wrapper">
									{{ '{{view Ember.Select
											multiple="true"
											contentBinding="App.fbaccounts"
											optionValuePath="content.id"
											optionLabelPath="content.name"
											selectionBinding="fbaccountsel"
											id="accounts_facebook"
											class="form-control"}}'
									 }}
								</div>
									<div class="fbdescription wrap wrapper">
										<div class="form-group">
											<div class="col-md-11">
												{{'{{view Ember.TextArea valueBinding="fbmessagecontent" id="fbmessagecontent" class="form-control" placeholder="Comentario..."}}'}}
											</div>
										</div>

										<div class="img-prev">
											<div class="container-fb-first col-md-3">
												<div class="edit-fb-image-container">
													<div data-toggle="modal" data-target="#images" class="edit-fb-image-tool glyphicon glyphicon-pencil"></div>
												</div>
												{{'{{view Ember.TextField valueBinding="fbimagepublication" id="fbimagepublication" class="form-control social-input-hide"}}'}}
												<img id="fb-share-image" src="{{'{{unbound imageUrl}}'}}/{{'{{unbound fbimagepublication}}'}}" width="154" height="154" />
											</div>
											<div class="col-md-9">
												<div class="form-group">
													<div class="col-md-12">
														{{'{{view Ember.TextArea valueBinding="fbtitlecontent" id="fbtitlecontent" class="form-control" placeholder="Título de la publicación..."}}'}}
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-12">
														{{'{{view Ember.TextArea valueBinding="fbdescriptioncontent" id="fbdescriptioncontent" class="form-control" placeholder="Descripción de la publicación..."}}'}}
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								{% else %}
									<div class="wrapper bg bg-warning">
										No tiene una cuenta de facebook configurada
									</div>	
								{% endif %}
							</div>
							<div class="tab-pane fade" id="twitter">
								{% if twsocials %}
									<div class="wrapper">
										{{ '{{view Ember.Select
												multiple="true"
												contentBinding="App.twaccounts"
												optionValuePath="content.id"
												optionLabelPath="content.name"
												selectionBinding="twaccountsel"
												id="accounts_twitter"
												class="form-control"}}'
										 }}
									</div>
									<div class="twdescription wrap wrapper">
										<label>Mensaje del Tweet: </label>
										{{'{{view Ember.TextArea valueBinding="twpublicationcontent" id="twpublicationcontent" class="form-control" placeholder="Tweet..."}}'}}
										<div class="number-of-tweet-characters">
										<span id="tweet-char-number" class="label label-blue">1</span>
										</div>
									</div>
								{% else %}
									<div class="wrapper bg bg-warning">
										No tiene una cuenta de twitter configurada
									</div>	
								{% endif %}
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-6 col-md-offset-6 text-right">
						<button class="btn btn-default btn-sm extra-padding" {{ '{{action "discardChanges" this}}' }}>Descartar cambios</button>
						<button class="btn btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
					</div>
				</div>
			</form>
		</div>
	</div>
{{ '{{/if}}' }}