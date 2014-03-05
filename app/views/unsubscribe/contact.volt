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
				{% if contact%}
					{% if dbase%}
						<div class="padded">
							<div class="login box" style="margin-top: 80px;">
								<div class="box-header">
									<span class="title">Des-suscribirse</span>
								</div>
								<div class="box-content padded">
									¿Esta seguro que desea desuscribirse? <br /><br />
									El correo electrónico esta relacionado con la base de datos <strong>{{dbase.name}}</strong>
									y esta suscrito a la lista <strong>{{dbase.Cdescription}}</strong><br /><br />

									compañia <strong>{{account.companyName}}</strong>

									<br /><br />
									<div class="table table-bordered span8 offset2">
										<table>
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
									</div>

									<br /><br /><br />
									<center><a href="{{url('unsubscribe/success')}}/{{parameters}}" class="btn btn-blue"><i class="icon-warning-sign"></i> Desuscribirse</a></center>
								</div>
							</div>
						</div>	
					{% else %}
						<div class="message-small">El contacto {{contact.name}} {{contact.lastName}} ({{email.email}}) ya se encuentra desuscrito</div>
					{%endif%}
				{% else %}
					<div class="message-small">El contacto no existe</div>	
				{%endif%}
			</div>
		</div>
	</div>
{% endblock %}
