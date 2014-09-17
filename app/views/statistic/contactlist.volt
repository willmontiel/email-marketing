{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script type="text/javascript">
		var color = ['#97c86b', '#ef8807', '#BDBDBD'];
		var data = [];
		var i = 0;
		{%for data in summaryChartData %}
			var obj = new Object;
				obj.name = '{{ data['title'] }}';
				obj.y = {{ data['value'] }};
				obj.color = color[i];

				data.push(obj);
				i++;
		{%endfor%}
		
		createCharts('container', data);
		
		function compareLists() {
			var id = $('#liststocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparelists')}}/{{contactList.idContactlist}}/" + id;
			}
		}
		
		
		var domains = [];
		{% for domain in domains%}
			var obj2 = new Object;
				obj2.name = '{{domain.domain}}';
				obj2.y = {{domain.total}};
				
				domains.push(obj2);
		{% endfor %}
		
		createCharts('container2', domains);
	</script>
{% endblock %}
{% block content %}

	{#   Navegacion botones pequeños   #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}

	{#   encabezado página   #}
	
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="header">
				<div class="title">{{contactList.name}}</div>
				<div class="title-info">Creada el {{date('d/M/Y', contactList.createdon)}}</div>
				<div class="sub-title">
					<span class="active-contacts">{{contactList.Ctotal}}</span><br /> 
					<span class="text-contacts">Contactos</span>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	{#   Contenedor chart   #}
	
	<div class="row-fluid">
		<div id="container" class="col-sm-12 col-md-8 col-lg-6"></div>
		<div id="container2" class="col-sm-12 col-md-4 col-lg-6"></div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<hr>
	{#   parcial estadisticas generales   #}
	{{ partial("statistic/partials/general_stats_contacts_partial") }}

	{#   Select para comparacion de estadisticas   #}
	<h4 class="sectiontitle">Comparación</h4>
	<div class="container-fluid">
		<div class="col-xs-6 col-sm-5 col-md-4">
			<form class="form-horizontal" role="form">
	  			<div class="form-group">
	  				<label class="sr-only" for=""></label>
					<select id="liststocompare" class="form-control">
						{%for clt in compareList %}
							<option value="{{clt.id}}">{{clt.name}}</option>
						{%endfor%}
					</select>
				</div>
			</form>
		</div>
		<div class="col-md-2 col-xs-4 ptop-3">
			<button class="btn btn-sm btn-default btn-add extra-padding" onclick="compareLists();">Comparar</button>
		</div>
	</div>	
{% endblock %}


{#
	<div class="row">
		{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

		<div class="wrap">
			<div class="col-md-5">
				<h4 class="sectiontitle numbers-contacts" >{{contactList.name}}</h4>
			</div>
			<div class="col-md-7">
				<div class="col-md-6">
					<p><span class="blue big-number"> {{statisticsData.sent}} </span> correos enviados</p>
				</div>
				<div class="col-md-6">
					<br><p class="text-right">Fecha del envío {{date('Y-m-d', mail.finishedon)}}</p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="row">
			<div class="space"></div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="box-dashboard-summary summary-opens">
					<div class="title-stats-dashboard-summary">{{statisticsData.uniqueOpens|numberf}}</div>
					<div class="number-stats-dashboard-summary">{{statisticsData.percentageUniqueOpens}}%</div>
					<div class="title-stats-dashboard-summary">Aperturas</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="box-dashboard-summary summary-clicks">
					<div class="title-stats-dashboard-summary">{{statisticsData.clicks|numberf}}</div>
					<div class="number-stats-dashboard-summary">{{statisticsData.percent_clicks_CTR}}%</div>
					<div class="title-stats-dashboard-summary">Clics</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="box-dashboard-summary summary-unsubscribed">
					<div class="title-stats-dashboard-summary">{{statisticsData.unsubscribed|numberf}}</div>
					<div class="number-stats-dashboard-summary">{{statisticsData.percentageUnsubscribed}}%</div>
					<div class="title-stats-dashboard-summary">Desuscritos</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="box-dashboard-summary summary-bounced">
					<div class="title-stats-dashboard-summary">{{statisticsData.bounced|numberf}}</div>
					<div class="number-stats-dashboard-summary">{{statisticsData.percentageBounced}}%</div>
					<div class="title-stats-dashboard-summary">Rebotes</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="box-dashboard-summary summary-spam">
					<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
					<div class="number-stats-dashboard-summary">{{statisticsData.percentageSpam}}%</div>
					<div class="title-stats-dashboard-summary">Spam</div>
				</div>
			</div>
		</div>
		<div class="space"></div>

		<div class="row">
			{%for widget in stats.fullSocialStats()%}
				<div class="col-md-2 col-sm-4 col-xs-6">
					<div class="box-dashboard-summary summary-box-social">
						<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
						<div>
							<p>{{widget.getTitle()}}</p>
						</div>
						<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{widget.getTotal()}}</div>
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
							{% endfor %}
						</div>
					</div>
				</div>
			{% endfor %}

			{%for widget in stats.fullSocialStats()%}
				<div class="col-md-2 col-sm-4 col-xs-6">
					<div class="box-dashboard-summary summary-box-social">
						<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
						<div>
							<p>{{widget.getTitle()}}</p>
						</div>
						<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{widget.getTotal()}}</div>
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
							{% endfor %}
						</div>
					</div>
				</div>
			{% endfor %}

			{%for widget in stats.fullSocialStats()%}
				<div class="col-md-2 col-sm-4 col-xs-6">
					<div class="box-dashboard-summary summary-box-social">
						<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
						<div>
							<p>{{widget.getTitle()}}</p>
						</div>
						<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{widget.getTotal()}}</div>
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
							{% endfor %}
						</div>
					</div>
				</div>
			{% endfor %}

			{%for widget in stats.fullSocialStats()%}
				<div class="col-md-2 col-sm-4 col-xs-6">
					<div class="box-dashboard-summary summary-box-social">
						<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
						<div>
							<p>{{widget.getTitle()}}</p>
						</div>
						<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{widget.getTotal()}}</div>
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
							{% endfor %}
						</div>
					</div>
				</div>
			{% endfor %}
		</div>
		<div class="row">
			<div class="col-sm-7">
				<select id="liststocompare" class="form-control">
					{%for clt in compareList %}
						<option value="{{clt.id}}">{{clt.name}}</option>
					{%endfor%}
				</select>
			</div>
			<div class="col-sm-5">
				<button class="btn btn-sm btn-guardar extra-padding" onclick="compareLists()">Comparar</button>
			</div>
		</div>

		<div class="col-sm-6">
			<div id="summaryChart" style="width: 640px; height: 400px;"></div>
		</div>
	</div>
#}
