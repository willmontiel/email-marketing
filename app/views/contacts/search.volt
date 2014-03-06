{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-search"></i>Buscar contactos{% endblock %}
{% block sectionsubtitle %}Busque contactos facilmente con solo ingresar un indicio{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span8 offset2">
			<div class="box">
				<div class="box-content">
					<form>
						<div class="padded">
							<div class="title-search">Buscar Contactos</div>
							<div class="input-prepend">
								<a class="add-on" href="#" style="pointer-events: none;cursor: default;">
									<i class="icon-search"></i>
								</a>
								<input type="text" placeholder="Dirección de correo, nombre, apellido, dominio...">
							</div>
						</div>
						<div class="form-actions" style="text-align: center !important;">
							<button class="btn btn-default" type="reset"><i class="icon-bolt"></i> Limpiar</button>
							<button class="btn btn-lightblue"><i class="icon-search"></i> Buscar</button>
						</div>
					</form>
				</div>
			</div>	
		</div>
	</div>
{% endblock %}