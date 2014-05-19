{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}
	<i class="icon-book"></i> {{edbase.name}}
{% endblock %}
{%block sectionsubtitle %} {{edbase.description}} {% endblock %}
{% block content %}

{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}

{{ content() }}
	<div class="row">
		<h4 class="sectiontitle">Editar una base de datos</h4>
		<div class="bs-callout bs-callout-info">
			<p>
				A través de las bases de datos, podrá administrar los contactos de la cuenta, cada base de datos tendrá
				sus propios campos personalizados y segmentos ademas de sus propios contactos, esto quiere decir que un contacto
				que esté guardado en dos bases de datos distintas será contado como 2 contactos diferentes.
			</p>
			<p>
				Una vez editada la base de datos no afectará la configuración si ya la ha realizado.
			</p>
		</div>
		<div class="row">
			{{ flashSession.output() }}
		</div>
		<div class="row">
			<div class="col-md-5">
				<form action = "{{url('dbase/edit')}}/{{edbase.idDbase}}" method="post" role="form" class="form-horizontal">
					<div class="form-group">
						<label for="name" class="col-md-5 control-label">*Nombre</label>
						<div class="col-md-7">
							{{editform.render("name")}}
						</div>
					</div>
					<div class="form-group">
						<label for="description" class="col-md-5 control-label">*Descripción</label>
						<div class="col-md-7">       
							{{editform.render("description")}}
						</div>
					</div>
					<div class="form-group">
						<label for="Cdescription" class="col-md-5 control-label">*Descripción de los contactos</label>
						<div class="col-md-7">
							{{editform.render("Cdescription")}}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-5 control-label">*Color de etiqueta</label>
						<div class="col-md-7">
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
					<div class="form-actions pull-right">
						<div class="row">
							<div class="col-xs-6">
								<a href="{{ url('dbase/show/') }}{{edbase.idDbase}}" class="btn btn-default btn-sm">Cancelar</a>
							</div>
							<div class="col-xs-6">
								{{submit_button("Guardar", 'class' : "btn btn-default btn-guardar btn-sm", 'data-toggle':"tooltip", 'data-placement': "bottom", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides")}}
							</div>
						</div>
					</div>	
				</form>
			</div>
			</div>
			<div class="col-md-6">
				<div class="alert alert-success">
					<div class="row">
						<div class="col-sm-2">
							<span class="glyphicon glyphicon-info-sign"></span>
						</div>
						<div class="col-md-9">
							<p>Cree una nueva lista</p>
						</div>
					</div>
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
