<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<div class="container-fluid">	
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="Bar" textChart="Aperturas de Correo"}}'}}
	</div>
	<div class="">
		<label class="label-open-percent">
			<label>{{statisticsData.opens|numberf}}</label></td>
			<label>{{statisticsData.statopens}}%</label></td>
		</label>
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/opens" class="btn btn-default">Descargar reporte</a>
	</div>

	<div class="space"></div>
	
	<h4 class="sectiontitle">Detalle de aperturas</h4>
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-striped">
				<thead>
					<tr>
						<td>Fecha y hora</td>
						<td>Dirección de correo</td>
					</tr>
				</thead>
				<tbody>
				{{'{{#each detailsData}}'}}
					<tr>
						<td>{{'{{date}}'}}</td>
						<td>{{'{{email}}'}}</td>
					</tr>
				{{ '{{/each}}' }}
				</tbody>
			</table>
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	<div class="row">
		<div class="col-md-offset-1">
			{{'{{view App.TimeGraphView idChart="clickBarChartContainer" typeChart="Bar" textChart="Clics en"}}'}}
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-6">
			<label class="label-gray-light-percent">Resumen de clics</label>
			<dl>
				<dd>
					<label class="label-click-percent">
						<table>
							<tr>
								<td class="clicks_percent_information">{{statisticsData.totalclicks}}</td>
								<td>Total de Clics Unicos</td>
							</tr>
						</table>
					</label>
				</dd>
				<dd>
					<label class="label-click-percent">
						<table>
							<tr>
								<td class="clicks_percent_information">
									{{statisticsData.clicks_CTR}} de {{statisticsData.total - statisticsData.bounced}}
									<br />
									({{statisticsData.percent_clicks_CTR}}%)
								</td>
								<td>Tasa de Clics</td>
							</tr>
						</table>
					</label>
				</dd>
				<dd>
					<label class="label-click-percent">
						<table>
							<tr>
								<td class="clicks_percent_information">
									{{statisticsData.clicks_CTR}} de {{statisticsData.opens}}
									<br />
									({{statisticsData.percent_clicks_CTO}}%)
								</td>
								<td>Click To Open Rate</td>
							</tr>
						</table>
					</label>
				</dd>
			</dl>
		</div>
	

		<div class="col-md-6">
			<table class="table table-striped">
				<thead>
					<tr>
						<td>Vinculos</td>
						<td>Total Clics</td>
						{#<td>Total Clics Unicos</td>#}
					</tr>
				</thead>
				<tbody>
					{{'{{#each detailsLinks}}'}}
					<tr>
						<td>{{'{{link}}'}}</td>
						<td>{{'{{total}}'}}</td>
						{#<td>{{'{{uniques}}'}}</td>#}
					</tr>
					{{ '{{/each}}' }}
				</tbody>
			</table>
		</div>
	</div>


	<div class="row">
		<div class="col-md-3">
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/clicks" class="btn btn-default">Descargar reporte</a>
		</div>
	</div>
	
	<div class="space"></div>

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="row">
				<div class="col-md-5">
					<div class="pull-left">
						{{ '{{view Ember.Select
								contentBinding="selectedLink"
								valueBinding="linkSelected"
								class="form-control"}}'
						}}
					</div>
				</div>
			</div>

			<table class="table table-striped">
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
				
				<div class="box-footer flat"> 
					{{ partial("partials/pagination_partial") }}
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	<div class="row">
		<div class="col-md-offset-1">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Des-suscritos de Correo"}}'}}
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-4">
			<dl>
				<dd><label class="label-gray-light-percent">Resumen de des-suscritos</label></dd>
				<dd>
					<label class="label-unsubscribed-percent">
						<table>
							<tr>
								<td><label>{{statisticsData.unsubscribed|numberf}}</label></td>
								<td>|</td>
								<td><label>{{statisticsData.statunsubscribed}}%</label></td>
							</tr>
						</table>
					</label>
				</dd>
			</dl>
		</div>
		<div class="col-md-3">
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/unsubscribed" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-striped">
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
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/spam">
	<div class="row">
		<div class="col-md-offset-1">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Reportes de Spam"}}'}}
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-4">
			<dl>
				<dd><label class="label-gray-light-percent">Resumen de des-suscritos</label></dd>
				<dd>
					<label class="label-spam-percent">
						<table>
							<tr>
								<td><label>{{statisticsData.spam|numberf}}</label></td>
								<td>|</td>
								<td><label>{{statisticsData.statspam}}%</label></td>
							</tr>
						</table>
					</label>
				</dd>
			</dl>
		</div>
		<div class="col-md-3">
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/spam" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-striped">
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
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</div>	
</script>

<script type="text/x-handlebars" data-template-name="drilldown/bounced">
	<div class="row">
		<div class="col-md-offset-1">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Rebotes"}}'}}
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-4">
			<dl>
				<dd><label class="label-gray-light-percent">Resumen de des-suscritos</label></dd>
				<dd>
					<label class="label-bounced-percent">
						<table>
							<tr>
								<td><label>{{statisticsData.softbounced|numberf}}</label></td>
								<td>|</td>
								<td><label>{{statisticsData.statsoftbounced}}%</label></td>
								<td>|</td>
								<td>Suaves</td>
							</tr>
							<tr>
								<td><label>{{statisticsData.hardbounced|numberf}}</label></td>
								<td>|</td>
								<td><label>{{statisticsData.stathardbounced}}%</label></td>
								<td>|</td>
								<td>Duros</td>
							</tr>
						</table>
					</label>
				</dd>
			</dl>
		</div>
		<div class="col-md-3">
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/bounced" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="row">
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
		
			<div class="space"></div>
		
			<div class="row">
				<div class="col-md-5">
					{{ '{{view Ember.Select
							contentBinding="selectedType"
							valueBinding="typeSelected"
							class="form-control"}}'
					}}
				</div>
			</div>
				
			<table class="table table-striped">
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
				
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</div>
</script>

