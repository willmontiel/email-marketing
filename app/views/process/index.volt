{% extends "templates/index_b3.volt" %}
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
							'<tr><td>' + data[f].pid + '</td><td>' + data[f].type + '</td><td>' + data[f].confirm + '</td><td>' + data[f].status + '</td><td>' + data[f].task +'</td><td>' + data[f].totalContacts + '</td><td>' + data[f].sentContacts + '</td><td>' + stop + '</td></tr>'
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
							'<tr><td>' + importdata[f].pid + '</td><td>' + importdata[f].type + '</td><td>' + importdata[f].confirm + '</td><td>' + importdata[f].status + '</td><td style="text-align: center;">' + importdata[f].task +'</td><td>' + importdata[f].totalContacts + '</td><td>' + importdata[f].sentContacts + '</td><td>' + stopimport + '</td></tr>'
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

	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'process']) }}
	
	<div class="row">
		<h4 class="sectiontitle">Procesos de envío</h4>

		<div class="bs-callout bs-callout-info">
			Monitoree los envíos de correos electrónicos,
			la programación de los mismos, sus estados y envíos en curso.
		</div>
	</div>

	{{ flashSession.output() }}
	<div class="text-right">
		<button class="btn btn-sm btn-primary extra-padding" onclick="loadNow()"><span class="glyphicon glyphicon-refresh"></span> Refrescar</button>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<h4>Procesos activos para envío de correos</h4>
			<table id="processes-table" class="table table-striped">
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
				<tbody id="resultadomail"></tbody>
			</table>
		</div>
	</div>
	
	<br />
	
	<div class="row">
		<div class="col-md-12">
			<h4>Procesos activos para importación de contactos</h4>
			<table id="processes-table-import" class="table table-striped">
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
				<tbody id="resultadoimport"></tbody>
			</table>
		</div>
	</div>
{% endblock %}