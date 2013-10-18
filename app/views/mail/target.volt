{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
	$(function(){
        $("input[name=radios]").on('ifChecked', function () { 
			$("#db").hide();
			$("#list").hide();
			$("#seg").hide();
			
			
			$('#dbSelect').prop('selectedIndex',-1);
			$("#dbSelect").val('').trigger("liszt:updated");
			$('#listSelect').prop('selectedIndex',-1);
			$('#segSelect').prop('selectedIndex',-1);
			
			var val = $('input[name=radios]:checked').val();
			console.log(val);
			switch (val) {
				case "0":
					$("#db").show();
					break;
				case "1":
					$("#list").show();
					break;
				case "2":
					$("#seg").show();
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
							Seleccione los destinatarios del correo
						</div>
						<div class="news-text">
							Esta es una parte muy importante, aqui decidirá quien debe recibir el correo, podrá seleccionar desde listas de contactos, segmentos hasta bases de
							datos, en un solo paso.
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
					<a href="{{url('mail/setup')}}/{{idMail}}">
						<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</a>
				</div>
				<div class="breadcrumb-button">
					<a href="{{url('mail/source')}}/{{idMail}}">
						<span class="breadcrumb-label"><i class="icon-edit"></i> Editar/Crear contenido</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</a>
				</div>
				<div class="breadcrumb-button blue">
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
	<br />
	<div class="row-fluid span4">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Seleccione destinatarios
				</div>
			</div>
			<div class="box-content">
				<form action="{{url('mail/target/')}}{{idMail}}" method="post">
					<div class="padded">
						<input type="radio" name="radios" class="icheck" value="0" id="dbRadio" >
						<label for="dbRadio">Base de datos de contactos</label> <br />
						<div id="db" style="display: none;">
							<select multiple="multiple" name="dbases[]"  id="dbSelect" class="chzn-select">
								{% for dbase in dbases %}
									<option value="{{dbase.idDbase}}">{{dbase.name}}</option>
								{% endfor %}
							</select>
						</div>
						<br />
						<input type="radio" name="radios" class="icheck" value="1" id="listRadio">
						<label for="listRadio">Lista de contactos </label>
						<div id="list" style="display: none;">
							<select multiple="multiple" name="contactlists[]" id="listSelect" class="chzn-select">
								{% for contactlist in contactlists %}
									<option value="{{contactlist.idContactlist}}">{{contactlist.name}},  {{contactlist.Dbase}}</option>
								{% endfor %}
							</select>
						</div>
						<br /><br />
						<input type="radio" name="radios" class="icheck" value="2" id="segmentRadio">
						<label for="segmentRadio">Segmentos</label>
						<br />
						<div id="seg" style="display: none;">
							<select multiple="multiple" name="segments[]" id="segSelect" class="chzn-select">
								{% for segment in segments %}
									<option value="{{segment.idSegment}}">{{segment.name}}</option>
								{% endfor %}
							</select>
						</div>
					</div>
					<div class="form-actions">
						<a href="{{url('mail/source')}}/{{idMail}}" class="btn btn-default">Anterior</a>
						{{submit_button('Siguiente', 'class' : "btn btn-blue")}}
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}
