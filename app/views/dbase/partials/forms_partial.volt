<script type="text/x-handlebars" data-template-name="forms/index">
	<div class="bs-callout bs-callout-info">
		Aquí esta toda la información necesaria para gestionar sus formularios
	</div>

	<div class="pull-right">
	{{ '{{#link-to "forms.setup" disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}Nuevo formulario{{ '{{/link-to}}' }}
	</div>
	<div class="space"></div>
	<table class="table table-striped">
		<thead>
		<tr>
			<td> Nombre </td>
			<td> Tipo </td>
		<tr>
		</thead>
		<tbody>
			{{'{{#each model}}'}}
			<tr>
				<td>
					<div>
						{{'{{name}}'}}
					</div>
				</td>
				<td>
					Inscripción
				</td>
				<td>
					<div class="text-right">
						{{ '{{#if framecode}}' }}
							{{ '{{#link-to "forms.code" this disabledWhen="controller.deleteDisabled" class="btn btn-guardar btn-sm"}}' }}<i class="glyphicon glyphicon-th"></i> Código{{ '{{/link-to}}' }}
						{{ '{{/if}}' }}

						{{ '{{#link-to "forms.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}<i class="glyphicon glyphicon-pencil"></i> Editar{{ '{{/link-to}}' }}

						{{ '{{#link-to "forms.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete"}}' }}<i class="glyphicon glyphicon-trash"></i> Eliminar{{ '{{/link-to}}' }}
					</div>
				</td>
			</tr>
			{{'{{/each}}'}}
		</tbody>
	</table>
</script>

<script type="text/x-handlebars" data-template-name="forms/setup">
	<h4 class="sectiontitle">Crear nuevo formulario</h4>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
		<div class="col-md-6 col-xs-12 col-md-offset-1 border">
			<form class="form-horizontal form-full-content" role="form"></form>

			<form class="form-full-button" role="form"></form>
		</div>
		<div class="col-md-4 col-sm-8 col-xs-12">
			<div class="form-menu">
				<h4 class="sectiontitle title-fields-options">Campos para agregar al formulario</h4>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="space"></div>
		<hr>
		<div class="form-action col-md-offset-1">
			<button class="btn btn-default btn-sm extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
			<button class="btn btn-guardar btn-sm extra-padding" {{ '{{action "sendData" this}}' }}>Crear formulario</button>
		</div>
	<div class="space"></div>
</script>

<script type="text/x-handlebars" data-template-name="forms/edit">
	<h4 class="sectiontitle">Editar formulario</h4>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/remove">
			<h4 class="sectiontitle">Eliminar un formulario</h4>
				<div class="box-content padded">			
					<p>¿Está seguro que desea eliminar el formulario <strong>{{'{{name}}'}}</strong>?</p>
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					<button {{'{{action eliminate this}}'}} class="btn btn-delete btn-sm extra-padding">Eliminar</button>
					<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>	
				</div>
			</div>
		</div>	
</script>

<script type="text/x-handlebars" data-template-name="forms/code">
	<div class="row">
		<h4 class="sectiontitle">Código formulario</h4>
		<div class="col-md-6">
			<div class="bs-callout bs-callout-info">
				<h4>Código IFrame</h4>
				<p>Copie y pegue el siguiente código en su página web.</p>
				<div>
					<textarea rows="4" cols="70">{{ '{{unbound framecode}}' }}</textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-4">
			<button class="btn btn-default" {{ '{{action cancel this}}' }}>Regresar</button>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/link">
	<div class="row">
		<h4 class="sectiontitle">Enlace Formulario de Actualización</h4>
		<div class="col-md-6">
			<div class="bs-callout bs-callout-info">
				<h4>Recuerde</h4>
				<p>Seleccione el formulario cuando este creando un correo</p>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-4">
			<button class="btn btn-default" {{ '{{action cancel this}}' }}>Regresar</button>
		</div>
	</div>
</script>

<script type="text/javascript">
	function preview(id) {
		$.post("{{url('form/preview')}}/" + id, function(form){
			var f = form.form;
			
			var button = '<div class="form-actions pull-right"><a class="btn btn-sm btn-default btn-guardar extra-padding">' + f.button + '</a></div><div class="clearfix"></div>';
			var title = '<h4 class="sectiontitle">' + f.title + '</h4>';
			var content = title + '<form class="form-horizontal">';
			for(var i = 0; i < f.fields.length; i++) {
				content+= '<div class="form-group ' + f.fields[i].hide + '"><div class="col-md-3">' + f.fields[i].label + '</div><div class="col-md-7">' + f.fields[i].field + '</div></div>'
			}
			content+= button + '</form>';
			$('#preview-modal').empty();
			$('#preview-modal').append(content);
			$('.date_view_picker').datetimepicker({
				format:'Y-m-d',
				inline:true,
				timepicker:false,
				lang:'es',
				startDate: 0
			});
		});
	}
</script>

<script type="text/x-handlebars" data-template-name="forms/index">
	<div class="bs-callout bs-callout-info">
		Aquí esta toda la información necesaria para gestionar sus formularios
	</div>

	<div class="pull-right">
	{{ '{{#link-to "forms.setup" disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}Nuevo formulario{{ '{{/link-to}}' }}
	</div>
	<div class="space"></div>
	<table class="table table-striped">
		<thead>
		<tr>
			<td> Nombre </td>
			<td> Tipo </td>
		<tr>
		</thead>
		<tbody>
			{{'{{#each model}}'}}
			<tr>
				<td>
					<div>
						{{'{{name}}'}}
					</div>
				</td>
				<td>
					Inscripción
				</td>
				<td>
					<div class="text-right">
						{{ '{{#if framecode}}' }}
							{{ '{{#link-to "forms.code" this disabledWhen="controller.deleteDisabled" class="btn btn-guardar btn-sm"}}' }}<i class="glyphicon glyphicon-th"></i> Código{{ '{{/link-to}}' }}
						{{ '{{/if}}' }}

						{{ '{{#link-to "forms.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}<i class="glyphicon glyphicon-pencil"></i> Editar{{ '{{/link-to}}' }}

						{{ '{{#link-to "forms.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete"}}' }}<i class="glyphicon glyphicon-trash"></i> Eliminar{{ '{{/link-to}}' }}
					</div>
				</td>
			</tr>
			{{'{{/each}}'}}
		</tbody>
	</table>
</script>

<script type="text/x-handlebars" data-template-name="forms/setup">
	<h4 class="sectiontitle">Crear nuevo formulario</h4>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
		<div class="col-md-6 col-xs-12 col-md-offset-1 border">
			<form class="form-horizontal form-full-content" role="form"></form>

			<form class="form-full-button" role="form"></form>
		</div>
		<div class="col-md-4 col-sm-8 col-xs-12">
			<div class="form-menu">
				<h4 class="sectiontitle title-fields-options">Campos para agregar al formulario</h4>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="space"></div>
		<hr>
		<div class="form-action col-md-offset-1">
			<button class="btn btn-default btn-sm extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
			<button class="btn btn-guardar btn-sm extra-padding" {{ '{{action "sendData" this}}' }}>Crear formulario</button>
		</div>
	<div class="space"></div>
</script>

<script type="text/x-handlebars" data-template-name="forms/edit">
	<h4 class="sectiontitle">Editar formulario</h4>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/remove">
			<h4 class="sectiontitle">Eliminar un formulario</h4>
				<div class="box-content padded">			
					<p>¿Está seguro que desea eliminar el formulario <strong>{{'{{name}}'}}</strong>?</p>
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					<button {{'{{action eliminate this}}'}} class="btn btn-delete btn-sm extra-padding">Eliminar</button>
					<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>	
				</div>
			</div>
		</div>	
</script>

<script type="text/x-handlebars" data-template-name="forms/code">
	<div class="row">
		<h4 class="sectiontitle">Código formulario</h4>
		<div class="col-md-6">
			<div class="bs-callout bs-callout-info">
				<h4>Código IFrame</h4>
				<p>Copie y pegue el siguiente código en su página web.</p>
				<div>
					<textarea rows="4" cols="70">{{ '{{unbound framecode}}' }}</textarea>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-4">
			<button class="btn btn-default" {{ '{{action cancel this}}' }}>Regresar</button>
		</div>
	</div>
</script>
