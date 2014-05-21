{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de su cuenta{% endblock %}

{% block content %}
	{{ content() }}
	<div class="row">
		<h4 class="sectiontitle">Eliminar un usuario</h4>
	</div>
	<div class="bs-callout bs-callout-info">
		Aquí puede eliminar usuarios de la cuenta, pero recuerde que al al hacerlo se perderá totalmente la información y no podrá recuperarla.
	</div>
	<div class="row">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Eliminar un usuario
				</div>
			</div>
			<div class="box-content padded">
				Recuerde, al eliminar un usuario perderá todos los datos referentes a ese usuario y no podrá recuperarlos.
				Si esta seguro de continuar haga clic en eliminar, de lo contrario haga clic en cancelar.
				<br /><br />
				{{ submit_button("Eliminar", 'class' : "btn btn-red", 'data-toggle': "tooltip", 'data-placement': "left", 'title': "Recuerda que los campos con asterisco (*) son obligatorios, por favor no los olvides", 'data-original-title': "Tooltip on left") }}
				<a href="{{ url('user/index') }}" class="btn btn-default">Cancelar<a>
			</div>
		</div>
	</div>
{% endblock %}