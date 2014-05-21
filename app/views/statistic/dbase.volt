{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script>
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
		
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparedbases')}}/{{dbase.idDbase}}/" + id;
			}
		}
	</script>
{% endblock %}
{% block content %}
	{#   Navegacion botones pequeños   #}
	<div class="row">
		{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
		</div>
	</div>
	
	{#   encabezado página   #}
	<div class="wrap">
		<div class="col-md-5">
			<h4 class="sectiontitle numbers-contacts">{{dbase.name}}</h4>
		</div>
		<div class="col-md-7">
			<div class="col-md-6">
				<p><span class="blue big-number">{{dbase.Ctotal}} </span>Contactos totales</p>
			</div>
			<div class="col-md-6">
				<br><p class="text-right">Creada el: {{date('Y-m-d', dbase.createdon)}}</p>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	
	{#   Contenedor chart   #}
	<div id="container" class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 col-sm-12"></div>
	<div class="clearfix"></div>
	<div class="space"></div>

	{#   parcial estadisticas generales   #}
	{{ partial("statistic/partials/general_stats_contacts_partial") }}

	{#   Select para comparacion de estadisticas   #}
	<h4 class="sectiontitle">Comparación</h4>
	<div class="container-fluid">
		<div class="col-xs-6 col-sm-5 col-md-4">
			<form class="form-horizontal" role="form">
	  			<div class="form-group">
	  				<label class="sr-only" for=""></label>
					<select id="dbasestocompare" class="form-control">
						{%for cdb in compareDbase %}
							<option value="{{cdb.id}}">{{cdb.name}}</option>
						{%endfor%}
					</select>
				</div>
			</form>
		</div>
		<div class="col-md-2 col-xs-4 ptop-3">
			<button class="btn btn-sm btn-default btn-add extra-padding" onclick="compareDbases()">Comparar</button>
		</div>
	</div>	
{% endblock %}

{#
	<div class="row">
		<div class="col-sm-12">
			<h4 class="sectiontitle">Estadisticas de base de datos</h4>
			<div class="bs-callout bs-callout-info">
				<h3>{{dbase.name}} <small>{{statisticsData.sent}} correos enviados</small></h3>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-6">
			<table class="table table-striped">
				<thead></thead>
				<tbody>
					<tr>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-openings"><i class="icon-folder-open icon-3x"></i></label>
									<div class="news-time">
									  <span>{{statisticsData.percentageUniqueOpens}}%</span>
									</div>
									<div class="news-content">
										<label class="label-openings">{{statisticsData.uniqueOpens|numberf}}</label>
										<div class="news-text">
											Aperturas
										</div>
									</div>
								</div>	
							</div>
						</td>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-clicks"><i class="icon-hand-up icon-3x"></i></label>
									<div class="news-content">
										<label class="label-clicks">{{statisticsData.clicks|numberf}}</label>
										<div class="news-text">
											Clicks
										</div>
									</div>
								</div>	
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-unsubscribed"><i class="icon-minus-sign icon-3x"></i></label>
									<div class="news-time">
									  <span>{{statisticsData.percentageUnsubscribed}}%</span>
									</div>
									<div class="news-content">
										<label class="label-unsubscribed">{{statisticsData.unsubscribed|numberf}}</label>
										<div class="news-text">
											Des-suscritos
										</div>
									</div>
								</div>	
							</div>
						</td>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-bounced"><i class="icon-ban-circle icon-3x"></i></label>
									<div class="news-time">
									  <span>{{statisticsData.percentageBounced}}%</span>
									</div>
									<div class="news-content">
										<label class="label-bounced">{{statisticsData.bounced|numberf}}</label>
										<div class="news-text">
											Rebotes
										</div>
									</div>
								</div>	
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-spam"><i class="icon-warning-sign icon-3x"></i></label>
									<div class="news-time">
									  <span>{{statisticsData.percentageSpam}}%</span>
									</div>
									<div class="news-content">
										<label class="label-spam">{{statisticsData.spam|numberf}}</label>
										<div class="news-text">
											Reportes de Spam
										</div>
									</div>
								</div>	
							</div>
						</td>
					</tr>
				</tbody>
			</table>
#}
