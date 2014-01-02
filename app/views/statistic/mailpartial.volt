<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<hr />
	<div class="row-fluid">
		<div class="news span3">
			<label class="label-gray-light-percent"><i class="icon-search"></i> Resumen de aperturas</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-green-left"></td>
							<td class="border-radious-green-center">{{statisticsData.opens}}</td>
							<td class="border-radious-green-center">|</td>
							<td class="border-radious-green-right">{{statisticsData.statopens}}%</td>
						</tr>
					</table>
				</label>
			</div>
			<div class="titleMail">
				<h4 class="openColor">Aperturas</h4>
			</div>
		</div>
		<div class="span1">
		</div>
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="Bar" textChart="Aperturas de Correo"}}'}}
	</div>
	<div class="row-fluid">
		<div class="span12">
			<a href="{{url('statistic/downloadreport')}}/{{idMail}}/opens" class="btn btn-default"><i class="icon-download-alt"></i> Descargar reporte</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Lista de aperturas
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Fecha y hora</td>
								<td>Dirección de correo</td>
								<td>Sistema operativo?</td>
							</tr>
						</thead>
						<tbody>
						{{'{{#each detailsData}}'}}
							<tr>
								<td>{{'{{date}}'}}</td>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{os}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	<hr />
	<div class="row-fluid">
		<div class="news span3">
			<label class="label-gray-light-percent"><i class="icon-hand-up"></i> Resumen de clics</label>
			<div>
				<label class="label-click-percent">
					<table>
						<tr>
							<td></td>
							<td>{{statisticsData.clicks}}</td>
							<td>|</td>
							<td>{{statisticsData.statclicks}}%</td>
							<td>Clics Unicos</td>
						</tr>
					</table>
				</label>
				<label class="label-click-percent">
					<table>
						<tr>
							<td></td>
							<td>{{statisticsData.totalclicks}}</td>
							<td>|</td>
							<td>{{statisticsData.stattotalclicks}}%</td>
							<td>Clics Totales</td>
						</tr>
					</table>
				</label>
				<label class="label-click-percent">
					<table>
						<tr>
							<td></td>
							<td>{{statisticsData.statCTRclicks}}%</td>
							<td>Tasa de Clics</td>
						</tr>
					</table>
				</label>
			</div>
			
		</div>
		{{'{{view App.TimeGraphView idChart="clickPieChartContainer" typeChart="Bar" textChart="Clics en"}}'}}
	</div>
	<div class="row-fluid">
		<div class="clickstotalsandunique span6">
			<div class="box">
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Vinculos</td>
								<td>Total Clics</td>
								<td>Total Clics Unicos</td>
							</tr>
						</thead>
						<tbody>
							{{'{{#each detailsLinks}}'}}
							<tr>
								<td>{{'{{link}}'}}</td>
								<td>{{'{{total}}'}}</td>
								<td>{{'{{uniques}}'}}</td>
							</tr>
							{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="span6">
			<a href="{{url('statistic/downloadreport')}}/{{idMail}}/clicks" class="btn btn-default"><i class="icon-download-alt"></i> Descargar reporte</a>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="pull-left">
				{{ '{{view Ember.Select
						contentBinding="selectedLink"
						valueBinding="linkSelected"}}'
				}}
			</div>
			<div class="box">
				<div class="box-header">
					<div class="title">
						Lista de clicks
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Dirección de correo</td>
								<td>Enlace</td>
								<td>Fecha y hora</td>
							</tr>
						</thead>
						<tbody>
						{{'{{#each detailsData}}'}}
							<tr>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{link}}'}}</td>
								<td>{{'{{date}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	<hr />
	<div class="row-fluid">
		<div class="news span3">
			<label class="label-gray-light-percent"><i class="icon-minus-sign"></i> Resumen de des-suscritos</label>
			<div>
				<label>
					<table>
						<tr>
							<td class="border-radious-gray-left"></td>
							<td class="border-radious-gray-center">{{statisticsData.unsubscribed}}</td>
							<td class="border-radious-gray-center">|</td>
							<td class="border-radious-gray-right">{{statisticsData.statunsubscribed}}%</td>
						</tr>
					</table>
				</label>
			</div>
			<div class="titleMail">
				<h4 class="unsubscribedColor">Des-suscritos</h4>
			</div>
		</div>
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Des-suscritos de Correo"}}'}}
	</div>
	<div class="row-fluid">
		<div class="span6">
			<a href="{{url('statistic/downloadreport')}}/{{idMail}}/unsubscribed" class="btn btn-default"><i class="icon-download-alt"></i> Descargar reporte</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Lista de Des-suscritos
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Dirección de correo</td>
								<td>Nombre</td>
								<td>Apellido</td>
								<td>Fecha y hora</td>
							</tr>
						</thead>
						<tbody>
						{{'{{#each detailsData}}'}}
							<tr>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{name}}'}}</td>
								<td>{{'{{lastname}}'}}</td>
								<td>{{'{{date}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/spam">
	<hr />
	<div class="row-fluid">
		<div class="news span3">
			<label class="label-gray-light-percent"><i class="icon-remove"></i> Resumen de spam</label>
			<div>
				<label>
					<table>
						<tr>
							<td class="border-radious-red-left"></td>
							<td class="border-radious-red-center">{{statisticsData.spam}}</td>
							<td class="border-radious-red-center">|</td>
							<td class="border-radious-red-right">{{statisticsData.statspam}}%</td>
						</tr>
					</table>
				</label>
			</div>
			<div class="titleMail">
				<h4 class="spamColor">Spam</h4>
			</div>
		</div>
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Reportes de Spam"}}'}}
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Lista de Spam
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Dirección de correo</td>
								<td>Nombre</td>
								<td>Apellido</td>
								<td>Fecha y hora</td>
							</tr>
						</thead>
						<tbody>
						{{'{{#each detailsData}}'}}
							<tr>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{name}}'}}</td>
								<td>{{'{{lastname}}'}}</td>
								<td>{{'{{date}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/bounced">
	<hr />
	<div class="row-fluid">
		<div class="news span3">
			<label class="label-gray-light-percent"><i class="icon-warning-sign"></i> Resumen de rebotes</label>
			<div>
				<label>
					<table>
						<tr>
							<td class="border-radious-scarlet-left"></td>
							<td class="border-radious-scarlet-center">{{statisticsData.softbounced}}</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-center">{{statisticsData.statsoftbounced}}%</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-right">Suaves</td>	
						</tr>
					</table>
				</label>
				<label>
					<table>
						<tr>
							<td class="border-radious-scarlet-left"></td>
							<td class="border-radious-scarlet-center">{{statisticsData.hardbounced}}</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-center">{{statisticsData.stathardbounced}}%</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-right">Duros</td>
						</tr>
					</table>
				</label>
				<label>
					<table>
						<tr>
							<td class="border-radious-scarlet-left"></td>
							<td class="border-radious-scarlet-center">{{statisticsData.otherbounced}}</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-center">{{statisticsData.statotherbounced}}%</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-right">Otros</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Rebotes"}}'}}
	</div>
	<div class="row-fluid">
		<div class="pull-left scaleChart">
			<div class="pull-left">
				Filtrar por: &nbsp;
			</div>
			<div class="pull-left">
				<label for="typeFilter">
					{{'{{view Ember.RadioButton id="typeFilter" name="filterBounced" selectionBinding="bouncedFilter" value="type" checked="checked"}}'}}
					Tipo &nbsp;
				</label>
			</div>
			<div class="pull-left">
				<label for="categoryFilter">
					{{'{{view Ember.RadioButton id="categoryFilter" name="filterBounced" selectionBinding="bouncedFilter" value="category"}}'}}
					Categoria &nbsp;
				</label>
			</div>
			<div class="pull-left">
				<label for="domainFilter">
					{{'{{view Ember.RadioButton id="domainFilter" name="filterBounced" selectionBinding="bouncedFilter" value="domain"}}'}}
					Dominio &nbsp;
				</label>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="pull-left">
				{{ '{{view Ember.Select
						contentBinding="selectedType"
						valueBinding="typeSelected"}}'
				}}
			</div>
			<div class="box">
				<div class="box-header">
					<div class="title">
						Lista de Rebotes
					</div>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead>
							<tr>
								<td>Dirección de correo</td>
								<td>Fecha y hora</td>
								<td>Tipo</td>
								<td>Categoria</td>
							</tr>
						</thead>
						<tbody>
						{{'{{#each detailsData}}'}}
							<tr>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{date}}'}}</td>
								<td>{{'{{type}}'}}</td>
								<td>{{'{{category}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>

