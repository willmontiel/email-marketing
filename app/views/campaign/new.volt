{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# Select master 2 #}
	{{ stylesheet_link('select2-master/select2.css')}}
	{{ javascript_include('select2-master/select2.js')}}
	
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}
    <script> 
		$(function (){
			$("[name='my-checkbox']").bootstrapSwitch({
				onColor: 'success',
				//onText: 'Activo',
				offColor: 'danger',
				//offText: 'Inactivo',
			});
			$("#dbases").select2();
		});
		
    </script>
{% endblock %}
{% block content %}
	<div class="space"></div>

	<div class="row">
		<h4 class="sectiontitle">Autorespuestas</h4>
	</div>
	
	<div class="row">
		<form action="{{url('campaign/new/time')}}" method="post"class="form-horizontal" role="form">
			<div class="form-group">
				<label for="autoresponderName" class="col-sm-3 control-label">Nombre de la autorespuesta</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" id="autoresponderName" placeholder="Nombre de la autorespuesta">
				</div>
			</div>
				
			<div class="form-group">
				<label for="dbases" class="col-sm-3 control-label">Base de datos</label>
				<div class="col-sm-7">
					<select id="dbases" name="dbase" style="width: 100%;">
						{% for dbase in dbases %}
							<option value="{{dbase.idDbase}}">{{dbase.name}}</option>
						{% endfor %}
					</select>
				</div>
			</div>
			
			<hr />
			
			<div class="form-group">
				<label for="time" class="col-sm-3 control-label">Iniciar en el día</label>
				<div class="col-sm-7">
					<div class="bg-wrap-calendar center-block">
						<div class="date">
							<div class="day-send" style="margin-left: 10%; margin-right: 10%; text-align: center;">
								<input id="time" class="input-autoresponse" value="0" autofocus="autofocus" type="text">
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<hr />
			
			<div class="form-group">
				<label for="mail" class="col-sm-3 control-label">Elegir mensaje</label>
				<div class="col-sm-7">
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<a href="#" class="thumbnail">
								<img data-src="holder.js/100%x180" alt="100%x180" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4=">
							</a>
							<div class="caption text-center">
								<h3>Elegir correo</h3>
							</div>
						</div>
							
						<div class="col-xs-6 col-md-6">
							<a href="#" class="thumbnail">
								<img data-src="holder.js/100%x180" alt="100%x180" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4=">
							</a>
							<div class="caption text-center">
								<h3>Crear nuevo correo</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<hr />
			
			<div class="form-group">
				<label for="time" class="col-sm-3 control-label">Activar</label>
				<div class="col-sm-7">
					<input type="checkbox" name="my-checkbox" checked>
				</div>
			</div>
			
			<hr />
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-7">
					<a href="{{url('campaign')}}" class="btn btn-default">Cancelar</a>
					<button type="submit" class="btn btn-default">Guardar</button>
				</div>
			</div>
		 </form>
	</div>
	
{% endblock %}