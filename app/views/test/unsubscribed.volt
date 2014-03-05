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
				<div class="padded">
					<div class="login box" style="margin-top: 80px;">
						<div class="box-header">
							<span class="title">Des-suscribirse</span>
						</div>
						<div class="box-content padded">
							¿Esta seguro que desea desuscribirse? <br /><br />
							El correo electrónico esta relacionado con la base de datos <strong>American Fans Arsenal</strong>
							y esta suscrito a la lista <strong>Boletín semanal de resultados para america latina</strong><br /><br />
							
							compañia <strong>Arsenal FC</strong>
							

							<br /><br />
							<div class="table table-bordered span8 offset2">
								<table>
									<tr>
										<td>Email</td>
										<td><strong>pepitoperez@live.com</strong></td>
									</tr>
									<tr>
										<td>Nombre</td>
										<td><strong>Pepito</strong></td>
									</tr>
									<tr>
										<td>Apellido</td>
										<td><strong>Perez</strong></td>
									</tr>
								</table>
							</div>

							<br /><br /><br />
							<center><a href="#" class="btn btn-blue"><i class="icon-warning-sign"></i> Desuscribirse</a></center>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
{% endblock %}