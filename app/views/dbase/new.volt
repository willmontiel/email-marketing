{% extends "templates/index.volt" %}

{% block content %}
{{ content() }}
<div class="row-fluid">
    <div class="row-fluid">
		<div class="span8">
			<div class="modal-header">
				<h1>Nueva Base de Datos</h1>
			</div>
		</div>
		
		<div class="span4">
			<span class="return-upper-right-corner"><a href="/emarketing/dbase"><h3>Regresar</h3></a></span>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
    <div class="row-fluid">
        <form action = "/emarketing/dbase/new", method="post">
        <div class="row-fluid">
            <div class="span3">
                <label for="name">Nombre</label>
            </div>
            <div class="span4">
                {{editform.render("name")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="description">Descripcion</label>                
            </div>
            <div class="span4">
                {{editform.render("description")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="Cdescription">Descripcion de los Contactos</label>
            </div>
            <div class="span4">
                {{editform.render("Cdescription")}}
            </div>
        </div>
    </div>
    <div class="row-fluid">
		{{submit_button("Guardar", 'class' : "btn btn-success")}}
		{{link_to('dbase', 'class':"btn btn-inverse", "Cancelar")}}
    </div>
    </form>
</div>
{% endblock %}