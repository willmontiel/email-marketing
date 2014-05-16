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
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script>
		var color = ['#97c86b', '#ef8807', '#BDBDBD'];
		var data1 = [];
		var i = 0;
		{%for sum1 in summaryChartData1 %}
			var obj = new Object;
				obj.name = '{{ sum1['title'] }}';
				obj.y = {{ sum1['value'] }};
				obj.color = color[i];

				data1.push(obj);
				i++;
		{%endfor%}
		
		var data2 = [];
		var j = 0;
		{%for sum2 in summaryChartData2 %}
			var obj = new Object;
				obj.name = '{{ sum2['title'] }}';
				obj.y = {{ sum2['value'] }};
				obj.color = color[j];

				data2.push(obj);
				j++;
		{%endfor%}
		
		createCharts('summaryChart1', data1);
		createCharts('summaryChart2', data2);
		
		function compareMails() {
			var id = $('#mailstocompare').val();
			if(id !== undefined) {
				window.location = "{{url('statistic/comparemails')}}/{{mail1.idMail}}/" + id;
			}
		}
			
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'mail']) }}
	</div>
		
	<div class="row">
		<div class="col-md-3 col-md-offset-6">
			<select id="mailstocompare" class="form-control">
				{%for cmail in compareMail %}
					<option value="{{cmail.id}}">{{cmail.name}}</option>
				{%endfor%}
			</select>
		</div>
		<div class="col-md-1 text-right ptop-3">
			<button class="btn btn-sm btn-default extra-padding" onclick="compareMails()">Comparar</button>
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-6">
			<h4 class="sectiontitle">{{mail1.name}}</h4>
			<div id="summaryChart1" class="col-sm-12"></div>
		</div>
		<div class="col-md-6">
			<h4 class="sectiontitle">{{mail2.name}}</h4>
			<div id="summaryChart2" class="col-sm-12"></div>
		</div>
	</div>

	<div class="space"></div>
	
	{#
		{{ partial('statistic/partials/partial_statistics_compare') }}
	#}
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