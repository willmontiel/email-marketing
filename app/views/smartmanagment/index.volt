{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'smartmanagent']) }}
		</div>
	</div>
{% endblock %}	