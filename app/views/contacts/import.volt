{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
		{{ super() }}
		{{ partial("partials/ember_partial") }}
		{{ javascript_include('js/mixin_pagination.js') }}


<script type="text/javascript">
	var MyDbaseUrl = '{{urlManager.getBaseUri(true) ~ '/import/' ~ idContactlist}}';

	var myImportModel = {
		datas: DS.attr( 'string' ),
		email: DS.attr( 'string' ),	
		name: DS.attr( 'string' ),
		lastname: DS.attr( 'string' ),
		birthdate: DS.attr( 'string' ),
		header :DS.attr( 'boolean' ),
		delimiter: DS.attr( 'string' ),
		dateformat: DS.attr( 'string' ),
                importformat: DS.attr('string'),
                
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
		<div class="row">
			<h4 class="sectiontitle">Importar contactos desde archivo .csv</h4>
			<div class="">
				<img src="{{url('')}}b3/images/step2-import.png" class="center-block" alt="" />
			</div>
			<div class="space"></div>
{#
			<div class="well relative">
				Esta es la segunda parte del proceso, aqui podrá relacionar los datos del archivo que acaba de importar, con los
				campos obligatorios (direccion de correo electrónico), y los campos personalizados que ha creado en cada base de datos,
				la previsualización de como queda la relacion la podrá ver en la parte superior derecha de la pantalla.
			</div>
#}
		</div>
{#
		<div class="well relative span12">
			<div class="easy-pie-step span6"  data-percent="100"><span>2/2</span></div>
			<span class="triangle-button blue"><i class="icon-lightbulb"></i></span>
			<div class="span7"><strong>Segundo paso: </strong><br />
				Elegir y relacionar los campos a importar
			</div>
		</div>
#}

		<form method="POST" class="" action="{{url('contacts/processfile/')}}{{idContactlist~'/'~idImportfile}}" role="form">
			<div class="row">
				<div class="col-sm-6">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
						<h4 class="panel-title">Asignación de campos</h4>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-condensed table-striped table-contacts">
                                                        <tbody>
                                                                <tr>
                                                                        <th>Email</th>
                                                                        <th>
                                                                                {{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="email" id="email" name="email" class="form-control"}}'}}
                                                                        </th>
                                                                </tr>
                                                                <tr>		
                                                                        <th>Nombre</th>
                                                                        <th>
                                                                                {{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="name" id="name" name="name" class="form-control"}}'}}
                                                                        </th>
                                                                </tr>
                                                                <tr>		
                                                                        <th>Apellido</th>
                                                                        <th>
                                                                                {{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="lastname" id="lastname" name="lastname" class="form-control"}}'}}
                                                                        </th>
                                                                </tr>
                                                                <tr>		
                                                                        <th>Fecha de nacimiento</th>
                                                                        <th>
                                                                                {{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" valueBinding="birthdate" id="birthdate" name="birthdate" class="form-control"}}'}}
                                                                        </th>
                                                                </tr>
                                                                {%for field in customfields %}
                                                                <tr>		
                                                                        <th>{{field.name}}</th>
                                                                        <th>
                                                                                {{'{{ view Ember.Select contentBinding="App.options" optionValuePath="content.id" optionLabelPath="content.name" class="uniform form-control" valueBinding="campo'~field.idCustomField~'" id="campo'~field.idCustomField~'" name="campo'~field.idCustomField~'" }}'}}
                                                                        </th>
                                                                </tr>
                                                                {%endfor%}
                                                        </tbody>
                                                </table>
                                            </div>
                                        </div>
				</div>
		
				<div class="col-sm-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Previsualización</h4>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-normal hight-line">
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
                                                            <tr>
                                                                    <th>Fecha de nacimiento: </th>
                                                                    <td>{{'{{birthdateF}}'}}</td>
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
			
			<div class="row">
                            <div class="col-sm-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                    <h4 class="panel-title">Opciones de importación</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th><strong>Encabezado: </strong></th>
                                                    <td>
                                                        <div class="checkbox">
                                                            <label>
                                                                {{' {{view Ember.Checkbox  checkedBinding="header" name="header"}} '}}
                                                                Tratar primera fila de archivo como encabezado<br/>
                                                                <i>No se importa la primera línea</i>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><strong>Delimitador: </strong></th>
                                                    <td>
                                                        {{' {{view App.delimiterView valueBinding="delimiter" contentBinding="content" }} '}}
                                                        <br/>
                                                        <i>Para identificar los campos de cada línea</i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><strong>Formato de fecha: </strong></th>
                                                    <td>
                                                        {{'{{ view Ember.Select contentBinding="App.dateformats" optionValuePath="content.id" optionLabelPath="content.format" valueBinding="dateformat" id="dateformat" name="dateformat" class="form-control"}}'}}
                                                        <br/>
                                                        <i>Afecta como se procesan los campos de tipo fecha</i>    
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><strong>Modo de importación: </strong></th>
                                                    <td>
                                                        {{'{{ view Ember.Select contentBinding="App.importmodes" optionValuePath="content.id" optionLabelPath="content.value" valueBinding="importmode" id="dateformat" name="importmode" class="form-control"}}'}}
                                                        <br/>  
                                                        Estado en que se importan los contactos
                                                        <p class="text-warning">
                                                            <strong>Nota:</strong>
                                                            Esta opción es avanzada, si no sabe cual debe elegir deje el valor por defecto!
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="space"></div>
			</div>
			<div class="col-md-12">
				<div class="panel panel-warning">
                                    <div class="panel-heading">
					<h4 class="panel-title">Esta es la información del archivo. Se muestran las primeras 5 filas</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-condensed table-striped">
                                                <thead>
                                                    {{' {{#each App.firstline}} '}}
                                                            <th>Campo</th>
                                                    {{' {{/each}} '}}
                                                </thead>
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
			<a href="{{ url('contactlist/show/'~ idContactlist ~'#/contacts/import') }}" class="btn btn-default btn-sm extra-padding">Cancelar</a>
			{{submit_button('class': "btn btn-default btn-sm btn-guardar extra-padding", "Guardar")}}
		</form>
		<div class="space"></div>
	</script>
		
	<script type="text/x-handlebars" data-template-name="select">
		{{' {{view App.DelimiterView name="delimiter" contentBinding="App.delimiter_opt"}} '}}
	</script>

	<script type="text/x-handlebars" data-template-name="contacts">
		{{' {{outlet}} '}}
	</script>

</div>
{% endblock %}
