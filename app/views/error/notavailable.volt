{% extends "templates/signin.volt" %}

{% block content %}
	<div class="navbar navbar-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="#">Mail Station</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="span8 offset2">
				<div class="error-box">
					<div class="message-small">Lo sentimos, en estos momentos la plataforma no se encuentra disponible</div>
					<div class="message-big">503</div>
					<br />
					<i class="icon-cogs"></i> Estamos realizando mantenimiento a nuestros servidores para mejorar nuestro servicio, agradecemos su pacencia.
				</div>
			</div>
		</div>
	</div>
{% endblock %}