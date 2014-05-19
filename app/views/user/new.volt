{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de su cuenta{% endblock %}

{% block content %}
	<div class="row">
		<h4 class="sectiontitle">Crear un nuevo usuario</h4>
					
		{{ flashSession.output() }}	

		<div class="col-md-5">
			<form action = "{{url('user/new/')}}" id="createUser" method="Post" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="firstName" class="col-sm-4 control-label"><span class="required">*</span>Nombre:</label>
					<div class="col-md-8">
						{{ UserForm.render('firstName') }}
					</div>
				</div>
				<div class="form-group">
					<label for="lastName" class="col-sm-4 control-label"><span class="required">*</span>Apellido:</label>
					<div class="col-md-8">
						{{ UserForm.render('lastName') }}
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label"><span class="required">*</span>Dirección de correo electrónico:</label>
					<div class="col-md-8">
						{{ UserForm.render('email') }}
					</div>
				</div>
				<div class="form-group">
					<label for="username" class="col-sm-4 control-label"><span class="required">*</span>Nombre de usuario:</label>
					<div class="col-md-8">
						{{ UserForm.render('username') }}
					</div>
				</div>
				<div class="form-group">
					<label for="passForEdit" class="col-sm-4 control-label"><span class="required">*</span>Contraseña:</label>
					<div class="col-md-8">
						{{ UserForm.render('password') }}
					</div>
				</div>
				<div class="form-group">
					<label for="pass2ForEdit" class="col-sm-4 control-label"><span class="required">*</span>Repita la contraseña:</label>
					<div class="col-md-8">
						{{ UserForm.render('password2') }}	
					</div>
				</div>
				<div class="form-group">
					<label for="userrole" class="col-sm-4 control-label"><span class="required">*</span>Funciones:</label>
					<div class="col-md-8">
						{{ UserForm.render('userrole') }}	
					</div>
				</div>

				<div class="form-actions pull-right">
					<div class="col-xs-6">
						<a href="{{ url('user') }}" class="btn btn-sm btn-default extra-padding">Cancelar</a>
					</div>
					<div class="col-xs-6">
						{{ submit_button("Grabar", 'class' : "btn btn-sm btn-default btn-guardar extra-padding", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-6">
			<div class="alert alert-success">
				<div class="row">
					<div class="col-sm-2">
						<span class="glyphicon glyphicon-info-sign"></span>
					</div>
					<div class="col-md-9">
						<p>Cree un nuevo usuario</p>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}