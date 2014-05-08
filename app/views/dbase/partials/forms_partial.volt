<script type="text/x-handlebars" data-template-name="forms/index">
	<div class="bs-callout bs-callout-info">
		Aqui esta toda la información necesaria para gestionar sus formularios
	</div>

	<div class="pull-right">
	{{ '{{#link-to "forms.setup" disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}Nuevo Formulario de Inscripción{{ '{{/link-to}}' }}
	{{ '{{#link-to "forms.updating" disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}Nuevo Formulario de Actualización{{ '{{/link-to}}' }}
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
					<div>
						{{'{{type}}'}}
					</div>
				</td>
				<td>
					<div class="text-right">
						{{ '{{#if framecode}}' }}
							{{ '{{#link-to "forms.code" this disabledWhen="controller.deleteDisabled" class="btn btn-guardar btn-sm"}}' }}<i class="glyphicon glyphicon-th"></i> Codigo{{ '{{/link-to}}' }}
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
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo formulario</h4>
	</div>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/updating">
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo formulario de actualizacion</h4>
	</div>
	{{ partial("dbase/partials/form_update_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class="form-full-content">
			
			</div>
			<div class="form-full-button col-md-offset-7">
				
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-menu">
			<h4 class="sectiontitle title-fields-options form-options">Campos</h4>
			
			</div>
		</div>
	</div>
	<div class="form-action">
		<div class="row">
			<div class="col-xs-offset-6 col-xs-2">
				<button class="btn btn-default btn-sm extra-padding" {{ '{{action "cancel" this}}' }}>Cancelar</button>
			</div>
			<div class="col-xs-2">
				<button class="btn btn-guardar btn-sm extra-padding" {{ '{{action "sendData" this}}' }}>Crear Formulario</button>
			</div>
		</div>
	</div>
	<div class="space"></div>
</script>

<script type="text/x-handlebars" data-template-name="forms/edit">
	<div class="row">
		<h4 class="sectiontitle">Editar formulario</h4>
	</div>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/remove">
	<div class="row">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Eliminar un formulario
					</div>
				</div>
				<div class="box-content padded">			
					<p>¿Esta seguro que desea eliminar el Formulario <strong>{{'{{name}}'}}</strong>?</p>
					{{ '{{#if errors.errormsg}}' }}
						<div class="alert alert-error">
							{{ '{{errors.errormsg}}' }}
						</div>
					{{ '{{/if}}' }}
					<button {{'{{action eliminate this}}'}} class="btn btn-danger">Eliminar</button>
					<button class="btn btn-default" {{ '{{action cancel this}}' }}>Cancelar</button>	
				</div>
			</div>
		</div>	
</script>

<script type="text/x-handlebars" data-template-name="forms/code">
	<div class="row">
		<h4 class="sectiontitle">Codigo formulario</h4>
		<div class="col-md-6">
			<div class="bs-callout bs-callout-info">
				<h4>Codigo IFrame</h4>
				<p>Copie y pegue el siguiente codigo en su pagina web.</p>
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