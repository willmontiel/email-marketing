{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}

	<script>
		var chartData = [];
		
		{%for data in summaryChartData %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			chartData.push(data);
		{%endfor%}
			
		AmCharts.ready(function () {
			chart = createPieChart(chartData);	
			chart.write('summaryChart');
		});
		
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparedbases')}}/{{dbase.idDbase}}/" + id;
			}
		}
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-signal icon-2x"></i>{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12">
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
		</div>
	</div>
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
			
			<div class="row">
				<div class="col-sm-7">
					<select id="dbasestocompare" class="form-control">
						{%for cdb in compareDbase %}
							<option value="{{cdb.id}}">{{cdb.name}}</option>
						{%endfor%}
					</select>
				</div>
				<div class="col-sm-5">
					<button class="btn btn-sm btn-guardar extra-padding" onclick="compareDbases()">Comparar</button>
				</div>
			</div>	
		</div>
			
		<div class="col-sm-6">
			<div id="summaryChart" style="width: 640px; height: 400px;"></div>
		</div>
	</div>
	
	<br />
#}
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

	{#   parcial estadisticas generales   #}
	{{ partial("statistic/partials/general_stats_contacts_partial") }}

{% endblock %}
