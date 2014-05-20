{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"> </i> Nueva base de datos{% endblock %}
{%block sectionsubtitle %}Cree una base de datos, y administre listas de contactos{% endblock %}
{% block content %}

{# Botones de navegacion interna #}
{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}

	<div class="row">
		<h4 class="sectiontitle">Crear una nueva base de datos de contactos</h4>					
		<div class="bs-callout bs-callout-info">
			<p>
				Con las bases de datos, podrá administrar los contactos de la cuenta, en donde cada base de datos tendrá
				sus propios campos personalizados y segmentos además de sus propios contactos, esto quiere decir que un contacto
				que esté guardado en dos bases de datos distintas será contado como 2 contactos diferentes.
			</p>
			<p>
				Una vez creada la base de datos podrá configurarla
			</p>
		</div>
	</div>
	
	{{ flashSession.output() }}

	<div class="col-sm-12 hidden-md hidden-lg">
		<div class="alert alert-success">
			<div class="row">
				<div class="col-sm-2">
					<span class="glyphicon glyphicon-info-sign"></span>
				</div>
				<div class="col-md-9">
					<p>Cree una nueva base de datos, seleccione un color para identificar los contactos que pertenecen a la misma.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-7">
		<form action = "{{url('dbase/new')}}" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-sm-4 control-label">*Nombre</label>
				<div class="col-sm-8">
					{{editform.render("name")}}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">*Descripción</label>
				<div class="col-sm-8">
					{{editform.render("description")}}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">*Descripción de los contactos</label>
				<div class="col-sm-8">
					{{editform.render("Cdescription")}}
				</div>
			</div>
			<div class="form-group">	
				<label class="col-sm-4 control-label">*Color de etiqueta</label>
				<div class="col-sm-8">
					{{editform.render("color")}}
					<table id="colorchart">
						{% for color in colors %}
							<tr>
							{% for col in color %}
									<td class="color-{{col}}" bgcolor="#{{col}}"></td>
							{% endfor %}
							</tr>
						{% endfor %}
					</table>
				</div>
			</div>
			<div class="form-actions">
				<a href="{{ url('dbase') }}" class="btn btn-default btn-sm extra-padding">Cancelar</a>
				{{submit_button("Guardar", 'class' : "btn btn-guardar btn-sm extra-padding", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
			</div>
		</form>
	</div>
	<div class="hidden-xs hidden-sm col-md-5">
		<div class="alert alert-success">
			<div class="row">
				<div class="col-sm-2">
					<span class="glyphicon glyphicon-info-sign"></span>
				</div>
				<div class="col-md-9">
					<p>Cree una nueva base de datos, seleccione un color para identificar los contactos que pertenecen a la misma.</p>
				</div>
			</div>
		</div>
	</div>
<script>
	$(function() {
		$('#color').val("#FF0000");
		$('.color-FF0000').addClass('color-selected');
		$('#colorchart td').on('click', function() {
			$('.color-selected').removeClass('color-selected');
			$(this).addClass('color-selected');
			$('#color').val($(this)[0].bgColor);
		});
	});
</script>
{% endblock %}
