{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de cualquier cuenta{% endblock %}

{% block content %}
	{{ flashSession.output() }}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar blue">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Editar o actualizar información del usuario <span class="label label-gray">{{user.username}}</span>
						</div>
						<div class="news-text">
							Aqui podrá editar o actualizar información de cualquier usuario de cualquiera de las cuentas.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8">
			{{ flashSession.output() }}
		</div>
		<div class="span4 text-right">
			<a href="{{url('account/index')}}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span4">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Editar un usuario
					</div>
				</div>
				<div class="box-content">
					<form action="{{url('account/edituser/')}}{{user.idUser}}" method="Post">
						<div class="padded">
							<label>*Nombre </label>
							{{ UserForm.render('firstName') }}

							<label>*Apellido </label>
							{{ UserForm.render('lastName') }}

							<label>*Dirección de correo electrónico </label>
							{{ UserForm.render('email') }}

							<label>*Nombre de usuario </label>
							{{ UserForm.render('username') }}

							<label>*Contraseña </label>
							{{ UserForm.render('passForEdit') }}

							<label>*Repita la contraseña </label>
							{{ UserForm.render('pass2ForEdit') }}	

							<label>*Funciones </label>
							{{ UserForm.render('userrole') }}	
						</div>
						<div class="form-actions">
							<a href="{{ url('account/show/')}}{{user.idAccount}}" class="btn btn-default">Cancelar</a>
							{{ submit_button("Grabar", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
{% endblock %}
