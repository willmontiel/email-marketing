{% extends "templates/index_b3.volt" %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h4 class="sectiontitle">Crear un nuevo usuario en la cuenta <strong>{{account.companyName}}</strong></h4>
			<div class="bs-callout bs-callout-info">
				Cree un nuevo usuario. Créelo a partir de los datos más básicos, agregue un nombre 
				de usuario, una dirección de correo electrónico, una contraseña y asígnele permisos de administración
				en la cuenta.
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			{{ flashSession.output() }}
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h4>Crear un nuevo usuario</h4>
			<form action="{{url('account/newuser/')}}{{account.idAccount}}" method="Post" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="firstName" class="col-sm-3 control-label">*Nombre:</label>
					<div class="col-sm-5">
						{{ UserForm.render('firstName', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="lastName" class="col-sm-3 control-label">*Apellido:</label>
					<div class="col-sm-5">
						{{ UserForm.render('lastName', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="email" class="col-sm-3 control-label">*Dirección de correo electrónico:</label>
					<div class="col-sm-5">
						{{ UserForm.render('email', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="username" class="col-sm-3 control-label">*Nombre de usuario:</label>
					<div class="input-group col-sm-5" style="padding-left: 15px !important; padding-right: 15px !important;">
						<span class="input-group-addon">{{prefix}}</span>
						{{ UserForm.render('username', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="password" class="col-sm-3 control-label">*Contraseña:</label>
					<div class="col-sm-5">
						{{ UserForm.render('password', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="password2" class="col-sm-3 control-label">*Repita la contraseña:</label>
					<div class="col-sm-5">
						{{ UserForm.render('password2', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group">
					<label for="userrole" class="col-sm-3 control-label">*Funciones:</label>
					<div class="col-sm-5">
						{{ UserForm.render('userrole', {'class':'form-control'}) }}
					</div>
				</div>
					
				<div class="form-group wrapper">
					<div class="col-md-4 col-md-offset-4">
						<a href="{{ url('account/show/')}}{{account.idAccount}}" class="btn btn-sm btn-default extra-padding">Cancelar</a>
						{{ submit_button("Grabar", 'class' : "btn btn-sm btn-default btn-guardar extra-padding", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
					</div>
				</div>
			</form>
		</div>
	</div>
{% endblock %}