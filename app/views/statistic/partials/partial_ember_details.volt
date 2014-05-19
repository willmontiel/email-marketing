<script type="text/x-handlebars" data-template-name="drilldown/opens">
	{{ '{{#if App.chartData}}' }}
		<h4 class="sectiontitle">Aperturas</h4>
		<div class="wrapper">	
			{{'{{view App.TimeGraphView idChart="openBarChartContainer" typeChart="bar-drilldown"}}'}}
		</div>
		<hr>
		<div class="stats">
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.opens|numberf}}</span><br>
				<span class="number">{{statisticsData.statopens}}%</span>
			</div>
		</div>
		<div class="clearfix"></div>
	{{ '{{else}}' }}
		<div class="bg bg-warning wrapper">
			Aún no hay aperturas para esta campaña.
		</div>
	{{ '{{/if}}' }}
		{% if type is defined %}
		{% else %}
			{{ '{{#if detailsData}}' }}
				{{ partial("statistic/partials/opens_table_stats_partial") }}
			{{ '{{/if}}' }}
		{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/clicks">
	{{ '{{#if App.chartData}}' }}
		<h4 class="sectiontitle">Clics</h4>
		<div class="wrapper">
			{{'{{view App.TimeGraphView idChart="clickBarChartContainer" typeChart="bar-drilldown"}}'}}
		</div>
		<hr>
		<div class="stats">	
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.totalclicks}}</span><br>
				Total de clics únicos
			</div>
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.clicks_CTR}} de {{statisticsData.total - statisticsData.bounced}}</span><br>
				<span class="number">({{statisticsData.percent_clicks_CTR}}%)</span>
				Tasa de clics
			</div>
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.clicks_CTR}} de {{statisticsData.opens}}</span><br>
				<span class="number">({{statisticsData.percent_clicks_CTO}}%)</span>
				Click To Open Rate
			</div>
		</div>
		<div class="clearfix"></div>
		
		<h4 class="sectiontitle">Clics por enlance</h4>

		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<td class="col-sm-11">Vínculos</td>
							<td class="col-sm-1">Total Clics</td>
						</tr>
					</thead>
					<tbody>
						{{'{{#each detailsLinks}}'}}
						<tr>
							<td>{{'{{link}}'}}</td>
							<td>{{'{{total}}'}}</td>
						</tr>
						{{ '{{/each}}' }}
					</tbody>
				</table>
			</div>
		</div>
	{{ '{{else}}' }}
		<div class="bg bg-warning">
			<div class="wrapper">
				Aún no hay clics para esta campaña.
			</div>
		</div>
	{{ '{{/if}}' }}
	{% if type is defined %}
	{% else %}
		{{ '{{#if detailsData}}' }}
			{{ partial("statistic/partials/clics_table_stats_partial") }}
		{{ '{{/if}}' }}
	{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/unsubscribed">
	{{ '{{#if App.chartData}}' }}
		<h4 class="sectiontitle">Desuscritos</h4>	
		<div class="wrapper">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer"}}'}}
		</div>

		<hr>

		<div class="stats">
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.unsubscribed|numberf}}</span><br>
				<span class="number">{{statisticsData.statunsubscribed}}%</span>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="space"></div>
		<div class="text-right">
			<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
		</div>
		<div class="clearfix"></div>
	{{ '{{else}}' }}
		<div class="bg bg-success">
			<div class="wrapper">
				No hubo desuscritos durante la campaña.
			</div>
		</div>
	{{ '{{/if}}' }}
	{% if type is defined %}
	{% else %}
		{{ '{{#if detailsData}}' }}
			{{ partial("statistic/partials/unsubscribed_table_stats_partial") }}
		{{ '{{/if}}' }}
	{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/spam">
	{{ '{{#if App.chartData}}' }}
		{#
		<h4 class="sectiontitle">Spam</h4>
		<div class="wrapper">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer"}}'}}
		</div>

		<hr>
		<div class="stats">
			<div class="col-md-4 wrapper">
				<span class="number">{{statisticsData.spam|numberf}}</span><br>
				<span class="number">{{statisticsData.statspam}}%</span>
			</div>
		</div>

		<div class="clearfix"></div>
		<div class="space"></div>
		<div class="text-right">
			<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
		</div>
		<div class="clearfix"></div>
		#}
	{{ '{{else}}' }}
		<div class="bg bg-success">
			<div class="wrapper">
				No hay reportes de spam para esta campaña.
			</div>
		</div>
	{{ '{{/if}}' }}
	{% if type is defined %}
	{% else %}
		{{ '{{#if detailsData}}' }}
			{{ partial("statistic/partials/spam_table_stats_partial") }}
		{{ '{{/if}}' }}
	{% endif %}
</script>

<script type="text/x-handlebars" data-template-name="drilldown/bounced">
	{{ '{{#if bouncedData}}' }}
		<h4 class="sectiontitle">Rebotes</h4>
		<div class="col wrapper">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartContainer" typeChart="pie-basic"}}'}}
		</div>
		<hr>	

		<div class="stats">
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.softbounced|numberf}}</span><br>
				<span class="number">{{statisticsData.statsoftbounced}}%</span>
				Suaves
			</div>
			<div class="col-sm-4 wrapper">
				<span class="number">{{statisticsData.hardbounced|numberf}}</span><br>
				<span class="number">{{statisticsData.stathardbounced}}%</span>
				Duros
			</div>
		</div>
		<div class="clearfix"></div>
		{% if type is defined %}
		{% else %}
			{{ partial("statistic/partials/bounced_table_stats_partial") }}
		{% endif %}
	{{ '{{else}}' }}
		<div class="bg bg-success wrapper">
			No hubo correos rebotados para esta campaña.
		</div>
	{{ '{{/if}}' }}
</script>

