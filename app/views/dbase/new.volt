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
						
						<label>*Color de Etiqueta</label>
						{{editform.render("color")}}
						<table id="colorchart">
							<tr>
								<td class="color-FF0000" bgcolor="#FF0000"></td>
								<td class="color-FF4000" bgcolor="#FF4000"></td>
								<td class="color-FF8000" bgcolor="#FF8000"></td>
								<td class="color-FFBF00" bgcolor="#FFBF00"></td>
								<td class="color-FFFF00" bgcolor="#FFFF00"></td>
								<td class="color-BFFF00" bgcolor="#BFFF00"></td>
							</tr>
							<tr>
								<td class="color-80FF00" bgcolor="#80FF00"></td>
								<td class="color-40FF00" bgcolor="#40FF00"></td>
								<td class="color-00FF00" bgcolor="#00FF00"></td>
								<td class="color-00FF40" bgcolor="#00FF40"></td>
								<td class="color-00FF80" bgcolor="#00FF80"></td>
								<td class="color-00FFBF" bgcolor="#00FFBF"></td>
							</tr>
							<tr>
								<td class="color-00FFFF" bgcolor="#00FFFF"></td>
								<td class="color-00BFFF" bgcolor="#00BFFF"></td>
								<td class="color-0080FF" bgcolor="#0080FF"></td>
								<td class="color-0040FF" bgcolor="#0040FF"></td>
								<td class="color-0000FF" bgcolor="#0000FF"></td>
								<td class="color-4000FF" bgcolor="#4000FF"></td>
							</tr>
							<tr>
								<td class="color-8000FF" bgcolor="#8000FF"></td>
								<td class="color-BF00FF" bgcolor="#BF00FF"></td>
								<td class="color-FF00FF" bgcolor="#FF00FF"></td>
								<td class="color-FF00BF" bgcolor="#FF00BF"></td>
								<td class="color-FF0080" bgcolor="#FF0080"></td>
								<td class="color-FF0040" bgcolor="#FF0040"></td>
							</tr>
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
