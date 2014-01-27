{% extends "templates/editor_template.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getBaseUri()}}';
		$(function() { 
			$("#processes-table").tablesorter(); 
		}); 
		
		var loadNow = function() {   
							$.getJSON(MyBaseURL + '/sendingprocess/getprocessesinfo',function(data){
							$('#resultado').empty();
							if(data !== null) {
								for(var f=0; f<data.length; f++){
									var stop;
									if (data[f].pause){
										stop = '<a href="{{url('sendingprocess/stop')}}/' + data[f].task +'"><span class="label label-dark-red">Stop</span></a>'; 
									}
									else{
									stop = '---';
									}
									$("#resultado").append(
										'<tr><td style="text-align: center;">' + data[f].pid + '</td><td style="text-align: center;">' + data[f].type + '</td><td style="text-align: center;">' + data[f].confirm + '</td><td style="text-align: center;">' + data[f].status + '</td><td style="text-align: center;">' + data[f].task +'</td><td style="text-align: center;">' + data[f].totalContacts + '</td><td style="text-align: center;">' + data[f].sentContacts + '</td><td style="text-align: center;">' + stop + '</td></tr>'
									); 
								}
							}
					  });
		};
		
		$(function() {
			loadNow();
			var autoRefresh = setInterval(loadNow, 45000);
		});
	</script>
{% endblock %}
{% block content %}
	<div class="area-top clearfix">
		<div class="pull-left header">
			<h3 class="title">
				<i class="icon-spinner icon-spin"></i> Monitor de procesos de envío
			</h3>
			<h5>
				Aqui podrá encontrar toda la información relacionada con los envíos de correos eléctronicos,
				como la programación, estados y que se estan enviando actualmente
			</h5>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span7">
		{{ flashSession.output() }}
		</div>
		<div class="span5 text-right">
			<a href="{{url('index')}}" class="btn btn-blue"><i class="icon-dashboard"></i> Volver a la página principal</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="box">
			<div class="box-header padded">
				<div class="pull-left">
					<h5>Procesos activos</h5>
				</div>
				<div class="pull-right">
					<button class="btn btn-default" onclick="loadNow()"><i class="icon-refresh"></i> Refrescar</button>
				</div>
			</div>
			<div class="box-content" >
				<table id="processes-table" class="tablesorter table table-normal">
					<thead>
						<tr>
							<th>PID</th>
							<th>Type</th>
							<th>Confirm</th>
							<th>Status</th>
							<th>Task</th>
							<th>Total contacts</th>
							<th>Sent contacts</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="resultado">

					</tbody>
				</table>
			</div>
			<div class="box-footer padded">

			</div>
		 </div>
	</div>	
{% endblock %}