{% extends "templates/index_new.volt" %}
{% block sectiontitle %}
	<i class="icon-book"></i> {{edbase.name}}
{% endblock %}
{%block sectionsubtitle %} {{edbase.description}} {% endblock %}
{% block content %}
{{ content() }}
	<div class="row-fluid">
		<div class="span3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Editar una base de datos
					</div>
				</div>
				<div class="box-content padded">
					<form action = "{{url('dbase/edit')}}/{{edbase.idDbase}}" method="post">
						<label for="name">*Nombre</label>
						{{editform.render("name")}}
								
						<label for="description">*Descripcion</label>                
						{{editform.render("description")}}
						
						<label for="Cdescription">*Descripcion de los Contactos</label>
						{{editform.render("Cdescription")}}
						<br />	
						{{submit_button("Guardar", 'class' : "btn btn-default", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
						<a href="{{ url('dbase') }}" class="btn btn-default">Cancelar</a>
					</form>
				</div>
			</div>
		</div>
{% endblock %}
