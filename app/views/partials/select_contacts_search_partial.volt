<div class="row frame-bg-pd">
	<div class="col-md-3">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle no-bg" data-toggle="dropdown">
			Marcar <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="#">Todos</a></li>
				<li><a href="#">Activos</a></li>
				<li><a href="#">Suscritos</a></li>
				<li><a href="#">Rebotados</a></li>
			</ul>
		</div>
	</div>
	<div class="col-md-3">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle no-bg" data-toggle="dropdown">
			Marcar <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="#">Todos</a></li>
				<li><a href="#">Activos</a></li>
				<li><a href="#">Suscritos</a></li>
				<li><a href="#">Rebotados</a></li>
			</ul>
		</div>
	</div>
		{#{{' {{ view App.DropDownSelect }} '}}#}
	<div class="col-md-3">
		<div class="btn-group">
			Mostrar: 
			{{'{{
				view Ember.Select
				content=filters
				optionValuePath="content.value"
				optionLabelPath="content.name"
				value=filter.value
				class="span6"
			}}'}}
		</div>
	</div>
</div>
