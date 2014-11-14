{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# HighCharts & HighMaps #}
	{{ javascript_include('vendors/highcharts/highcharts.js')}}
	{{ javascript_include('vendors/highcharts/modules/exporting.js')}}

	<script type="text/javascript">
		$(function () {
			$.getJSON(MyBaseURL + 'account/gethistory',function(data){ 
				setHighchart(data);
			});
			
			function setHighchart(data) {
				$('#container').highcharts({
					title: {
						text: 'Puntos en el año 2014',
						x: -20 //center
					},
					//subtitle: {
					//	text: 'Source: WorldClimate.com',
					//	x: -20
					//},
					xAxis: {
						categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
							'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
					},
					yAxis: {
						title: {
							text: 'Puntos'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						valueSuffix: ''
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
					},
					series: [{
						name: 'Puntos',
						data: [data.Jan, data.Feb, data.Mar, data.Apr, data.May, data.Jun, data.Jul, data.Aug, data.Sep, data.Oct, data.Nov, data.Dec]
					}]
				});
			}
		});
	</script>
{% endblock %}	
{% block content %}
	{#<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{{flashSession.output()}}
		</div>
	</div>
	#}

	<div class="container-fluid">
		{#<h1 class="sectiontitle">Interacciones de los últimos quince días</h1>#}
		<h1 class="sectiontitle">Interacción de la última campaña enviada</h1>	
		{%for widget in stats.fullPeriodStats()%}
		<div class="col-xs-6 col-md-3 col-lg-3">
			<div class="box-dashboard-summary summary-{{ widget.getClassName() }}">
				<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
				<div class="title-stats-dashboard-summary">
					{{widget.getTitle()}}
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				{#<div class="sparkline big" data-color="white">
					<!--
					{%for statvalue in widget.getSecondaryValues()%}
						{{statvalue.value}},
					{%endfor%}
					-->
				</div>#}
			</div>
		</div>

		{%endfor%}
		{%for widget in stats.fullSocialStats()%}
		<div class="col-xs-6 col-md-3 col-lg-3">
			<div class="box-dashboard-summary summary-box-social">
				<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
				<div>
					<p>{{widget.getTitle()}}</p>
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				<div class="container-fluid">
					<div class="row border-top">
					{%for value in widget.getSecondaryValues()%}
						<div class="col-xs-6 social-sec-box">
							<div class="">
								{{value.name}}
							</div>
							<div class="">
								{{value.value}}
							</div>
						</div>
					{%endfor%}
					</div>
				</div>
			</div>
		</div>
		{%endfor%}
	</div>
	
	<div class="clearfix space-small"></div>
			
	<div class="row block-simple block-simple-gray">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
					<div class="block block-gray">
						<div id="container"></div>
					</div>	
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
					<div class="score-container">
						<div class="score-image"></div>
						<div class="score-detail">
							{% if score.score is defined %}{{score.score}}{%else%}0{% endif %}
						</div>
						<div class="score-description">
							Puntos
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="clearfix space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Interacciones de los últimos 3 envíos</h1>
			<table class="table table-normal table-striped table-bordered">
				<thead>
					<tr>
						<th class="title"></th>
						<th class="opens">Aperturas</th>
						<th class="clics">Clics</th>
						<th class="desusc">Desuscripciones</th>
						<th class="bounced">Rebotes</th>
					</tr>
				</thead>
				<tbody>
					{%for mail in stats.getLastMailsWithStats()%}
						<tr>
							<td>{{mail.name}}</td>
							<td>{{mail.uniqueOpens|numberf}}</td>
							<td>{{mail.clicks|numberf}}</td>
							<td>{{mail.unsubscribed|numberf}}</td>
							<td>{{mail.bounced|numberf}}</td>
						</tr>
					{%endfor%}
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="clearfix space"></div>
	<div class="container-fluid">
		<h1 class="sectiontitle">Qué quiere hacer hoy?</h1>
			<div class="col-xs-6 col-md-3">
				<div class="big-btn-nav sm-btn-blue">
					<a href="{{url('mail/compose')}}"  class="shortcuts"><span class="sm-button-large-email-new"></span></a>
				</div>
				<div class="w-190 center">
					<a href="{{url('mail/compose')}}" class="btn-actn">Crear un nuevo email</a>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="big-btn-nav sm-btn-blue">
					<a href="{{url('contactlist#/lists')}}"  class="shortcuts"><span class="sm-button-large-contact-list"></span></a>
				</div>
				<div class="w-190 center">
					<a href="{{url('contactlist#/lists')}}" class="btn-actn">Listas de contactos</a>
				</div>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="big-btn-nav sm-btn-blue">
					<a href="{{url('mail/list')}}"  class="shortcuts"><span class="sm-button-large-email-list"></span></a>
				</div>
				<div class="w-190 center">
					<a href="{{url('mail/list')}}" class="btn-actn">Listas de correos</a>
				</div>
			</div>
	</div>
	<div class="row space"></div>
</div>

{% endblock %}