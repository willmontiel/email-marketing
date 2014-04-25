{{ '{{#unless contentEmpty}}' }}
	{{ '{{#unless isGoogleAnalitycsExpanded}}' }}
		<div {{'{{bind-attr class=":bs-callout GoogleAnalitycsEmpty:bs-callout-warning:bs-callout-success"}}'}}>
			<div class="panel-body">
				<dl class="dl-horizontal" {{ '{{action "expandGA" this}}' }}>
					<dt>Google Analitycs:</dt><dd>{{ '{{summaryAnalytics}}' }}</dd>
				</dl>
			</div>
		</div>
	{{ '{{else}}' }}
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						{{ '{{#if isGaAvailable}}' }}
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label for="target" class="col-sm-4 control-label">Nombre de la campaña: </label>
									<div class="col-sm-8">
										{{ '{{view Ember.TextField valueBinding="campaignName" id="campaignName" required="required" autofocus="autofocus" class="form-control"}}'}}
									</div>
								</div>
								<div class="form-group">
									<label for="target" class="col-sm-4 control-label">Agregar seguimiento de Google Analitycs a los siguientes enlaces: </label>
									<div class="col-sm-8">
										{{ '{{view Ember.Select
												multiple="true"
												contentBinding="App.googleAnalyticsLinks"
												optionValuePath="content.name"
												optionLabelPath="content.name"
												selectionBinding="linksAnalytics"
												id="linksAnalitycs"
												class="form-control"}}'
										 }}
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12 text-right">
										<button class="btn btn-default" {{'{{action "cleanGoogleAnalytics" this}}'}}>Limpiar Cambios</button>
										<button class="btn btn-default" {{'{{action "discardGoogleAnalytics" this}}'}}>Descartar cambios</button>
										<button class="btn btn-primary" {{'{{action "save" this}}'}}>Aplicar cambios</button>
									</div>
								</div>
							</form>
						{{ '{{else}}' }}
							No se encontrarón enlaces en el contenido html, para empezar agregue al menos uno.
							<br />
							<div class="text-right">
								<button class="btn btn-default" {{ '{{action "expandGA" this}}' }}>Regresar</button>
							</div>
						{{ '{{/if}}' }}
					</div>
				</div>
			</div>
		</div>
	{{ '{{/unless}}' }}
{{ '{{/unless}}' }}