<script type="text/x-handlebars" data-template-name="drilldown/opens">
	<h4 class="sectiontitle">Aperturas</h4>
	<div class="col-md-offset-1 wrapper">	
		{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="Bar" textChart="Aperturas de Correo"}}'}}
	</div>
	<div class="summary-opens stats col-md-3">
		<div class="col-sm-4">
			<span class="number">{{statisticsData.opens|numberf}}</span><br>
			<span class="number">{{statisticsData.statopens}}%</span>
		</div>
	</div>
	<div class="col-md-3">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/opens" class="btn btn-default">Descargar reporte</a>
	</div>
	<div class="clearfix"></div>
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
		<div class="space"></div>
		<div class="box-footer flat"> 
			{{ partial("partials/pagination_partial") }}
		</div>
	<hr>
	</div>

</script>

<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	<h4 class="sectiontitle">Clics</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="clickBarChartContainer" typeChart="Bar" textChart="Clics en"}}'}}
	</div>
	<div class="summary-clicks stats col-md-6">	
		<div class="col-sm-4">
			<span class="number">{{statisticsData.totalclicks}}</span><br>
			Total de clics únicos
		</div>
		<div class="col-sm-4">
			<span class="number">{{statisticsData.clicks_CTR}} de {{statisticsData.total - statisticsData.bounced}}</span><br>
			<span class="number">({{statisticsData.percent_clicks_CTR}}%)</span>
			Tasa de clics
		</div>
		<div class="col-sm-4">
			<span class="number">{{statisticsData.clicks_CTR}} de {{statisticsData.opens}}</span><br>
			<span class="number">({{statisticsData.percent_clicks_CTO}}%)</span>
			Click To Open Rate
		</div>
	</div>
	<div class="col-md-3">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/clicks" class="btn btn-default">Descargar reporte</a>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	<h4 class="sectiontitle">Detalle de clics</h4>
	<div class="col-md-10 col-md-offset-1">
		<table class="table table-striped">
			<thead>
				<tr>
					<td>Vínculos</td>
					<td>Total Clics</td>
					{#<td>Total Clics únicos</td>#}
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
	
	<div class="space"></div>

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
		<div class="space"></div>
		<hr>
			
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	<h4 class="sectiontitle">Desuscritos</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Des-suscritos de Correo"}}'}}
	</div>
	
	<div class="space"></div>
	
	<div class="summary-unsubscribed stats col-md-3">
		<div class="col-sm-4">
			<span class="number">{{statisticsData.unsubscribed|numberf}}</span><br>
			<span class="number">{{statisticsData.statunsubscribed}}%</span>
		</div>
	</div>
	<div class="col-md-3">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/unsubscribed" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<h4 class="sectiontitle">Detalle de desuscritos</h4>
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
			<div class="space"></div>
			<hr>
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/spam">
	<h4 class="sectiontitle">Spam</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Reportes de Spam"}}'}}
	</div>
	
	<div class="space"></div>
	
		<div class="summary-spam stats col-md-3">
			<div class="col-md-4">
				<span class="number">{{statisticsData.spam|numberf}}</span><br>
				<span class="number">{{statisticsData.statspam}}%</span>
			</div>
		</div>
		<div class="col-md-3">
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/spam" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<h4 class="sectiontitle">Detalle de Spam</h4>
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
		<div class="space"></div>
		<hr>
		<div class="box-footer flat"> 
			{{ partial("partials/pagination_partial") }}
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="drilldown/bounced">
	<h4 class="sectiontitle">Rebotes</h4>
	<div class="col-md-offset-1 wrapper">
		{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="Bar" textChart="Rebotes"}}'}}
	</div>

	<div class="space"></div>
	

	<div class="summary-bounced stats col-md-5">
		<div class="col-sm-4">
			<span class="number">{{statisticsData.softbounced|numberf}}</span><br>
			<span class="number">{{statisticsData.statsoftbounced}}%</span>
			Suaves
		</div>
		<div class="col-sm-4">
			<span class="number">{{statisticsData.hardbounced|numberf}}</span><br>
			<span class="number">{{statisticsData.stathardbounced}}%</span>
			Duros
		</div>
	</div>
	<div class="col-md-3">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/bounced" class="btn btn-default btn-sm extra-padding">Descargar reporte</a>
	</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<h4 class="sectiontitle">Detalle de rebotes</h4>
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
			<div class="space"></div>
			<hr>
			<div class="box-footer flat"> 
				{{ partial("partials/pagination_partial") }}
			</div>
		</div>
	</div>
</script>

