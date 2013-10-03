{% extends "templates/index_new.volt" %}
{% block sectiontitle %}
	<i class="icon-book"></i> {{edbase.name}}
{% endblock %}
{%block sectionsubtitle %} {{edbase.description}} {% endblock %}
{% block content %}
{{ content() }}
	<div class="row-fluid">
		{{ flashSession.output() }}
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
						<a href="{{ url('dbase/show/') }}{{edbase.idDbase}}" class="btn btn-default">Cancelar</a>
						{{submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
