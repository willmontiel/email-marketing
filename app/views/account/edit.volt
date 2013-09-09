{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-edit"></i> Editar información de las cuentas{%endblock%}
{% block content %} 
<div class="container-fluid padded">
	{{ content() }}
	<div class="box span3">
		<div class="box-header">
			<div class="title">
				Editar datos de la cuenta
			</div>
		</div>
		<div class="box-content padded">
			<form action = "{{url('account/edit/')}}{{allAccount.idAccount}}" id="registerAccount" method="post">
				<label>*Nombre de la cuenta: </label>
				{{ editFormAccount.render('companyName') }}
			
				<label>*Cantidad de trafico de archivos (Mb):</label>
				{{ editFormAccount.render('fileSpace') }}

				<label>*Limite de contactos:</label>
				{{ editFormAccount.render('contactLimit') }}
			
				<label>*Limite de mensajes:</label>
				{{ editFormAccount.render('messageLimit') }}

				<label>*Modo de uso:</label>
				{{ editFormAccount.render('accountingMode') }}
				
				<label>*Modo de Pago: </label>
				{{ editFormAccount.render('subscriptionMode') }}
				
				<br />
				{{ submit_button("Editar", 'class' : "btn btn-success", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides") }}
				<button {{ url('account/index') }} class="btn btn-inverse">Regresar</button>
			</form>
		</div>
	</div>
</div>
{% endblock %}