<script>
	var chartData1 = [
		{
			type: "Spam",
			amount: {{stat.spam}}
		},
		{
			type: "Des-suscritos",
			amount: {{stat.unsubscribed}}
		},
		
		{
			type: "Correos efectivos",
			amount: {{stat.sent - stat.spam - stat.unsubscribed - stat.bounced}}
		}
	];

	var chartData2 = [
		{
			type: "Rebotados",
			amount: {{stat.bounced}}
		},
		{
			type: "Aperturas",
			amount: {{stat.uniqueOpens}}
		}, 
		{
			type: "No Aperturas",
			amount: {{stat.sent - stat.uniqueOpens}}
		}
	]; 



	AmCharts.ready(function () {
		var chart1 = new AmCharts.AmPieChart();
		chart1.dataProvider = chartData2;
		chart1.titleField = "type";
		chart1.valueField = "amount";

		chart1.colors = ["#953B39", "#8CC079", "#8C8689"];

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

		chart1.write('summaryChart1');
		//------------------------------------------------------------
		var chart2 = new AmCharts.AmPieChart();
		chart2.dataProvider = chartData1;
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

		//chart2.write('summaryChart2');
	});
</script>