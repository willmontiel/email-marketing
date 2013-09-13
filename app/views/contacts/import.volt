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
		header :DS.attr( 'boolean' ),
		delimiter: DS.attr( 'string' )
		{%for field in customfields%}
			,
			campo{{ field.idCustomField }}: DS.attr('string')
		{%endfor%}		
	};
</script>

{{ javascript_include('js/app_import.js') }}
<script type="text/javascript">
	App.lines = [];
	App.lines.push("{{row[0]}}");
	App.lines.push("{{row[1]}}");
	App.lines.push("{{row[2]}}");
	App.lines.push("{{row[3]}}");
	App.lines.push("{{row[4]}}");
	App.lines.push("{{row[0]}}");

	App.options = mappingColumns(advancedSplit(App.lines[5], ","));
	App.firstline = advancedSplit(App.lines[0], ",");
	App.secondline = advancedSplit(App.lines[1], ",");
	App.thirdline = advancedSplit(App.lines[2], ",");
	App.fourthline = advancedSplit(App.lines[3], ",");
	App.fifthline = advancedSplit(App.lines[4], ",");
</script>

<script type="text/javascript">
	App.ContactsIndexController.reopen({
	{%for field in customfields%}
		campo{{ field.idCustomField }}F: function () {
		return App.secondline[this.get('content.campo{{ field.idCustomField }}')];
	}.property('content.campo{{ field.idCustomField }}'),
	{%endfor%}		
});
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
		<form method="POST" action="{{url('contacts/processfile/')}}{{idContactlist~'/'~idImportfile}}">
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
									<th>Email</th>
									<th>
										{{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="email" id="email" name="email"}}'}}
									</th>
								</tr>
								<tr>		
									<th>Nombre</th>
									<th>
										{{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="name" id="name" name="name"}}'}}
									</th>
								</tr>
								<tr>		
									<th>Apellido</th>
									<th>
										{{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="lastname" id="lastname" name="lastname"}}'}}
									</th>
								</tr>
								{%for field in customfields %}
								<tr>		
									<th>{{field.name}}</th>
									<th>
										{{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" class="uniform" valueBinding="campo'~field.idCustomField~'" id="campo'~field.idCustomField~'" name="campo'~field.idCustomField~'"}}'}}
						
									</th>
								</tr>
								{%endfor%}
							</tbody>
						</table>
					</div>
				<div class="box-footer">
					<span class="title">Delimitador: </span>
					{{' {{view App.delimiterView valueBinding="delimiter" contentBinding="content" class="span2"}} '}}
					<span class="title">Encabezado: </span>
					{{' {{view Ember.Checkbox  checkedBinding="header" name="header"}} '}}
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="box">
				<div class="box-header">
					<span class="title">Previsualización</span>
					<ul class="box-toolbar">
						<li><span class="label label-blue"><i class="icon-eye-open"></i></span></li>
					</ul>
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<tbody>
							<tr>
								<th>Email: </th>
								<td>{{'{{emailF}}'}}</td>
							</tr>
							<tr>
								<th>Nombre:</th>
								<td> {{'{{nameF}}'}}</td>
							</tr>
							<tr>
								<th>Apellido: </th>
								<td>{{'{{lastnameF}}'}}</td>
							</tr>
						{%for field in customfields%}
							<tr>
								<th>{{field.name}}: </th>
								<td>{{'{{campo'~field.idCustomField~'F}}'}}</td>
							
							</tr>
						{%endfor%}
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
								{{' {{#unless hasheader}} '}}
								<tr>
									{{' {{#each App.firstline}} '}}
										<td>{{' {{this}} '}}</td>
									{{' {{/each}} '}}
								</tr>
								{{ '{{/unless}}' }}
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
