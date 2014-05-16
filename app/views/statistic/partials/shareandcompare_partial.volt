<div class="space"></div>
	<div class="row">
		<div class="col-md-5">
			{{ '{{view Ember.Select
				class="form-control"
				id="select-options-for-compare"
				contentBinding="App.mails"
				optionValuePath="content.id"
				optionLabelPath="content.name"
				valueBinding="App.mailCompare"}}'
			}}
		</div>
		<div class="col-md-2">
			<button class="btn btn-blue" onclick="compareMails()">Comparar</button>
		</div>
		<div class="col-md-5 text-right">
			<button id="sharestats-{{mail.idMail}}" type="button" class="btn btn-sm btn-default btn-add extra-padding" data-container="body" data-toggle="popover" data-placement="left" data-idmail="{{mail.idMail}}">Compartir estadísticas</button>
		</div>
	</div>
<div class="space"></div>