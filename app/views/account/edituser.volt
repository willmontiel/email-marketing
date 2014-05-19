{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de cualquier cuenta{% endblock %}

{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}
		</div>
	</div>

	<div class="row">
		<h4 class="sectiontitle">Editar o actualizar información del usuario</h4>
		<div class="bs-callout bs-callout-info">
			Aquí podrá editar o actualizar información de cualquier usuario de cualquiera de las cuentas.
		</div>
	</div>

	<div class="row">
		{{ flashSession.output() }}
	</div>


	<div class="row">
		<form action="{{url('account/edituser/')}}{{user.idUser}}" method="Post" class="form-horizontal" role="form">
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
				<div class="col-sm-5">
					{{ UserForm.render('username', {'class':'form-control'}) }}
				</div>
			</div>
				
			<div class="form-group">
				<label for="passForEdit" class="col-sm-3 control-label">*Contraseña:</label>
				<div class="col-sm-5">
					{{ UserForm.render('passForEdit', {'class':'form-control'}) }}
				</div>
			</div>
				
			<div class="form-group">
				<label for="pass2ForEdit" class="col-sm-3 control-label">*Repita la contraseña:</label>
				<div class="col-sm-5">
					{{ UserForm.render('pass2ForEdit', {'class':'form-control'}) }}
				</div>
			</div>
				
			<div class="form-group">
				<label for="userrole" class="col-sm-3 control-label">*Funciones:</label>
				<div class="col-sm-5">
					{{ UserForm.render('userrole', {'class':'form-control'}) }}
				</div>
			</div>
				
			<div class="form-actions col-md-3 col-md-offset-3">
				<div class="col-xs-6">
					<a href="{{ url('account/show/')}}{{user.idAccount}}" class="btn btn-sm btn-default extra-padding">Cancelar</a>
				</div>
				<div class="col-xs-6">
					{{ submit_button("Grabar", 'class' : "btn btn-sm btn-default btn-guardar extra-padding", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
				</div>
			</div>
		</form>
	</div>
{% endblock %}
