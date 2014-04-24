<script type="text/x-handlebars" data-template-name="forms/index">
	<div class="row">
		<h4 class="sectiontitle">Formularios</h4>
		<table class="table table-condensed table-striped table-contacts">
			{{'{{#each model}}'}}
			<tr>
				<td>
					<div>
						{{'{{name}}'}}
					</div>
				</td>
				<td>
					<div>
						{{ '{{#link-to "forms.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm"}}' }}<i class="glyphicon glyphicon-pencil"></i> Editar{{ '{{/link-to}}' }}
					</div>
				</td>
				<td>
					<div>
						{{ '{{#link-to "forms.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete"}}' }}<i class="glyphicon glyphicon-trash"></i> Eliminar{{ '{{/link-to}}' }}
					</div>
				</td>
				<td>
					<div>
						{{ '{{#link-to "forms.code" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete"}}' }}<i class="glyphicon glyphicon-trash"></i> Codigo{{ '{{/link-to}}' }}
					</div>
				</td>
			</tr>
			{{'{{/each}}'}}
		</table>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/setup">
	<div class="row">
		<h4 class="sectiontitle">Crear nuevo formulario</h4>
	</div>
	{{ partial("dbase/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
	<br />
	<br />
	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class="form-full-content">
			
			</div>
			<div class="form-full-button col-md-offset-8">
				
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-menu">
			
			</div>
		</div>
	</div>
	<div class="row">
		<div class="btn btn-default btn-sm">
			<span {{ '{{action "cancel" this}}' }}>Cancelar</span>
		</div>
		<div class="btn btn-default btn-sm">
			<span {{ '{{action "sendData" this}}' }}>Crear Formulario</span>
		</div>
	</div>
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
	</div>
</script>