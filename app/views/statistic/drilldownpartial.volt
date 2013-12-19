<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	<hr />
	<div class="row-fluid">
		<div class="news span4">
			<table class="table-condensed">
				<thead>
					<tr>
						<td colspan="5"><label class="label-gray-light-percent">Resumén de Clicks</label></td>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="5"></td></tr>
					<tr>
						<td colspan="3">
							<label class="label-blue-percent">
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
							<label class="label-green-percent">
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
		{{'{{view App.TimeGraphView idChart="clickPieChartContainer" typeChart="Pie"}}'}}
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

<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<hr />
	<div class="row-fluid">
		<div class="news span4">
			<table class="table-condensed">
				<thead>
					<tr>
						<td colspan="5"><label class="label-gray-light-percent">Resumén de aperturas</label></td>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="5"></td></tr>
					<tr>
						<td colspan="3">
							<label class="label-blue-percent">
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
							<label class="label-green-percent">
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
						<td  colspan="2"><h4>Aperturas</h4></td>
					</tr>
				</tbody>
			</table>
		</div>
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="Bar"}}'}}
	</div>
	<div class="row-fluid">
		<div class="span12">
			
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
						<td colspan="5"><label class="label-gray-light-percent">Resumén de Des-suscritos</label></td>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="5"></td></tr>
					<tr>
						<td colspan="3">
							<label class="label-blue-percent">
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
							<label class="label-green-percent">
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
						<td  colspan="2"><h4>Aperturas</h4></td>
					</tr>
				</tbody>
			</table>
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

