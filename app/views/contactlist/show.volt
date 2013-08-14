{% extends "templates/index.volt" %}

{% block content %}
	<div class="row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<div class="span12">
					<h1>{{datalist.name}}</h1>
				</div>
			</div>
			<br>
			<div class="row-fluid">
				<div class="span12">
					{{datalist.description}}
				</div>
			</div>
		</div>
	</div>
{% endblock %}