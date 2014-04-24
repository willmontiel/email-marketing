{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"> </i> Nueva base de datos{% endblock %}
{%block sectionsubtitle %}Cree una base de datos, y administre listas de contactos{% endblock %}
{% block content %}
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
	<div class="row">
		{{ flashSession.output() }}
	</div>
	<div class="col-md-5">
		<form action = "{{url('dbase/new')}}" method="post">
			<div class="for-group">
				<label class="form-control">*Nombre</label>
				<div class="col-sm-5">
					{{editform.render("name")}}
				</div>
			</div>
			<div class="for-group">
				<label class="form-control">*Descripcion</label>
				<div class="col-sm-5">
					{{editform.render("description")}}
				</div>
			<div class="for-group">
				<label class="form-control">*Descripcion de los Contactos</label>
				<div class="col-sm-5">
					{{editform.render("Cdescription")}}
				</div>
			<div class="for-group">	
				<label class="form-control">*Color de Etiqueta</label>
				<div class="col-sm-5">
					{{editform.render("color")}}
				</div>
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
			<div class="form-actions">
				<a href="{{ url('dbase') }}" class="btn btn-default">Cancelar</a>
				{{submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
			</div>
		</form>
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
