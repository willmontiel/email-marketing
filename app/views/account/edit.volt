{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-edit"></i> Editar información de las cuentas{%endblock%}
{%block sectionsubtitle %}Edite la configuración de la cuenta{% endblock %}

{% block content %} 
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar green">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Edite información de las cuentas
						</div>
						<div class="news-text">
							Edite datos de cuentas o reconfigure.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output() }}
	</div>
	<div class="row-fluid padded">
		<div class="box span4">
			<div class="box-header">
				<div class="title">
					Editar datos de la cuenta
				</div>
			</div>
			<div class="box-content">
				<form action = "{{url('account/edit/')}}{{allAccount.idAccount}}" id="registerAccount" method="post">
					<div class="padded">
						<label>*Nombre de la cuenta: </label>
						{{ editFormAccount.render('companyName') }}

						<label>*Espacio disponible en disco(Mb):</label>
						{{ editFormAccount.render('fileSpace') }}

						<label>*Limite de contactos:</label>
						{{ editFormAccount.render('contactLimit') }}

						<label>*Limite de mensajes:</label>
						{{ editFormAccount.render('messageLimit') }}

						<label>*Modo de uso:</label>
						{{ editFormAccount.render('accountingMode') }}

						<label>*Modo de Pago: </label>
						{{ editFormAccount.render('subscriptionMode') }}
						
						<label>*MTA: </label>
						{{ editFormAccount.render('virtualMta') }}
						
						<label>*Url de dominio: <label/>
						{{ editFormAccount.render('idUrlDomain')}}<br /> <br />
									
						<label>*Retornar correos rebotados a: <label/>
						{{ editFormAccount.render('idMailClass')}}
					</div>
					<div class="form-actions">
						<a href="{{ url('account') }}" class="btn btn-default">Cancelar</a>
						{{ submit_button("Grabar", 'class' : "btn btn-blue", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides") }}
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}