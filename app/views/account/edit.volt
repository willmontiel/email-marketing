{% extends "templates/index.volt" %}

{% block content %} 
<div class="span12">
		<h1>Editar información de las cuentas</h1>
	</div>	{{ content() }}
	
	<div class="span12"></div>	
	
	<div class="span5">	
		<form action = "/emarketing/account/edit/<?php echo $allAccount->idAccount; ?>" id="registerAccount" method="post">
			<p>
				*Nombre de la cuenta: 
				{{ editFormAccount.render('companyName') }}
			</p>

			<p>
				*Cantidad de trafico de archivos (Mb):
				{{ editFormAccount.render('fileSpace') }}
			</p>

			<p>
				*Limite de contactos:
				{{ editFormAccount.render('contactLimit') }}
			</p>
			
			<p>
				*Limite de mensajes:
				{{ editFormAccount.render('messageLimit') }}
			</p>

			<p>
				*Modo de uso:
				{{ editFormAccount.render('accountingMode') }}
			</p>
			
			<p>
				*Modo de Pago: 
				{{ editFormAccount.render('subscriptionMode') }}
			</p>

			<p>
				{{ submit_button("Editar", 'class' : "btn btn-success", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides") }}
				<button {{ url('account/index') }} class="btn btn-inverse">Regresar</button>
			</p>
		</form>
	</div>
{% endblock %}