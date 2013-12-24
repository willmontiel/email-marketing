<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<hr />
	<div class="row-fluid">
		<div class="news span3">
			<label class="label-gray-light-percent"><i class="icon-search"></i> Resumen de aperturas</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td></td>
							<td>{{statisticsData.opens}}</td>
							<td>|</td>
							<td>{{statisticsData.statopens}}%</td>
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
		<div class="pull-right scaleChart">
			<div class="pull-left">
				Agrupar por: &nbsp;
			</div>
			<div class="pull-right">
				<label for="scaleHour">
					{{'{{view Ember.RadioButton id="scaleHour" name="scale" selectionBinding="App.scaleSelected" value="hh"}}'}}
					Hora &nbsp;
				</label>
			</div>
			<div class="pull-right">
				<label for="scaleDay">
					{{'{{view Ember.RadioButton id="scaleDay" name="scale" selectionBinding="App.scaleSelected" value="DD"}}'}}
					Dia &nbsp;
				</label>
			</div>
			<div class="pull-right">
				<label for="scaleMonth">
					{{'{{view Ember.RadioButton id="scaleMonth" name="scale" selectionBinding="App.scaleSelected" value="MM" checked="checked"}}'}}
					Mes &nbsp;
				</label>
			</div>
		</div>
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="Bar"}}'}}
	</div>
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
						{{'{{#each App.detailsData}}'}}
							<tr>
								<td>{{'{{date}}'}}</td>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{os}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
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
							<td>9</td>
							<td>|</td>
							<td>20%</td>
							<td>Clics Totales</td>
						</tr>
					</table>
				</label>
			</div>
			
		</div>
		<div class="pull-right scaleChart">
			<div class="pull-left">
				{{ '{{view Ember.Select
						contentBinding="selectedLink"
						valueBinding="App.linkSelected"}}'
				}}
			</div>
			<div class="pull-left">
				Agrupar por: &nbsp;
			</div>
			<div class="pull-right">
				<label for="scaleHour">
					{{'{{view Ember.RadioButton id="scaleHour" name="scale" selectionBinding="App.scaleSelected" value="hh"}}'}}
					Hora &nbsp;
				</label>
			</div>
			<div class="pull-right">
				<label for="scaleDay">
					{{'{{view Ember.RadioButton id="scaleDay" name="scale" selectionBinding="App.scaleSelected" value="DD"}}'}}
					Dia &nbsp;
				</label>
			</div>
			<div class="pull-right">
				<label for="scaleMonth">
					{{'{{view Ember.RadioButton id="scaleMonth" name="scale" selectionBinding="App.scaleSelected" value="MM" checked="checked"}}'}}
					Mes &nbsp;
				</label>
			</div>
		</div>
		{{'{{view App.TimeGraphView idChart="clickPieChartContainer" typeChart="Bar"}}'}}
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
							{{'{{#each App.detailsLinks}}'}}
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
	</div>
	<div class="row-fluid">
		<div class="span12">
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
						{{'{{#each App.detailsData}}'}}
							<tr>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{link}}'}}</td>
								<td>{{'{{date}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
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
				<label class="label-unsubscribed-percent">
					<table>
						<tr>
							<td></td>
							<td>{{statisticsData.unsubscribed}}</td>
							<td>|</td>
							<td>{{statisticsData.statunsubscribed}}%</td>
						</tr>
					</table>
				</label>
			</div>
			<div class="titleMail">
				<h4 class="unsubscribedColor">Des-suscritos</h4>
			</div>
		</div>
		<div class="pull-right scaleChart">
			<label for="scaleHour">
				{{'{{view Ember.RadioButton id="scaleHour" name="scale" selectionBinding="App.scaleSelected" value="hh"}}'}}
				Hora
			</label>
			<label for="scaleDay">
				{{'{{view Ember.RadioButton id="scaleDay" name="scale" selectionBinding="App.scaleSelected" value="DD"}}'}}
				Dia
			</label>
			<label for="scaleMonth">
				{{'{{view Ember.RadioButton id="scaleMonth" name="scale" selectionBinding="App.scaleSelected" value="MM" checked="checked"}}'}}
				Mes
			</label>
			</div>
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar"}}'}}
	</div>
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
								<td>Fecha y hora</td>
								<td>Dirección de correo</td>
								<td>Sistema operativo?</td>
							</tr>
						</thead>
						<tbody>
						{{'{{#each App.detailsData}}'}}
							<tr>
								<td>{{'{{date}}'}}</td>
								<td>{{'{{email}}'}}</td>
								<td>{{'{{os}}'}}</td>
							</tr>
						{{ '{{/each}}' }}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</script>

