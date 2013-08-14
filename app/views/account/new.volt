{% extends "templates/index.volt" %}
{% block content %}
<div class="row-fluid">
		<h1>Crear una nueva cuenta</h1>
</div>
 <div class="span12">
	<div class="row-fluid">
		<div class="span5">
		{{ form('account/new', 'id': 'registerAccount', 'method': 'Post') }}

			<fieldset>
				<legend>Datos de la cuenta</legend>

				<p>
				 <label for="companyName">*Nombre de la cuenta:</label>
				  {{ newFormAccount.render('companyName') }}
				</p>

				<p>
				 <label for="fileSpace">*Espacio disponible en disco (Mb):</label>
				 {{ newFormAccount.render('fileSpace') }}
				</p>

				<p>
				 <label for="messageQuota">*Limite de mensajes/contactos</label>
				 {{ newFormAccount.render('messageQuota') }}
				</p>

				<p>
				 <label for="modeUse">*Modo de uso:</label>
				 {{ newFormAccount.render('accountingMode') }}
				</p>

				<p>
				 <label for="modeAccounting">*Modo de pago:</label>
				 {{ newFormAccount.render('subscriptionMode') }}
				</p>	
			</fieldset>
		</div>
     
		<div class="span1"></div>
    
		<div class="span5">
			<fieldset>
				<legend>Datos del administrador</legend>
				<p>
				 <label for="firstName">*Nombre:</label>
				 {{ newFormAccount.render('firstName') }}
				</p>

				<p>
				 <label for="lastName">*Apellido:</label>
				 {{ newFormAccount.render('lastName') }}
				</p>

				<p>
				 <label for="email">*Dirección de correo electronico:</label> 
				  {{ newFormAccount.render('email') }}
				</p>

				<p>
				 <label for="username">*Nombre de usuario:</label>
				 {{ newFormAccount.render('username') }}
				</p>

				<p>
				 <label for="password">*Contraseña:</label>
				 {{ newFormAccount.render('password') }}
				</p>

				<p>
				 <label for="password2">*Repita la contraseña:</label>
				 {{ newFormAccount.render('password2') }}
				</p>
			</fieldset>
		</div>
	</div>

      <p>
		{{ submit_button("Registrar", 'class' : "btn btn-success", 'data-toggle':"tooltip", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides") }}
		{{link_to('account', 'class':"btn btn-inverse", "Cancelar")}}
      </p>
   </form>
   </div>
  </div>  
{% endblock %}