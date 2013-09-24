{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"> </i> Nueva base de datos{% endblock %}
{%block sectionsubtitle %}Cree una base de datos, y administre listas de contactos{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span12 text-right">
			<a href="{{url('dbase')}}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
		</div>
	</div>
	<br />
    <div class="row-fluid span3">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Crear una base de datos
				</div>
			</div>
			<div class="box-content padded">	
				<form action = "{{url('dbase/new')}}" method="post">
					<label>*Nombre</label>
					{{editform.render("name")}}
					
					<label>*Descripcion</label>                
					{{editform.render("description")}}
					
					<label>*Descripcion de los Contactos</label>
					{{editform.render("Cdescription")}}
					<div class="form-actions">
						{{submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
						<a href="{{ url('dbase') }}" class="btn btn-default">cancelar</a>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}
