{% extends "templates/signin.volt" %}

{% block content %}
	{{ stylesheet_link('css/prstyles.css') }}
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
						<div class="unsubscribe-contact-success">El contacto ha sido desuscrito exitosamente</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}