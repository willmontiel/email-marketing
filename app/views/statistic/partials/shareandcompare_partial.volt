<div class="row">
	<hr>
	<div class="space"></div>
	<div class="col-md-4 mtop-neg">
		<p class="hand-writer-message">Compare las estadísticas de este envío con cualquier otro...</p>
	</div>
	<div class="col-md-4">
		{{ '{{view Ember.Select
			class="form-control"
			id="select-options-for-compare"
			contentBinding="App.mails"
			optionValuePath="content.id"
			optionLabelPath="content.name"
			valueBinding="App.mailCompare"}}'
		}}
	</div>
	<div class="col-md-2 ptop-3">
		<button class="btn btn-sm btn-default extra-padding" onclick="compareMails();">Comparar</button>
	</div>
	<div class="col-md-2 text-right">
		<button class="btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple" onClick="getUrlForStatistics({{mail.idMail}});">
			Compartir estadísticas
		</button>
	</div>
<div class="space"></div>
