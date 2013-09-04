{% extends "templates/index_new.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}


<script type="text/javascript">
	var MyDbaseUrl = '{{apiurlbase.url ~ '/import/' ~ idContactlist}}';

	var myImportModel = {
		datas: DS.attr( 'string' ),
		email: DS.attr( 'string' ),	
		name: DS.attr( 'string' ),
		lastname: DS.attr( 'string' ),
		delimiter: DS.attr( 'string' )
		{%for field in customfields%}
			,
			{{field.name|lower }}: DS.attr('string')
		{%endfor%}
	};
</script>

{{ javascript_include('js/app_import.js') }}
<script type="text/javascript">
	
	App.originalF = "{{row[0]}}";
	App.originalS = "{{row[1]}}";
	App.originalT = "{{row[2]}}";
	App.originalFo = "{{row[3]}}";
	App.originalFi = "{{row[3]}}";
	App.optionsOr = " ,{{row[0]}}"
	
	App.options = App.optionsOr.split(",");
	
	App.firstline = App.originalF.split(",");
	
	App.secondline = App.originalS.split(",");
	
	App.thirdline = App.originalT.split(",");
	
	App.fourthline = App.originalFo.split(",");
	
	App.fifthline = App.originalFi.split(",");
</script>


{% endblock %}
{% block sectiontitle %}Importar contactos{% endblock %}
{% block content %}
<div id="emberAppImportContainer">
	<script type="text/x-handlebars" data-template-name="contacts/index">
		<div class="row-fluid">
			<div class="span8">
				<div class="well relative">
					Esta es la segunda parte del proceso, aqui podrá relacionar los datos del archivo que acaba de importar, con los
					campos obligatorios (direccion de correo electrónico), y los campos personalizados que ha creado en cada base de datos,
					la previsualización de como queda la relacion la podrá ver en la parte superior derecha de la pantalla.
				</div>
			</div>
			<div class="span4">
				<div class="well relative span12">
					<div class="easy-pie-step span6"  data-percent="100"><span>2/2</span></div>
					<span class="triangle-button blue"><i class="icon-lightbulb"></i></span>
					<div class="span7"><strong>Segundo paso: </strong><br />
						Elegir y relacionar los campos a importar
					</div>
				</div>
			</div>
		</div>
		<form method="POST" action="{{url('contacts/processfile')}}">
		<div class="row-fluid">
			<div class="span6">
				<div class="box">
					<div class="box-header">
						<span class="title">Relaciones entre campos</span>
						<ul class="box-toolbar">
							<li><span class="label label-black"><i class="icon-exchange"></i></span></li>
						</ul>
					</div>
					<div class="box-content">
						<table class="table table-normal">
							<tbody>
								<tr>
									<td>Email</td>
									<td>
										<div class="selector hover span12">
											{{'{{ view Ember.Select contentBinding="App.options" valueBinding="email" id="email" class="uniform"}}'}}
										</div>
									</td>
								</tr>
								<tr>		
									<td>Nombre</td>
									<td>
										<div class="selector hover span12">
											{{'{{ view Ember.Select contentBinding="App.options" valueBinding="name" id="name" class="uniform"}}'}}
										</div>
									</td>
								</tr>
								<tr>		
									<td>Apellido</td>
									<td>
										<div class="selector hover span12">
											{{'{{ view Ember.Select contentBinding="App.options" valueBinding="lastname" id="lastname" class="uniform"}}'}}
										</div>
									</td>
								</tr>
								{%for field in customfields %}
								<tr>		
									<td>{{field.name}}</td>
									<td>
										{{'{{ view Ember.Select contentBinding="App.options" class="uniform" valueBinding="'~field.name|lower~'" id="'~field.name|lower~'"}}'}}
						
									</td>
								</tr>
								{%endfor%}
							</tbody>
						</table>
					</div>
				<div class="box-footer">
					<span class="title">Delimitador: </span>
					<div class="selector hover span2">
					{{' {{view App.delimiterView valueBinding="delimiter" contentBinding="content" class="uniform"}} '}}
					</div>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="box">
				<div class="box-header">
					<span class="title">Previsualización</span>
					<ul class="box-toolbar">
						<li><span class="label label-black"><i class="icon-eye-open"></i></span></li>
					</ul>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<tbody>
							<tr>
								<td>Email: </td>
								<td>{{'{{email}}'}}</td>
							</tr>
							<tr>
								<td>Nombre:</td>
								<td> {{'{{name}}'}}</td>
							</tr>
							<tr>
								<td>Apellido: </td>
								<td>{{'{{lastname}}'}}</td>
							</tr>
							<tr>
						{%for field in customfields%}
								<td>{{field.name}}: </td>
								<td>{{'{{'~field.name|lower~'}}'}}</td>
						{%endfor%}
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<div class="box-header">
						<span class="title">Información de archivo, 5 primeras filas</span>
						<ul class="box-toolbar">
							<li><span class="label label-green"><i class="icon-thumbs-up"></i></span></li>
						</ul>
					</div>
					<div class="box-content">
						<table class="table table-normal">
							<tbody>
								<tr>
									{{' {{#each App.firstline}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each App.secondline}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each App.thirdline}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each App.fourthline}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								<tr>
									{{' {{#each App.fifthline}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		{{submit_button('class': "btn btn-default", "Enviar")}}
		<button class="btn btn-default">Cancelar</button>
		</form>
	</script>
	
	<script type="text/x-handlebars" data-template-name="select">
		{{' {{view App.DelimiterView name="delimiter" contentBinding="App.delimiter_opt"}} '}}
	</script>
	
	<script type="text/x-handlebars" data-template-name="contacts">
		{{' {{outlet}} '}}
	</script>

</div>
{% endblock %}
