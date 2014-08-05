{{'{{#unless isSocialExpanded }}'}}
<div {{'{{bind-attr class=": socialEmpty: :bg-warning"}}'}}>
		{{'{{#if socialEmpty }}'}}
		<div class="wrapper">
			<dl class="dl-horizontal" {{ '{{action "expandSocial" this}}' }}>
				<dt>Redes sociales:</dt>
				<dd>Inactivas</dd>
			</dl>
		</div>
		{{'{{else}}'}}
		<div class="wrapper">
			<dl class="dl-horizontal" {{ '{{action "expandSocial" this}}' }}>
					{{'{{#if fbaccountsel }}'}}
					<dt>Cuenta de Facebook activa:</dt>
					<dd>
						<img src="{{url('images')}}/facebook_icon_active.png">
					</dd>
					{{'{{/if}}'}}

					{{'{{#if twaccountsel }}'}}
					<dt>Cuenta de Twitter activa:</dt>
					<dd>
						<img src="{{url('images')}}/twitter_icon_active.png">
					</dd>
					{{'{{/if}}'}}
				</dl>
			</div>
		{{'{{/if}}'}}
		</div>
{{'{{else}}'}}
		<h4 class="paneltitle">Redes sociales</h4>
		<form class="form-horizontal" role="form" id="header">
			<div class="form-group">
				<div class="col-sm-10 col-md-offset-2">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#facebook" data-toggle="tab"><img src="{{url('b3/images/facebook_icon_active.png')}}" class="center-block" alt="" /></a></li>
						<li><a href="#twitter" data-toggle="tab"><img src="{{url('b3/images/twitter_icon_active.png')}}" class="center-block" alt="" /></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="facebook">
						{% if fbsocials %}
							<div class="fbdescription wrapper">
								<div class="form-group">
									<div class="col-sm-12">
										{{'{{view Ember.TextArea valueBinding="fbmessagecontent" id="fbmessagecontent" class="form-control" placeholder="Comentario..."}}'}}
									</div>
								</div>
									
								<div class="form-group">
									<div class="col-sm-4">
										<div class="img-post-face">
											<div data-toggle="modal" data-target="#images" class="edit-fb-image-tool glyphicon glyphicon-pencil"></div>
											{{'{{view Ember.TextField valueBinding="fbimagepublication" id="fbimagepublication" class="form-control social-input-hide"}}'}}
											<img id="fb-share-image" align="middle" src="{{'{{unbound imageUrl}}'}}/{{'{{unbound fbimagepublication}}'}}" width="170" height="150" />
										</div>
									</div>
									<div class="col-sm-8">
										{{'{{view Ember.TextArea 
												valueBinding="fbtitlecontent" 
												id="fbtitlecontent" 
												class="form-control" 
												placeholder="Título de la publicación..."}}'
										}}
										<br />
										{{'{{view Ember.TextArea 
												valueBinding="fbdescriptioncontent" 
												id="fbdescriptioncontent" 
												class="form-control" 
												placeholder="Descripción de la publicación..."}}'
										}}
									</div>
								</div>
									
								<div class="clearfix"></div>
							</div>
						{% else %}
							<div class="wrapper bg bg-warning">
								No tiene una cuenta de facebook configurada, para configurarla haga <span style="text-decoration: underline;" {{'{{action gotosocial this "fbloginurl"}}'}}>click aqui</span>
							</div>	
						{% endif %}
						</div>
						
						<div class="tab-pane fade" id="twitter">
						{% if twsocials %}
							<div class="twdescription wrapper">
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.twaccounts"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="twaccountsel"
										id="accounts_twitter"
										class="form-control"}}'
								 }}

								<div class="space"></div>

								<label>Mensaje del Tweet: </label>
								{{'{{view Ember.TextArea 
										valueBinding="twpublicationcontent" 
										id="twpublicationcontent" 
										class="form-control" 
										placeholder="Tweet..."}}'
								}}
								<div class="number-of-tweet-characters">
									<span id="tweet-char-number" class="label label-blue">1</span>
								</div>
							</div>
						{% else %}
							<div class="wrapper bg bg-warning">
								No tiene una cuenta de twitter configurada, para configurarla haga <span style="text-decoration: underline;" {{'{{action gotosocial this "twloginurl"}}'}}>click aqui</span>
							</div>	
						{% endif %}
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-md-offset-6 text-right">
					{#
					<button class="btn btn-default" {{'{{action "cleanSocial" this}}'}}>Limpiar Cambios</button>
					#}
					<button class="btn btn-default btn-sm extra-padding" {{ '{{action "discardChanges" this}}' }}>Descartar cambios</button>
					<button class="btn btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
				</div>
			</div>
		</form>
	</div>
</div>
{{ '{{/unless}}' }}
