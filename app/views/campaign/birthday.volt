{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}

	<script type="text/javascript">
		var urlBase = "{{url('')}}";
		var idAutoresponder = 'null';
		
		{%if autoresponse is defined%}
			var idAutoresponder = "{{autoresponse.idAutoresponder}}"
		{%endif%}
		
		{%if autoresponse.target is defined%}
			var serializerObject = {{autoresponse.target}};
		{%else%}
			var serializerObject = null;
		{%endif%}
			
		{%if autoresponse.contentsource is defined%}
			var content = "{{autoresponse.contentsource}}";
		{%else%}
			var content = null;
		{%endif%}
			
		$(function (){
			{%if autoresponse is defined%}
				$('.input-autoresponse-time-hour').val('{{autoresponse.time.hour}}');
				$('.input-autoresponse-time-minutes').val('{{autoresponse.time.minute}}');
				$('.input-autoresponse-time-text').val('{{autoresponse.time.meridian}}');
				
				{%if autoresponse.active == 0%}
					$(".switch-campaign").bootstrapSwitch('state', false);
				{%endif%}
					
				{%if autoresponse.from is defined%}
					$('#from_email').val('{{autoresponse.from.email}}');
					$('#from_name').val('{{autoresponse.from.name}}');
				{%endif%}
					
				{%if autoresponse.subject.text == 'Meta Tag'%}
					$('#meta-tag').prop('checked', true);
					$("input[name='subject']").prop('disabled', true);
				{%endif%}
					
			{%endif%}
		});	
			
	</script>
	
	{{ javascript_include('js/campaign/birthday.js')}}
	
	{# Select2 master#}
	{{ stylesheet_link('vendors/select2-master/select2.css') }}
	{{ javascript_include('vendors/select2-master/select2.js')}}

	{# Selección de destinatarios #}
	{{ partial('partials/target_selection_partial') }}
	{% endblock %}
{% block content %}
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="title">Autorespuesta de cumpleaños</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{{ flashSession.output() }}	
		</div>
	</div>	
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel" style="box-shadow: 2px 2px 5px 0px #afafaf;">
				<form id="birthday_form" class="form-horizontal"  action="{%if autoresponse is defined%} {{url('campaign/birthday')}}/{{autoresponse.idAutoresponder}} {%else%} {{url('campaign/birthday')}} {%endif%}" method="post"  role="form">
					<div class="panel-body" style="margin-top: 20px;">
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Nombre de envío de cumpleaños
							</label>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<input class="form-control" autofocus placeholder="Nombre de envío de cumpleaños" type="text" name="name" required="required" {%if autoresponse is defined%} value="{{autoresponse.name}}" {%endif%}>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								¿A quién envías?
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<div class="panel panel-default">
									<div class="panel-body" style="background-color: #f5f5f5;">
										<div id="panel-container"></div>
									</div>
								</div>
							</div>
							<input class="form-control" type="hidden" name="target">
						</div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Hora del envío
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<div class="bg-wrap-time center-block without-margin time-clock-bigger">
									<select class="input-autoresponse-time-hour" name="hour"><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option></select>
									<select class="input-autoresponse-time-minutes" name="minute"><option>00</option><option>10</option><option>20</option><option>30</option><option>40</option><option>50</option></select>
									<select class="input-autoresponse-time-text" name="meridian"><option>am</option><option>pm</option></select>
								</div>
							</div>
						</div>
						
						<div class="space"></div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Asunto
							</label>
							<div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
								<input class="form-control" placeholder="Asunto" type="text" name="subject" required="required" {%if autoresponse is defined%} value="{{autoresponse.subject.text}}" {%endif%}>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Remitente
							</label>

							<div id="select-from">
								<div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
									<select id="select-field" class="form-control">
										<option>Seleccione nombre de remitente</option>
										{%for sender in senders%}
										<option value="{{sender.name}}/{{sender.email}}" {%if autoresponse is defined %} {%if autoresponse.from and autoresponse.from.email == sender.email%} selected {%endif%} {%endif%}>{{sender.name}} / {{sender.email}}</option>
										{%endfor%}
									</select>
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
									<a onclick="newSender();" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a>
								</div>
							</div>	

							<div id="new-from" class="hide-temporary">
								<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
									<input id="from_email" class="form-control" name="from_email" type="text" placeholder="Correo">
								</div>
								<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
									<input id="from_name" class="form-control" name="from_name" type="text" placeholder="Nombre">
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
									<a onclick="senderList();" class="btn btn-default"><span class="glyphicon glyphicon-list"></span></a>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Responder a
							</label>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<input class="form-control" placeholder="Responder a" type="text" name="reply" {%if autoresponse is defined%} value="{{autoresponse.reply}}" {%endif%}>
							</div>
						</div>
						
						{%if autoresponse is defined and autoresponse.contentsource is defined%}
						
							<div class="form-group col-md-10">
								<h4 class="paneltitle col-md-offset-2">Contenido del correo</h4>
								<div class="col-md-3 col-md-offset-5">
								{%if autoresponse.contentsource == 'editor'%}
									<a href="{{url('campaign/contenteditor')}}" class="create_content thumbnail">
								{%else%}
									<a href="{{url('campaign/contenthtml')}}" class="create_content thumbnail">
								{%endif%}

								{%if autoresponse.previewData is defined%}
									<img data-src="holder.js/100%x180" alt="100%x180" src="data:image/png;base64,{{autoresponse.previewData}}">
								{%else%}
									<img data-src="holder.js/100%x180" alt="100%x180" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSI+PC9yZWN0Pjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4=">
								{%endif%}
								</a>
								</div>
							</div>
						
						{% else %}
						
							<div class="form-group col-md-10">
								<h4 class="paneltitle col-md-offset-2">Contenido del correo</h4>
								<div class="col-md-3 col-md-offset-4 text-center">
									<a href="{{url('campaign/contenteditor')}}" class="create_content"><img src="{{url('vendors/bootstrap_v3/images/icon-edit-avanz.png')}}" class="" alt=""></a>
									<br>
									<a href="{{url('campaign/contenteditor')}}" class="create_content">Editor avanzado</a>
								</div>
								<div class="col-md-3 text-center">
									<a href="{{url('campaign/contenthtml')}}" class="create_content"><img src="{{url('vendors/bootstrap_v3/images/icon-html.png')}}" class="" alt=""></a>
									<br>
									<a href="{{url('campaign/contenthtml')}}" class="create_content">HTML</a>
								</div>
							</div>
						
						{% endif %}
						
						<div class="space"></div>
						
						<div class="form-group col-md-12">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Habilitado
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<input type="checkbox" class="switch-campaign"  name="active" checked>
							</div>
						</div>
					</div>
						
					<div class="panel-footer">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<a href="{{url('campaign')}}" class="btn btn-sm btn-default extra-padding">Cancelar</a>
								{{ submit_button("Guardar", 'class' : "btn btn-sm btn-guardar extra-padding") }}	
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
	
{% endblock %}