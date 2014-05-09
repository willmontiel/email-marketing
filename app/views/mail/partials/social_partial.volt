{{'{{#unless isSocialExpanded }}'}}
	<div {{'{{bind-attr class=": socialEmpty: :bg-warning"}}'}}>
		<div class="wrapper">
			<dl class="dl-horizontal" {{ '{{action "expandSocial" this}}' }}>
			{{'{{#if socialEmpty }}'}}

				<dt>Redes sociales:</dt>
				<dd><img src="{{url('')}}b3/images/icon-face-color.png" /> <img src="{{url('')}}b3/images/icon-tweett-color.png" /></dd>

		</div>
		{{'{{else}}'}}
			Esto se va mostrar cuando hay algo configurado
			{{'{{#if fbaccountsel }}'}}
				Entra aqui cuando se configura facebook
				<img src="{{url('images')}}/share_facebook_image_24.png">
			{{'{{/if}}'}}

			{{'{{#if twaccountsel }}'}}
				Entra aqui cuando se configura twitter
				<img src="{{url('images')}}/share_twitter_image_24.png">
			{{'{{/if}}'}}
		{{'{{/if}}'}}
		</dl>
	</div>
{{'{{else}}'}}
	<h4 class="paneltitle">Redes sociales</h4>
			<form class="form-horizontal" role="form" id="header">
				<div class="form-group">
					<div class="col-sm-10 col-md-offset-2">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#facebook" data-toggle="tab"><img src="{{url('b3/images/icon-face-color.png')}}" class="center-block" alt="" /></a></li>
							<li><a href="#twitter" data-toggle="tab"><img src="{{url('b3/images/icon-tweett-color.png')}}" class="center-block" alt="" /></a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="facebook">
								{#
									{% if fbsocials %}
								#}			
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
								{#
								{% else %}
									<div class="wrapper bg bg-warning">
										No tiene una cuenta de facebook configurada, para configurarla haga <span style="text-decoration: underline;" {{' {{action "saveDataAndGoToSocialMedia" "fbloginUrl"}} '}}>click aqui</span>
									</div>	
								{% endif %}
								#}
							</div>
							<div class="tab-pane fade" id="twitter">
								{#
								{% if twsocials %}
								#}
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
								{#
								{% else %}
									<div class="wrapper bg bg-warning">
										No tiene una cuenta de twitter configurada, para configurarla haga <span style="text-decoration: underline;" {{' {{action "saveDataAndGoToSocialMedia" "twloginUrl"}} '}}>click aqui</span>
									</div>	
								{% endif %}
								#}
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
{{ '{{/unless}}' }}