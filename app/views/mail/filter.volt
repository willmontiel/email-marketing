{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
	$(function(){
        $("input[name=filter]").on('ifChecked', function () { 
			$("#link").hide();
			$("#open").hide();
			$("#date").hide();
			$("#exclude").hide();
			
			$('#link').prop('selectedIndex',-1);
			$('#open').prop('selectedIndex',-1);
			$('#date').prop('selectedIndex',-1);
			$('#exclude').prop('selectedIndex',-1);
			
			var val = $('input[name=filter]:checked').val();
			switch (val) {
				case "0":
					$("#link").show();
					break;
				case "1":
					$("#open").show();
					break;
				case "2":
					$("#date").show();
					break;
				case "3":
					$("#exclude").show();
					break;
			}
         });
	});
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-envelope"></i>Correos{% endblock %}
{% block sectionsubtitle %}Envíe un correo a multiples contactos{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar green">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Seleccione contactos por medio de filtros
						</div>
						<div class="news-text">
							Esta función le permite seleccionar contactos que cumplan ciertas condiciones
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			<div id="breadcrumbs">
				<div class="breadcrumb-button">
					<a href="{{url('mail/setup')}}">
						<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</a>
				</div>
				<div class="breadcrumb-button blue">
					<span class="breadcrumb-label"><i class="icon-edit"></i> Editar/Crear contenido</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button">
					<span class="breadcrumb-label"><i class="icon-group"></i> Seleccionar destinatarios</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button">
					<span class="breadcrumb-label"><i class="icon-calendar"></i> Programar envío</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
			</div>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box offset2 span8">
				<div class="box-header">
					<div class="title">
						Filtrar contactos por: 
					</div>
				</div>
				<div class="box-content">
					<form action = "{{url('mail/html')}}" method="post">
						<div class="padded">
							<input type="radio" name="filter" id="clickLink" class="icheck" value="0" />
							<label for="clickLink">Seleccionar contactos que hayan realizado clic en algún enlace: </label><br />
							<div id="link" style="display: none;">
								<select multiple="multiple" name="link[]" id="link" class="chzn-select">
									
								</select>
							</div>
							
							
							<input type="radio" name="filter" id="openMail" class="icheck" value="1" />
							<label for="openMail">Seleccionar contactos que hayan abierto cualquier correo electrónico: </label><br />
							<div id="open" style="display: none;">
								<select multiple="multiple" name="openMail[]" id="open" class="chzn-select">
									
								</select>
							</div>
							
							<input type="radio" name="filter" id="dateMail" class="icheck" value="2" />
							<label for="dateMail">Seleccionar contactos que hayan abierto un correo electrónico enviado en una fecha en particular: </label><br />
							<div id="date" style="display: none;">
								<select multiple="multiple" name="dateMail[]" id="date" class="chzn-select">
									
								</select>
							</div>
							
							<input type="radio" name="filter" id="excludeContact" class="icheck" value="3" />
							<label for="excludeContact">Excluir aquellos contactos que hayan abierto algún correo electrónico enviado en una fecha en particular: </label>
							<div id="exclude" style="display: none;">
								<select multiple="multiple" name="excludeContact[]" id="exclude" class="chzn-select">
									
								</select>
							</div>
							
						</div>
						<div class="form-actions">
							<a href="" class="btn btn-default">Anterior</a>
							<input type="submit" class="btn btn-blue" value="Siguiente" />
						</div>	
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}