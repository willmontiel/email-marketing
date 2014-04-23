{{ '{{#if isContentAvailable}}' }}
	{{ '{{#if contentEmpty}}' }}
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					  <h3 class="panel-title">Cree el contenido del correo</h3>
					</div>
					<div class="panel-body">
						<div id="choose-content" style="display: block;">
							<div class="row">
								<div class="col-md-3 text-center">
									{{' {{#external-link content=this pattern="' ~ url('mail/contenteditor') ~ '/%@" class="btn btn-primary btn-lg"}}'}}
										<span class="glyphicon glyphicon-star"></span> Editor Avanzado
									{{'{{/external-link}}'}}
								</div>
								<div class="col-md-3 text-center">
									{{' {{#external-link content=this pattern="' ~ url('template/select') ~ '/%@" class="btn btn-default btn-lg"}}'}}
										<span class="glyphicon glyphicon-star"></span> Plantillas prediseñadas
									{{'{{/external-link}}'}}
								</div>
								<div class="col-md-3 text-center">
									{{' {{#external-link content=this pattern="' ~ url('mail/contenthtml') ~ '/%@" class="btn btn-default btn-lg"}}'}}
										<span class="glyphicon glyphicon-star"></span> Html desde cero
									{{'{{/external-link}}'}}
								</div>
								<div class="col-md-3 text-center">
									{{' {{#external-link content=this pattern="' ~ url('mail/importcontent') ~ '/%@" class="btn btn-default btn-lg"}}'}}
										<span class="glyphicon glyphicon-star"></span> Importar desde una url
									{{'{{/external-link}}'}}
								</div>
							</div>
						</div>		
						<br />
						<div class="row">
							<div class="col-md-12">
								<div id="show-content"></div>
							</div>
						</div>
						<br />
						<div class="row" id="plaintext-content" style="display: none;">
							<div class="col-md-12">
								<div  >
									<h4>Texto plano</h4>
									{{ '{{view Ember.TextArea valueBinding="plainText" name="plainText" id="plainText" rows="10" class="col-sm-12"}}' }}
								</div>
							</div>	
						</div>
						<br />
						<div class="row" id="buttons-content" style="display: none;">
							<div class="col-md-12  text-right">
								<a href="#" class="btn btn-default">Descartar cambios</a>
								<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	{{ '{{else}}' }}
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-3">
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
										<label>Texto plano</label> <br />
										{{ '{{view Ember.TextArea valueBinding="plainText" id="plainText" class="form-control" rows="11"}}' }}
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<button class="btn btn-default" {{'{{action "discardChanges" this}}'}}>Descartar cambios</button>
								<button class="btn btn-primary" {{'{{action "save" this}}'}}>Aplicar cambios</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	{{'{{/if}}'}}
{{ '{{/if}}' }}