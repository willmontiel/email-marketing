{% extends "templates/signin.volt" %}

{% block content %}
	{{ stylesheet_link('css/prstyles.css') }}

	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{url('')}}">Email Sigma</a>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="sectiontitle">Des-suscribirse</h4>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				{% if contact%}
					{% if dbase%}
						<div class="wrapper">
							<p>¿Esta seguro que desea desuscribirse? </p>
							
							<p>
								Si se des-suscribe ya no recibirá boletines por parte de la compañía <strong>{{account.companyName}}</strong>
							</p>

							<table class="table table-bordered">
								<tr>
									<td>Email</td>
									<td><strong>{{email.email}}</strong></td>
								</tr>
								<tr>
									<td>Nombre</td>
									<td><strong>{{contact.name}}</strong></td>
								</tr>
								<tr>
									<td>Apellido</td>
									<td><strong>{{contact.lastName}}</strong></td>
								</tr>
							</table>
							
							<div class="text-center">
								<a href="{{url('unsubscribe/success')}}/{{parameters}}" class="btn btn-sm btn-default extra-padding">Desuscribirse</a>
							</div>
						</div>
					{% else %}
						<div class="bs-callout bs-callout-info">
							El contacto con nombre {{contact.name}} {{contact.lastName}} ya se encuentra desuscrito, para suscribirlo de nuevo contacte con la compañia <strong>{{account.companyName}}</strong>
						</div>
					{% endif %}
				{% else %}
					<div class="bs-callout bs-callout-warning">
						<p>
							El contacto indicado ya no existe en la base de datos
						</p>
						<p>
							Compañia <strong>{{account.companyName}}</strong>
						</p>
					</div>
				{% endif %}
			</div>
		</div>		
	</div>
{% endblock %}
