{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	
	<script>
			var chartData1 = [
				{
					type: "Aperturas",
					amount: {{stat.uniqueOpens}}
				}, 
				{
					type: "No Aperturas",
					amount: {{stat.sent - stat.uniqueOpens}}
				}
			]; 
			
			var chartData2 = [
				{
					type: "Spam",
					amount: {{stat.spam}}
				},
				{
					type: "Des-suscritos",
					amount: {{stat.unsubscribed}}
				},
				{
					type: "Rebotados",
					amount: {{stat.bounced}}
				},
				{
					type: "Correos efectivos",
					amount: {{stat.sent - stat.spam - stat.unsubscribed - stat.bounced}}
				}
			];
			
			AmCharts.ready(function () {
				var chart1 = new AmCharts.AmPieChart();
				chart1.dataProvider = chartData1;
				chart1.titleField = "type";
				chart1.valueField = "amount";
				
				chart1.colors = ["#8CC079", "#E86C12"];
				
				chart1.sequencedAnimation = true;
				chart1.startEffect = "easeOutSine";
				chart1.innerRadius = "40%";
				chart1.startDuration = 1;
				chart1.labelRadius = 2;
				chart1.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
				// this makes the chart 3D
				chart1.depth3D = 0;
				chart1.angle = 0;

				chart1.addListener("clickSlice", function (event) {

				});

				chart1.write('summaryChart2');
				//------------------------------------------------------------
				var chart2 = new AmCharts.AmPieChart();
				chart2.dataProvider = chartData2;
				chart2.titleField = "type";
				chart2.valueField = "amount";
				
				chart2.colors = ["#D12929", "#8C8689", "#953B39", "#1A73AD"];
				
				chart2.sequencedAnimation = true;
				chart2.startEffect = "easeOutSine";
				chart2.innerRadius = "40%";
				chart2.startDuration = 1;
				chart2.height = "500%";
				chart2.labelRadius = 2;
				chart2.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
				// this makes the chart 3D
				chart2.depth3D = 0;
				chart2.angle = 0;

				chart2.addListener("clickSlice", function (event) {

				});

				chart2.write('summaryChart1');
			});
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-signal icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span12">
			<table class="table" style="border: 0px !important;" >
				<thead></thead>
				<tbody>
					<tr>
						<td>
							<div class="box">
								<div class="box-section news with-icons">
									<label class="avatar-openings"><i class="icon-folder-open icon-3x"></i></label>
									<div class="news-time">
									  <span>{{stat.percentageUniqueOpens}}%</span>
									</div>
									<div class="news-content">
										<label class="label-openings">{{stat.uniqueOpens|numberf}}</label>
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
										<label class="label-clicks">{{stat.clicks|numberf}}</label>
										<div class="news-text">
											Clicks
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
									  <span>{{stat.percentageUnsubscribed}}%</span>
									</div>
									<div class="news-content">
										<label class="label-unsubscribed">{{stat.unsubscribed|numberf}}</label>
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
									  <span>{{stat.percentageBounced}}%</span>
									</div>
									<div class="news-content">
										<label class="label-bounced">{{stat.bounced|numberf}}</label>
										<div class="news-text">
											Rebotes
										</div>
									</div>
								</div>	
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<h3>{{contactList.name}} <small>20.002 correos enviados</small></h3>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="box">
				<div id="summaryChart1" style="width: 600px; height: 400px;"></div>
			</div>
		</div>
		<div class="span6">
			<div class="box">
				<div id="summaryChart2" style="width: 600px; height: 400px;"></div>
			</div>
		</div>
	</div>
{% endblock %}
