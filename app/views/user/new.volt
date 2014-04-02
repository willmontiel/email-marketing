{% extends "templates/index_b3.volt" %}
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
							Crear un nuevo usuario
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
	<div class="row-fluid">
		<div class="span8">
			{{ flashSession.output() }}
		</div>
		<div class="span4 text-right">
			<a href="{{url('user/index')}}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span4">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Crear un nuevo usuario
					</div>
				</div>
				<div class="box-content">
					{{ form('user/new', 'id': 'createUser', 'method': 'Post') }}
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
							{{ UserForm.render('password') }}

							<label>*Repita la contraseña </label>
							{{ UserForm.render('password2') }}	

							<label>*Funciones </label>
							{{ UserForm.render('userrole') }}
						</div>
						<div class="form-actions">
							<a href="{{ url('user/index') }}" class="btn btn-default">Cancelar</a>
							{{ submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
{% endblock %}