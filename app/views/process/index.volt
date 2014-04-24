{% extends "templates/editor_template.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
	<script type="text/javascript">
		var MyBaseURL = '{{urlManager.getBaseUri(true)}}';
		$(function() { 
			$("#processes-table").tablesorter(); 
			$("#processes-table-import").tablesorter(); 
		}); 
		
		var loadNow = function() {   
			$.getJSON(MyBaseURL + '/process/getprocesses',function(fulldata){
				$('#resultadomail').empty();
				$('#resultadoimport').empty();
				var data = fulldata['mail'];
				if(data !== null) {
					for(var f=0; f<data.length; f++){
						var stop;
						if (data[f].pause){
							stop = '<a href="{{url('process/stopsending')}}/' + data[f].task +'"><span class="label label-dark-red">Stop</span></a>'; 
						}
						else{
						stop = '---';
						}
						$("#resultadomail").append(
							'<tr><td style="text-align: center;">' + data[f].pid + '</td><td style="text-align: center;">' + data[f].type + '</td><td style="text-align: center;">' + data[f].confirm + '</td><td style="text-align: center;">' + data[f].status + '</td><td style="text-align: center;">' + data[f].task +'</td><td style="text-align: center;">' + data[f].totalContacts + '</td><td style="text-align: center;">' + data[f].sentContacts + '</td><td style="text-align: center;">' + stop + '</td></tr>'
						); 
					}
				}
				var importdata = fulldata['import'];
				if(importdata !== null) {
					for(var f=0; f<importdata.length; f++){
						var stopimport;
						if (importdata[f].pause){
							stopimport = '<a href="{{url('process/stopimport')}}/' + importdata[f].task +'"><span class="label label-dark-red">Stop</span></a>'; 
						}
						else{
						stopimport = '---';
						}
						$("#resultadoimport").append(
							'<tr><td style="text-align: center;">' + importdata[f].pid + '</td><td style="text-align: center;">' + importdata[f].type + '</td><td style="text-align: center;">' + importdata[f].confirm + '</td><td style="text-align: center;">' + importdata[f].status + '</td><td style="text-align: center;">' + importdata[f].task +'</td><td style="text-align: center;">' + importdata[f].totalContacts + '</td><td style="text-align: center;">' + importdata[f].sentContacts + '</td><td style="text-align: center;">' + stopimport + '</td></tr>'
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
	<div class="row">
		<div class="span7">
		{{ flashSession.output() }}
		</div>
		<div class="span5 text-right">
			<a href="{{url('index')}}" class="btn btn-blue"><i class="icon-dashboard"></i> Volver a la página principal</a>
		</div>
	</div>
	<br />
	<div class="row">
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
				<br />
				<p>Envio de Correos</p>
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
					<tbody id="resultadomail">

					</tbody>
				</table>
				
				<br />
				<br />
				<p>Importacion de Contactos</p>
				<table id="processes-table-import" class="tablesorter table table-normal">
					<thead>
						<tr>
							<th>PID</th>
							<th>Type</th>
							<th>Confirm</th>
							<th>Status</th>
							<th>Task</th>
							<th>Total contacts</th>
							<th>Contacts Imported</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="resultadoimport">

					</tbody>
				</table>
			</div>
			<div class="box-footer padded">

			</div>
		 </div>
	</div>	
{% endblock %}