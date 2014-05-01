{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		var chartData1 = [];
		var chartData2 = [];
		
		{%for data in summaryChartData1 %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			chartData1.push(data);
		{%endfor%}
			
		{%for data in summaryChartData2 %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			chartData2.push(data);
		{%endfor%}
		
		AmCharts.ready(function () {
			chart1 = createPieChart(chartData1);	
			chart1.write('summaryChart1');
			chart2 = createPieChart(chartData2);	
			chart2.write('summaryChart2');
		});
		
		function compareMails() {
			var id = $('#mailstocompare').val();
			if(id !== undefined) {
				window.location = "{{url('statistic/comparemails')}}/{{mail1.idMail}}/" + id;
			}
		}
			
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	
	<div class="row">
		<div class="col-md-3 col-md-offset-7">
			<select id="mailstocompare" class="form-control">
				{%for cmail in compareMail %}
					<option value="{{cmail.id}}">{{cmail.name}}</option>
				{%endfor%}
			</select>
		</div>
		<div class="col-md-2">
			<button class="btn btn-sm btn-guardar extra-padding" onclick="compareMails()">Comparar</button>
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-6">
			<h4 class="sectiontitle">{{mail1.name}}</h4>
			<div id="summaryChart1" style="width: 640px; height: 400px;"></div>
		</div>
		<div class="col-md-6">
			<h4 class="sectiontitle">{{mail2.name}}</h4>
			<div id="summaryChart2" style="width: 640px; height: 400px;"></div>
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<table class="table table-striped">
				<tr>
					<td>
						<div class="box">
							<div class="box-section news with-icons">
								<label class="avatar-openings"><i class="icon-folder-open icon-3x"></i></label>
								<div class="news-time">
								  <span>{{statisticsData1.statopens}}%</span>
								</div>
								<div class="news-content">
									<label class="label-openings">{{statisticsData1.opens|numberf}}</label>
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
								<label class="avatar-openings"><i class="icon-folder-open icon-3x"></i></label>
								<div class="news-time">
								  <span>{{statisticsData2.statopens}}%</span>
								</div>
								<div class="news-content">
									<label class="label-openings">{{statisticsData2.opens|numberf}}</label>
									<div class="news-text">
										Aperturas
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
								<label class="avatar-clicks"><i class="icon-hand-up icon-3x"></i></label>
								<div class="news-time">
									<span>{{statisticsData1.percent_clicks_CTO}}% <strong>(CTO)</strong></span>
								</div>
								<div class="news-content">
									<label class="label-clicks">{{statisticsData1.totalclicks|numberf}}</label>
									<div class="news-text">
										Clics
									</div>
								</div>
							</div>	
						</div>
					</td>
					<td>
						<div class="box">
							<div class="box-section news with-icons">
								<label class="avatar-clicks"><i class="icon-hand-up icon-3x"></i></label>
								<div class="news-time">
								  <span>{{statisticsData2.percent_clicks_CTO}}% <strong>(CTO)</strong></span>
								</div>
								<div class="news-content">
									<label class="label-clicks">{{statisticsData2.totalclicks|numberf}}</label>
									<div class="news-text">
										Clics
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
								  <span>{{statisticsData1.statunsubscribed}}%</span>
								</div>
								<div class="news-content">
									<label class="label-unsubscribed">{{statisticsData1.unsubscribed|numberf}}</label>
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
								<label class="avatar-unsubscribed"><i class="icon-minus-sign icon-3x"></i></label>
								<div class="news-time">
								  <span>{{statisticsData2.statunsubscribed}}%</span>
								</div>
								<div class="news-content">
									<label class="label-unsubscribed">{{statisticsData2.unsubscribed|numberf}}</label>
									<div class="news-text">
										Des-suscritos
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
								<label class="avatar-bounced"><i class="icon-ban-circle icon-3x"></i></label>
								<div class="news-time">
								  <span>{{statisticsData1.statbounced}}%</span>
								</div>
								<div class="news-content">
									<label class="label-bounced">{{statisticsData1.bounced|numberf}}</label>
									<div class="news-text">
										Rebotes
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
								  <span>{{statisticsData2.statbounced}}%</span>
								</div>
								<div class="news-content">
									<label class="label-bounced">{{statisticsData2.bounced|numberf}}</label>
									<div class="news-text">
										Rebotes
									</div>
								</div>
							</div>	
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
{% endblock %}