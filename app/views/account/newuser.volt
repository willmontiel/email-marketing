{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de cualquier cuenta{% endblock %}

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
							Crear un nuevo usuario en la cuenta <span class="label label-green">{{account.companyName}}</span>
						</div>
						<div class="news-text">
							Aqui podrá crear un nuevo usuario. Creelo a partir de los datos más básicos, agregue un nombre 
							de usuario, una dirección de correo electrónico, una contraseña y asignele permisos de adminsitración
							en la cuenta.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid text-right">
		<a href="{{url('account/index')}}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
	</div>
	<br />
	{{ flashSession.output() }}
	<br />
	<div class="row-fluid">
		<div class="span4">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Crear un nuevo usuario
					</div>
				</div>
				<div class="box-content padded">
					<form action="{{url('account/newuser/')}}{{account.idAccount}}" method="Post">
						<label>*Nombre </label>
						{{ NewUserForm.render('firstName') }}

						<label>*Apellido </label>
						{{ NewUserForm.render('lastName') }}

						<label>*Dirección de correo electrónico </label>
						{{ NewUserForm.render('email') }}

						<label>*Nombre de usuario </label>
						{{ NewUserForm.render('username') }}

						<label>*Contraseña </label>
						{{ NewUserForm.render('password') }}

						<label>*Repita la contraseña </label>
						{{ NewUserForm.render('password2') }}	

						<label>*Funciones </label>
						{{ NewUserForm.render('userrole') }}	
						
						<br />
						
						{{ submit_button("Crear", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
						<a href="{{url('account/show/')}}{{account.idAccount}}" class="btn btn-default">Cancelar<a>
					</form>
					
				</div>
			</div>
		</div>
	</div>
{% endblock %}