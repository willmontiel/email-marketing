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
							<td>5</td>
							<td>|</td>
							<td>50%</td>
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
		<label>
			{{'{{view Ember.RadioButton name="selectionTest" selectionBinding="App.scaleSelected" value="hh"}}'}}
			Hora
		<label>
		</label>
			{{'{{view Ember.RadioButton name="selectionTest" selectionBinding="App.scaleSelected" value="DD"}}'}}
			Dia
		<label>
		</label>
			{{'{{view Ember.RadioButton name="selectionTest" selectionBinding="App.scaleSelected" value="MM"}}'}}
			Mes
		</label>
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
		<div class="news span4">
			<table class="table-condensed">
				<thead>
					<tr>
						<td colspan="5"><label class="label-gray-light-percent">Resumen de Clicks</label></td>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="5"></td></tr>
					<tr>
						<td colspan="3">
							<label class="label-total-percent">
								<table>
									<tr>
										<td><i class="icon-envelope"></i></td>
										<td>3000</td>
									</tr>
								</table>	
							</label>
						</td>
						<td colspan="2"><h4>Correos enviados</h4></td>
					</tr>
					<tr>
						<td colspan="3">
							<label class="label-click-percent">
								<table>
									<tr>
										<td><i class="icon-search"></i></td>
										<td>2500</td>
										<td>|</td>
										<td>83%</td>
									</tr>
								</table>
							</label>
						</td>
						<td  colspan="2"><h4>Clicks</h4></td>
					</tr>
				</tbody>
			</table>
		</div>
		{{'{{view App.TimeGraphView idChart="clickPieChartContainer" typeChart="Line"}}'}}
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

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	<hr />
	<div class="row-fluid">
		<div class="news span4">
			<table class="table-condensed">
				<thead>
					<tr>
						<td colspan="5"><label class="label-gray-light-percent">Resumen de Des-suscritos</label></td>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="5"></td></tr>
					<tr>
						<td colspan="3">
							<label class="label-total-percent">
								<table>
									<tr>
										<td><i class="icon-envelope"></i></td>
										<td>3000</td>
									</tr>
								</table>	
							</label>
						</td>
						<td colspan="2"><h4>Correos enviados</h4></td>
					</tr>
					<tr>
						<td colspan="3">
							<label class="label-unsubscribed-percent">
								<table>
									<tr>
										<td><i class="icon-search"></i></td>
										<td>2500</td>
										<td>|</td>
										<td>83%</td>
									</tr>
								</table>
							</label>
						</td>
						<td  colspan="2"><h4>Des-suscritos</h4></td>
					</tr>
				</tbody>
			</table>
		</div>
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Line"}}'}}
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

