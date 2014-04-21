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
		<div class="row">
			<div class="span8 offset2">
				<div class="padded">
					<div class="login box" style="margin-top: 80px;">
						<div class="box-header">
							<span class="title">Des-suscribirse</span>
						</div>
				{% if contact%}
					{% if dbase%}
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
					{% else %}
						<div class="box-content padded">
							<ul class="chat-box timeline">
								<li class="arrow-box-left gray">
									<div class="avatar blue"><i class="icon-circle-blank icon-2x"></i></div>
									<div class="info">
										<span class="name">
										  <span class="label label-gray">{{email.email}}</span>
										</span>
										<span class="time"><i class="icon-briefcase"></i> Compañia <strong>{{account.companyName}}</strong></span>
									</div>
									<div class="content">
										<blockquote style="font-size: 14px;">El contacto con nombres {{contact.name}} {{contact.lastName}} ya se encuentra desuscrito, para suscribirlo de nuevo contacte con la compañia indicada en la parte superior izquierda</blockquote>
									</div>
								</li>
							 </ul>
						</div>
					{%endif%}
				{% else %}
					<div class="box-content padded">
						<ul class="chat-box timeline">
							<li class="arrow-box-left gray">
								<div class="avatar blue"><i class="icon-circle-blank icon-2x"></i></div>
								<div class="info">
									<span class="name" style="font-size: 12px;">
									  El contacto indicado ya no existe en la base de datos
									</span>
									<span class="time" style="font-size: 12px;"><i class="icon-briefcase"></i> Compañia <strong>{{account.companyName}}</strong></span>
								</div>
							</li>
						 </ul>
					</div>
				{%endif%}
					</div>
				</div>	
			</div>
		</div>
	</div>
{% endblock %}
