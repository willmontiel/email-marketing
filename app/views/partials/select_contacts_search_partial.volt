{#   seleccion de contactos a mostrar  #}
<div class="row frame-bg-pd">
	<div class="col-md-3">
		{#{{' {{ view App.DropDownSelect }} '}}#}
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label for=""  class="col-sm-4 control-label">Mostrar</label>
				<div class="col-md-8">
					{{'{{
					view Ember.Select
					content=filters
					optionValuePath="content.value"
					optionLabelPath="content.name"
					value=filter.value
					class="form-control"
					}}'}}
				</div>
			</div>
		</div>
	</div>
</div>
