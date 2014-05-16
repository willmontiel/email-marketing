<div class="row">
	<hr>
	<div class="space"></div>
	<div class="col-md-3">
		<img src="{{url('')}}b3/images/hand-write-compare.png" class="mtop-neg" alt="banner" />
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
		<button class="btn btn-sm btn-default extra-padding" onclick="compareMails()">Comparar</button>
	</div>
	<div class="col-md-3 text-right">
		<button id="sharestats-{{mail.idMail}}" type="button" class="btn btn-sm btn-default btn-add extra-padding" data-container="body" data-toggle="popover" data-placement="left" data-idmail="{{mail.idMail}}">Compartir estadísticas</button>
	</div>
<div class="space"></div>