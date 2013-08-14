{% extends "templates/index.volt" %}
{% block header_javascript %}
	<script type="text/javascript">
		var MyDbaseUrl = 'emarketing/api/';
	</script>
	{{ super() }}
	{{ partial("partials/emberlist_partial") }}
{% endblock %}

{% block content %}
	<div class="row-fluid">
		<div class="span12">
			{%for list  in datalist%}
				<div class="row-fluid">
					<div class="span12">
						<h1>{{list.name}}</h1>
					</div>
				</div>
				<br>
				<div class="row-fluid">
					<div class="span12">
						{{list.description}}
					</div>
				</div>
			{%endfor%}
		</div>
	</div>
{% endblock %}