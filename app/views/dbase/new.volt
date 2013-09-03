{% extends "templates/index.volt" %}

{% block content %}
<div class="row-fluid">
    <div class="row-fluid">
		<div class="span8">
			<div class="modal-header">
				<h1>Nueva Base de Datos</h1>
			</div>
		</div>
		
		<div class="span4">
			<span class="return-upper-right-corner"><a href="{{ url('emarketing/dbase') }}"><h3>Regresar</h3></a></span>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
    <div class="row-fluid">
        <form action = "{{ url('dbase/new') }}" method="post">
        <div class="row-fluid">
            <div class="span3">
                <label for="name">*Nombre</label>
            </div>
            <div class="span4">
                {{editform.render("name")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="description">*Descripcion</label>                
            </div>
            <div class="span4">
                {{editform.render("description")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="Cdescription">*Descripcion de los Contactos</label>
            </div>
            <div class="span4">
                {{editform.render("Cdescription")}}
            </div>
        </div>
    </div>
    <div class="row-fluid">
		{{submit_button("Guardar", 'class' : "btn btn-success", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
		<a href="{{ url('dbase') }}" class="btn btn-inverse">cancelar</a>
    </div>
    </form>
</div>
{% endblock %}
