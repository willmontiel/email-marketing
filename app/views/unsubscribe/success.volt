{% extends "templates/signin.volt" %}
{% block content %}
	{{ stylesheet_link('css/prstyles.css') }}
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<!-- Brand and toggle get grouped for better mobile display -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{url('')}}">Email Sigma</a>
			</div>
		</div>
	</nav>
	
	<div class="container">
		<div class="row">
			<div class="bs-callout bs-callout-success">
				<h4>Contacto <strong>des-suscrito</strong> exitosamente</h4>
				<p>
					El contacto ha sido des-suscrito exitosamente
				</p>
			</div>
		</div>
	</div>
{% endblock %}