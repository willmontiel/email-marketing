{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-edit"></i> Editar información de las cuentas{%endblock%}
{%block sectionsubtitle %}Edite la configuración de la cuenta{% endblock %}

{% block content %}
	<div class="row">
		<h4 class="sectiontitle">Edición de la cuenta</h4>
		<div class="bs-callout bs-callout-info">
			<p>Edite datos de cuentas o reconfigure.</p>
		</div>
	</div>	

	{{ flashSession.output() }}

		<div clas="col-md-6">
			<form action = "{{url('account/edit/')}}{{allAccount.idAccount}}" class="form-horizontal" id="registerAccount" method="post" role="form">
				<div class="form-group">
					<label class="col-md-4 control-label">*Nombre de la cuenta: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('companyName', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Espacio disponible en disco(Mb):</label>
					<div class="col-md-6">
						{{ editFormAccount.render('fileSpace', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Limite de contactos:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('contactLimit', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Limite de mensajes:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('messageLimit', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Modo de uso:</label>
					<div class="col-md-6">
						{{ editFormAccount.render('accountingMode', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4 control-label">*Modo de Pago: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('subscriptionMode', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">					
					<label class="col-md-4 control-label">*MTA: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('virtualMta', {'class': 'form-control'}) }}
					</div>
				</div>
				<div class="form-group">					
					<label class="col-md-4 control-label">*Url de dominio: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('idUrlDomain', {'class': 'form-control'})}}
					</div>
				</div>
				<div class="form-group">								
					<label class="col-md-4 control-label">*Retornar correos rebotados a: </label>
					<div class="col-md-6">
						{{ editFormAccount.render('idMailClass', {'class': 'form-control'})}}
				</div>
				<div class="form-actions pull-right">
					<div class="row">
						<div class="col-xs-6">
							<a href="{{ url('account') }}" class="btn btn-default btn-sm extra-padding">Cancelar</a>
						</div>
						<div class="col-xs-6">
							{{ submit_button("Grabar", 'class' : "btn btn-default btn-guardar extra-padding", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides") }}
						</div>
					</div>
				</div>
			</form>
		</div>
{% endblock %}