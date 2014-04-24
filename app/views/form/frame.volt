{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-edit"></i>Formularios{% endblock %}
{% block sectionsubtitle %}Cree formularios para ubicarlos en su sitio web o dentro de correos{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-md-5">
			<h4 class="sectiontitle">Formulario</h4>
			<form method="post" action="" onSubmit="" class="form-horizontal">
				{% for element in elements %}
					<div class="form-group">
						<div class="col-md-3">
							{{ element['label'] }}
						</div>
						<div class="col-md-7">
							{{ element['field'] }}
						</div>
					</div>
				{% endfor %}
			</form>
	</div>

{% endblock %}