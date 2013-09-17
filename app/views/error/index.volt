{% extends "templates/signin.volt" %}

{% block content %}
	<div class="navbar navbar-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="{{url('')}}">Mail Station</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="error-box">
					<div class="message-small">Ha accesado a un recurso restringido</div>
					<div class="message-big">404</div>
					<br />
					<a class="btn btn-blue" href="{{url('')}}">
						<i class="icon-arrow-left"></i> Volver a la página principal
					</a>
				</div>
			</div>
		</div>
	</div>
{% endblock %}