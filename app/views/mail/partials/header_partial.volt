{{'{{#unless isHeaderExpanded }}'}}
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
				</div>	
			</div>
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
						{{'{{view Ember.TextField valueBinding="fromEmail" id="fromEmail" placeholder="Enviar desde esta dirección de correo" required="required" class="form-control"}}'}}
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
						{{'{{view Ember.TextField valueBinding="subject" id="subject" placeholder="Asunto" class="form-control" required="required"}}'}}
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