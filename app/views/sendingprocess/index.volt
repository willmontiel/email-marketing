{% extends "templates/editor_template.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url}}';
		$(function() { 
			$("#processes-table").tablesorter(); 
		}); 
		
		var loadNow = function() {   
						  $.getJSON(MyBaseURL + 'sendingprocess/getprocessesinfo',function(data){
					  });
		};
		
		$(function() {
			loadNow();
			var autoRefresh = setInterval(loadNow, 10000);
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
		<div class="span12 padded">
			<div class="box">
				<div class="box-header padded">
					<div class="input-prepend span3">
						<a class="add-on" href="#">
							<i class="icon-search"></i>
						</a>
						<input type="text" placeholder="Buscar...">
					</div>
					<div class="pull-right">
						<button class="btn btn-default" onclick="loadNow()"><i class="icon-refresh"></i> Refresh</button>
					</div>
				</div>
				<div class="box-content" id="info-processes">
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
							</tr>
						</thead>
						<tbody>
							{%for a in account%}
								<tr>
									<td>{{a.company}}</td>
									<td>{{a.idAccount}}</td>
									<td>lala</td>
									<td class="center">1.7</td>
									<td class="center">1.7</td>
									<td class="center">1.7</td>
									<td class="center">1.7</td>
								</tr>
							{% endfor %}
						</tbody>
					</table>
				</div>
				<div class="box-footer padded">
				
				</div>
			 </div>
		</div>
	</div>	
{% endblock %}