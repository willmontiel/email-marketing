{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="space"></div>

	<div class="row">
		<h4 class="sectiontitle">Autorespuestas</h4>
	</div>
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#time" data-toggle="tab"><span class="glyphicon glyphicon-calendar"></span> Basadas en tiempo</a></li>
		<li><a href="#profile" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span></a></li>
		<li><a href="#messages" data-toggle="tab"><span class="glyphicon glyphicon-gift"></span></a></li>
		<li><a href="#messages" data-toggle="tab"><span class="glyphicon glyphicon-gift"></span></a></li>
	</ul>
	
	<div class="tab-content">
		<div class="tab-pane active" id="time">
			<div class="space"></div>
			<div class="text-right">
				<a href="{{url('campaign/new/time')}}" class="btn btn-sm btn-default extra-padding"><span class="glyphicon glyphicon-plus"></span> Nueva autorespuesta</a>
			</div>
			<div class="">
				<table class="table table-condensed">
					<thead>
						<tr>
							<td colspan="3">Autorespuestas</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="6">Uno</td>
							<td class="3">Dos</td>
							<td class="3">Tres</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane" id="profile">...</div>
		<div class="tab-pane" id="messages">...</div>
		<div class="tab-pane" id="messages">...</div>
	</div>
{% endblock %}