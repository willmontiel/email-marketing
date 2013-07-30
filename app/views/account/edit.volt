{% extends "templates/index.volt" %}

{% block content %} 
<div class="span12">
		<h1>Editar información de las cuentas</h1>
	</div>	{{ content() }}
	
	<div class="span12"></div>	
	
	<div class="span5">	
		<form action = "/emarketing/account/edit/<?php echo $allAccount->idAccount; ?>" id="registerAccount" method="post">
			<p>
				Nombre de la cuenta: 
				{{ editFormAccount.render('companyName') }}
			</p>

			<p>
				Cantidad de trafico de archivos (Mb):
				{{ editFormAccount.render('fileSpace') }}
			</p>

			<p>
				Limite de mensajes/contactos:
				{{ editFormAccount.render('messageQuota') }}
			</p>

			<p>
				Modo de uso:
				{{ editFormAccount.render('modeUse') }}
			</p>
			
			<p>
				Modo de Pago: 
				{{ editFormAccount.render('modeAccounting') }}
			</p>

			<p>
				{{ submit_button("Registrar", 'class' : "btn btn-success") }}
				{{link_to('account', 'class':"btn btn-inverse", "Regresar")}}
			</p>
		</form>
	</div>
{% endblock %}