{% extends "templates/index_new.volt" %}
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
			$("select").select2();
		});
		
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			window.location = "{{url('statistic/comparedbases')}}/{{dbase.idDbase}}/" + id;
		}
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-signal icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span12">
			<h3>{{dbase.name}} <small>{{statisticsData.sent}} correos enviados</small></h3>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<table class="table" style="border: 0px !important;" >
				<thead></thead>
				<tbody>
					<tr>
						<td style="width: 50%;">
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
			<div class="pull-left">
				<div class="span3">
					<select id="dbasestocompare" style="width: 150px;">
						{%for cdb in compareDbase %}
							<option value="{{cdb.id}}">{{cdb.name}}</option>
						{%endfor%}
					</select>
				</div>
				<div class="span2">
					<button class="btn btn-blue" onclick="compareDbases()"  style="margin-left: 80px;">Comparar</button>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="box">
				<div id="summaryChart" style="width: 640px; height: 400px;"></div>
			</div>
		</div>
	</div>	
{% endblock %}
