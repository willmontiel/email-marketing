{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"> </i> Nueva base de datos{% endblock %}
{%block sectionsubtitle %}Cree una base de datos, y administre listas de contactos{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="box">
			<div class="box-section news with-icons">
				<div class="avatar purple">
					<i class="icon-book icon-2x"></i>
				</div>
				<div class="news-content">
					<div class="news-title">
						Crear una nueva base de datos de contactos
					</div>
					<div class="news-text">
						<p>
							Con las bases de datos, podrá administrar los contactos de la cuenta, en donde cada base de datos tendrá
							sus propios campos personalizados y segmentos ademas de sus propios contactos, esto quiere decir que un contacto
							que este guardado en dos bases de datos distintas será contado como 2 contactos diferentes.
						</p>
						<p>
							Una vez creada la base de datos podrá confugurarla
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output() }}
	</div>
    <div class="row-fluid span4">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Crear una base de datos
				</div>
			</div>
			<div class="box-content">
				<form action = "{{url('dbase/new')}}" method="post">
					<div class="padded">
						<label>*Nombre</label>
						{{editform.render("name")}}

						<label>*Descripcion</label>                
						{{editform.render("description")}}

						<label>*Descripcion de los Contactos</label>
						{{editform.render("Cdescription")}}
					</div>
					<div class="form-actions">
						<a href="{{ url('dbase') }}" class="btn btn-default">Cancelar</a>
						{{submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}
