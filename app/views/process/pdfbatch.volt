{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{% if batch.status != 'finalizado' OR batch.status != 'cancelado'%}
		<script type="text/javascript">
			var baseurl = '{{urlManager.getBaseUri(true)}}';
			var refresher = setInterval(loadNow, 3000);

			function loadNow () { 
				$.getJSON(baseurl + 'process/refreshpdfbatch/' + {{batch.idPdfbatch}}, function(data){
					if(data.length !== 0) {
						$('#processed').empty();
						$('#processed').append(data.processed);
						
						$('#to-process').empty();
						$('#to-process').append(data.toProcess);
						
						if (data.status === 'finalizado') {
							$('#status').empty();
							$('#status').append(data.status);
							$('#file-available').show('slow');
							clearInterval(refresher);
						}
						else if (data.status === 'cancelado') {
							$('#status').empty();
							$('#status').append(data.status);
							clearInterval(refresher);
						}
					}
				});
			};
		</script>
	{% endif %}
{% endblock %}
{% block content %}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'export']) }}
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="header">
					<div class="title">Resumen de creación de archivos PDF por lote</div>
					<div class="title-info">Podría tardar unos minutos</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
			<div class="header-background">
				<table class="table table-striped table-bordered">
					<thead></thead>
					<tbody>
						<tr>
							<td><strong>Archivos a procesar(Aprox)</strong></td>
							<td>{% if batch.status == 'finalizado' OR batch.status == 'cancelado'%}
									{{batch.toProcess}}
								{% else %}
									<div id="to-process">0</div>
								{% endif %}</td>
						</tr>
						<tr>
							<td><strong>Archivos procesados(Aprox)</strong></td>
							<td>
								{% if batch.status == 'finalizado' OR batch.status == 'cancelado'%}
									{{batch.processed}}
								{% else %}
									<div id="processed">0</div>
								{% endif %}
							</td>
						</tr>
						<tr>
							<td><strong>Estado</strong></td>
							<td>
								{% if batch.status == 'finalizado' OR batch.status == 'cancelado'%}
									{{batch.status}}
								{% else %}
									<div id="status">{{batch.status}}</div>
								{% endif %}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	<div class="row" id="file-available" style="display: {% if batch.status == 'finalizado' %}block{%else%}none{% endif %};">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
			<div class="header-background">
				<div class="header">
					<div class="title">
						<a href="{{url('pdf/getbatch')}}/{{batch.idPdfbatch}}" target="_blank">
							Haga click aqui para descargar
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
{% endblock %}