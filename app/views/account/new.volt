{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-sitemap"></i> Crear una nueva cuenta{%endblock%}
{%block sectionsubtitle %}Cree una cuenta asignandole al mismo tiempo un usuario administrador{% endblock %}

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
							Crear un nueva cuenta
						</div>
						<div class="news-text">
							Aqui puede crear una nueva cuenta, configurar .
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output() }}
	</div>
	<div class="row-fluid">
		{{ form('account/new', 'id': 'registerAccount', 'method': 'Post') }}
		<div class="span3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Datos de la cuenta
					</div>
				</div>
				<div class="box-content padded">
					<label for="companyName">*Nombre de la cuenta:</label>
					{{ newFormAccount.render('companyName') }}

					<label for="fileSpace">*Espacio disponible en disco (Mb):</label>
					{{ newFormAccount.render('fileSpace') }}

					<label for="contactLimit">*Limite de contactos</label>
					{{ newFormAccount.render('contactLimit') }}

					<label for="messageLimit">*Limite de mensajes</label>
					{{ newFormAccount.render('messageLimit') }}

					<label for="modeUse">*Modo de uso:</label>
					{{ newFormAccount.render('accountingMode') }}

					<label for="modeAccounting">*Modo de pago:</label>
					{{ newFormAccount.render('subscriptionMode') }}	
				</div>
			</div>
		</div>
		<div class="span3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Datos del administrador
					</div>
				</div>
				<div class="box-content padded">
					<label for="firstName">*Nombre:</label>
					{{ newFormAccount.render('firstName') }}

					<label for="lastName">*Apellido:</label>
					{{ newFormAccount.render('lastName') }}

					<label for="email">*Dirección de correo electronico:</label> 
					 {{ newFormAccount.render('email') }}

					<label for="username">*Nombre de usuario:</label>
					{{ newFormAccount.render('username') }}

					<label for="password">*Contraseña:</label>
					{{ newFormAccount.render('password') }}

					<label for="password2">*Repita la contraseña:</label>
					{{ newFormAccount.render('password2') }}
				</div>
			</div> 
		</div>
	</div>
		<a href="{{ url('account') }}" class="btn btn-default">Cancelar</a>
		{{ submit_button("Registrar", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
	</form>
{% endblock %}