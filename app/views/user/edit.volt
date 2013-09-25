{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de su cuenta{% endblock %}

{% block content %}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar blue">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Editar el usuario <span class="label label-black">{{user.username}}</span>
						</div>
						<div class="news-text">
							Aqui podrá editar la información de los usuarios que tiene en la cuenta, actualice toda
							la información que necesite como el nombre de usuario, la contraseña, la dirección de correo
							electrónico, etc.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br />
	{{ flashSession.output() }}
	<br />
	<div class="row-fluid">
		<div class="span3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Editar un usuario
					</div>
				</div>
				<div class="box-content padded">
					<form action = "{{url('user/edit/')}}{{user.idUser}}" method="post">
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
						
						<br />
						
						{{ submit_button("Editar", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
						<a href="{{ url('user/index') }}" class="btn btn-default">Cancelar<a>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}