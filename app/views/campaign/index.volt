{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="space"></div>

	<div class="row">
		<h4 class="sectiontitle">Autorespuestas</h4>
	</div>
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span></a></li>
		<li><a href="#profile" data-toggle="tab"><span class="glyphicon glyphicon-calendar"></span></a></li>
		<li><a href="#messages" data-toggle="tab"><span class="glyphicon glyphicon-gift"></span></a></li>
	</ul>
	
	<div class="tab-content">
		<div class="tab-pane active" id="home">...</div>
		<div class="tab-pane" id="profile">...</div>
		<div class="tab-pane" id="messages">...</div>
	</div>
{% endblock %}