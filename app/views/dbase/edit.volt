{% extends "templates/index_new.volt" %}
{% block sectiontitle %}
	<i class="icon-book"></i> {{edbase.name}}
{% endblock %}
{%block sectionsubtitle %} {{edbase.description}} {% endblock %}
{% block content %}
{{ content() }}
	<div class="row-fluid">
		<div class="box">
			<div class="box-section news with-icons">
				<div class="avatar purple">
					<i class="icon-book icon-2x"></i>
				</div>
				<div class="news-content">
					<div class="news-title">
						Edite una base de datos de contactos
					</div>
					<div class="news-text">
						<p>
							Con las bases de datos, podrá administrar los contactos de la cuenta, en donde cada base de datos tendrá
							sus propios campos personalizados y segmentos ademas de sus propios contactos, esto quiere decir que un contacto
							que este guardado en dos bases de datos distintas será contado como 2 contactos diferentes.
						</p>
						<p>
							Una vez editada la base de datos no afectará la configuración si ya la ha realizado.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output() }}
	</div>
	<div class="row-fluid">
		<div class="span3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Editar una base de datos
					</div>
				</div>
				<div class="box-content">
					<form action = "{{url('dbase/edit')}}/{{edbase.idDbase}}" method="post">
						<div class="padded">
							<label for="name">*Nombre</label>
							{{editform.render("name")}}

							<label for="description">*Descripcion</label>                
							{{editform.render("description")}}

							<label for="Cdescription">*Descripcion de los Contactos</label>
							{{editform.render("Cdescription")}}
							
							<label>*Color de Etiqueta</label>
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
						<div class="form-actions">
							<a href="{{ url('dbase/show/') }}{{edbase.idDbase}}" class="btn btn-default">Cancelar</a>
							{{submit_button("Guardar", 'class' : "btn btn-blue", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
						</div>	
					</form>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
			setColor();
			$('#colorchart td').on('click', function() {
				$('.color-selected').removeClass('color-selected');
				$(this).addClass('color-selected');
				$('#color').val($(this)[0].bgColor);
			});

			function setColor() {
				if($('#color').val() === "") {
					$('#color').val("#FF0000");
					$('.color-FF0000').addClass('color-selected');
				}
				else {
					$('.color-' + $('#color').val().replace('#', '')).addClass('color-selected');
				}
			}
		});
	</script>
{% endblock %}
