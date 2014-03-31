{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de su cuenta{% endblock %}

{% block content %}
	{{ content() }}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar blue">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Eliminar un usuario
						</div>
						<div class="news-text">
							En esta sección de la aplicacion podrá eliminar usuarios de la cuenta, pero recuerde que
							al eliminar un usuario se perderá totalmente la información y no podrá recuperarla.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br />
	<div class="row-fluid">
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