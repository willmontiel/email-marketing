{{ '{{#unless contentEmpty}}' }}
	{{ '{{#unless isGoogleAnalitycsExpanded}}' }}
		<div {{'{{bind-attr class=": GoogleAnalitycsEmpty:bg-warning:"}}'}} style="cursor: pointer;">
			<div class="wrapper">
				<dl class="dl-horizontal" {{ '{{action "expandGA" this}}' }}>
					<dt>Google Analitycs:</dt><dd>{{ '{{summaryAnalytics}}' }}</dd>
				</dl>
			</div>
		</div>
	{{ '{{else}}' }}
		{{ '{{#if isGaAvailable}}' }}
			<div class="row wrapper">
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
							{{ '{{view App.Select2
									multiple="true"
									contentBinding="App.googleAnalyticsLinks"
									optionValuePath="content.name"
									optionLabelPath="content.name"
									selectionBinding="linksAnalytics"
									id="linksAnalitycs"
									class="select2view select2"}}'
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
			</div>
		{{ '{{else}}' }}
			<div class="row wrapper bg-danger" style="margin-right: 0px !important; margin-left: 0px !important;">
				<div class="col-md-8">
					<p>No se encontraron enlaces en el contenido html, para continuar agregue al menos uno.</p>
				</div>
				<div class="col-md-4 text-right">
					<button class="btn btn-default btn-sm extra-padding" {{ '{{action "expandGA" this}}' }}>Regresar</button>
				</div>
			</div>
		{{ '{{/if}}' }}
	{{ '{{/unless}}' }}
{{ '{{/unless}}' }}