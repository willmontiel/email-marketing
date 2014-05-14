{{ '{{#if isContentAvailable}}' }}
	{{ '{{#if contentEmpty}}' }}
		<h4 class="paneltitle">Contenido del correo</h4>
		<div id="choose-content" style="display: block;">
			<div class="row">
				<div class="col-md-3 text-center">
					{{' {{#external-link content=this pattern="' ~ url('mail/contenteditor') ~ '/%@" }}'}}
						<img src="{{url('b3/images/icon-edit-avanz.png')}}" class="" alt=""><br>Editor avanzado
					{{'{{/external-link}}'}}
				</div>
				<div class="col-md-3 text-center">
					{{' {{#external-link content=this pattern="' ~ url('template/select') ~ '/%@" }}'}}
						<img src="{{url('b3/images/icon-template.png')}}" class="" alt=""><br>Plantillas<br>predeterminadas
					{{'{{/external-link}}'}}
				</div>
				<div class="col-md-3 text-center">
					{{' {{#external-link content=this pattern="' ~ url('mail/contenthtml') ~ '/%@" }}'}}
						<img src="{{url('b3/images/icon-html.png')}}" class="" alt=""><br>HTML
					{{'{{/external-link}}'}}
				</div>
				<div class="col-md-3 text-center">
					{{' {{#external-link content=this pattern="' ~ url('mail/importcontent') ~ '/%@" }}'}}
						<img src="{{url('b3/images/icon-url.png')}}" class="" alt=""><br>Importar desde url
					{{'{{/external-link}}'}}
				</div>
			</div>
		</div>		
		
		<div class="row" id="plaintext-content" style="display: none;">
			<div class="col-md-12">
				<div >
					<h4>Texto plano</h4>
					{{ '{{view Ember.TextArea valueBinding="plainText" name="plainText" id="plainText" rows="10" class="col-sm-12"}}' }}
				</div>
			</div>	
		</div>
		<br />
		<div class="row" id="buttons-content" style="display: none;">
			<div class="col-md-12  text-right">
				<a href="#" class="btn btn-default btn-sm extra-padding">Descartar cambios</a>
				<button class="btn btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
			</div>
		</div>
	{{ '{{else}}' }}
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<h4>Preview</h4>
						{{ '{{#if isEditor}}' }}
							<a href="{{url('mail/contenteditor')}}/{{'{{unbound id}}'}}" class="thumbnail">
						{{ '{{else}}' }}
							<a href="{{url('mail/contenthtml')}}/{{'{{unbound id}}'}}" class="thumbnail">
						{{ '{{/if}}' }}
							<img data-src="holder.js/100%x180" alt="100%x180" src="{{'{{unbound contentSummary}}'}}">
						</a>
					</div>
					<div class="col-md-9">
						<div class="row">
							<div class="col-sm-12">
								<h4>Texto plano</h4>
								{{ '{{view Ember.TextArea valueBinding="plainText" id="plainText" class="form-control" rows="11"}}' }}
							</div>
						</div>
					</div>
				</div>
				<div class="wrapper">
					<div class="col-md-12 text-right">
						<button class="btn btn-default btn-sm extra-padding" {{'{{action "discardChanges" this}}'}}>Descartar cambios</button>
						<button class="btn btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
					</div>
				</div>
			</div>
		</div>
	{{'{{/if}}'}}
{{ '{{/if}}' }}