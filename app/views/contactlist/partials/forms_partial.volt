<script type="text/x-handlebars" data-template-name="forms/index">
	
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'forms']) }}
	
	<div class="row">
		<h1 class="sectiontitle">Formularios</h1>
		<div class="bs-callout bs-callout-info">
			Aquí esta toda la información necesaria para gestionar sus formularios
		</div>

		<div class="pull-right">
		{{ '{{#link-to "forms.setup" disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-plus"></span> Crear nuevo formulario de inscripción{{ '{{/link-to}}' }}
		{{ '{{#link-to "forms.updating" disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-plus"></span> Crear nuevo formulario de actualización{{ '{{/link-to}}' }}
		</div>
	</div>
	
	<div class="row">
		<table class="table table-striped table-contacts">
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
								{{ '{{#if isInscription}}' }}
									{{ '{{#link-to "forms.code" this disabledWhen="controller.deleteDisabled" class="btn btn-guardar btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-th"></span> Iframe{{ '{{/link-to}}' }}
									{{ '{{#link-to "forms.html" this disabledWhen="controller.deleteDisabled" class="btn btn-guardar btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-th"></span> HTML{{ '{{/link-to}}' }}
								{{ '{{else}}' }}
									{{ '{{#link-to "forms.link" this disabledWhen="controller.deleteDisabled" class="btn btn-guardar btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-link"></span> Enlace{{ '{{/link-to}}' }}
								{{ '{{/if}}' }}
								<a class="btn btn-default btn-sm extra-padding" onClick="preview({{'{{ unbound id }}'}})" title="Previsualizar" data-toggle="modal" data-target="#myModalFormPreview">
									<span class="glyphicon glyphicon-eye-open"></span> Previsualizar
								</a>
							{{ '{{/if}}' }}

							{{ '{{#if isInscription}}' }}
								{{ '{{#link-to "forms.edit" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-pencil"></span> Editar{{ '{{/link-to}}' }}
							{{ '{{else}}' }}
								{{ '{{#link-to "forms.editupdate" this disabledWhen="controller.updateDisabled" class="btn btn-default btn-sm extra-padding"}}' }}<span class="glyphicon glyphicon-pencil"></span> Editar{{ '{{/link-to}}' }}
							{{ '{{/if}}' }}

							{{ '{{#link-to "forms.remove" this disabledWhen="controller.deleteDisabled" class="btn btn-default btn-sm btn-delete extra-padding"}}' }}<span class="glyphicon glyphicon-trash"></span> Eliminar{{ '{{/link-to}}' }}
						</div>
					</td>
				</tr>
				{{'{{/each}}'}}
			</tbody>
		</table>
	</div>
	
	<div class="modal fade" id="myModalFormPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Previsualización del formulario</h4>
				</div>
				<div class="modal-body" id="preview-modal">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/setup">
	<h1 class="sectiontitle">Crear nuevo formulario de inscripción</h1>
	{{ partial("contactlist/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/updating">
	<h1 class="sectiontitle">Crear nuevo formulario de actualización</h1>
	{{ partial("contactlist/partials/form_update_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/new">
	<div class="row">
		<div class="col-md-11 col-sm-12 col-xs-12 top-editor-form">
			<button class="btn btn-delete btn-default btn-sm" {{ '{{action "cancel" this}}' }}><span class="glyphicon glyphicon-remove"></span></button>
			<button class="btn btn-guardar btn-sm" {{ '{{action "sendData" this}}' }}><span class="glyphicon glyphicon-floppy-saved"></span></button>
		</div>
		<div class="col-md-11 col-sm-12 col-xs-12 outline-editor-form">
			<div class="col-md-9 col-sm-12 col-xs-12 border edit-form-out-zone">
				<div id="header-zone"></div>
				<div class="form-horizontal form-full-content"></div>

				<div class="form-horizontal form-full-button"></div>
			</div>
			<div class="col-md-3 col-sm-12 col-xs-12 form-opt-out-zone" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingOne">
						 <h6 class="sectiontitle-form panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								<b>Campos Formulario</b>
							</a>
						</h6>
					</div>
					<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<div class="form-menu">
							</div>
						</div>
					</div>
				</div>
		
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingTwo">
						<h6 class="sectiontitle-form panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								<b>Herramientas</b>
							</a>
						</h6>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						<div class="panel-body">
							<div class="form-adv-tools">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<div class="space"></div>
	</div>
</script>


<script type="text/x-handlebars" data-template-name="forms/newupdate">
	<div class="row">
		<div class="col-md-11 col-sm-12 col-xs-12 top-editor-form">
			<button class="btn btn-delete btn-default btn-sm" {{ '{{action "cancel" this}}' }}><span class="glyphicon glyphicon-remove"></span></button>
			<button class="btn btn-guardar btn-sm" {{ '{{action "sendData" this}}' }}><span class="glyphicon glyphicon-floppy-saved"></span></button>
		</div>
		<div class="col-md-11 col-sm-12 col-xs-12 outline-editor-form">
			<div class="col-md-9 col-sm-12 col-xs-12 border edit-form-out-zone">
				<div id="header-zone"></div>
				<div class="form-horizontal form-full-content"></div>

				<div class="form-horizontal form-full-button"></div>
			</div>
			<div class="col-md-3 col-sm-12 col-xs-12 form-opt-out-zone" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingOne">
						 <h6 class="sectiontitle-form panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								<b>Campos Formulario</b>
							</a>
						</h6>
					</div>
					<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<div class="form-menu">
							</div>
						</div>
					</div>
				</div>
		
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="headingTwo">
						<h6 class="sectiontitle-form panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								<b>Herramientas</b>
							</a>
						</h6>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						<div class="panel-body">
							<div class="form-adv-tools">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<div class="space"></div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/edit">
	<h1 class="sectiontitle">Editar formulario de inscripción</h1>
	{{ partial("contactlist/partials/form_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/editupdate">
	<h1 class="sectiontitle">Editar formulario de actualización</h1>
	{{ partial("contactlist/partials/form_update_information_view_partial") }}
</script>

<script type="text/x-handlebars" data-template-name="forms/remove">
		<div class="row">	
			<h1 class="sectiontitle">Eliminar un formulario</h1>
			<div class="bs-callout bs-callout-danger">
				<p>¿Está seguro que desea eliminar el formulario <strong>{{'{{name}}'}}</strong>?</p>
			</div>
			{{ '{{#if errors.errormsg}}' }}
				<div class="alert alert-error">
					{{ '{{errors.errormsg}}' }}
				</div>
			{{ '{{/if}}' }}
			<div class="form-actions">
				<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Cancelar</button>	
				<button {{'{{action eliminate this}}'}} class="btn btn-delete btn-sm extra-padding">Eliminar</button>
			</div>
		</div>	
</script>

<script type="text/x-handlebars" data-template-name="forms/code">
	<div class="row">
		<h1 class="sectiontitle">Código Iframe del formulario</h1>
		<div class="row">
			<div class="col-md-6">
				<div class="bs-callout bs-callout-info">
					<h4>Código IFrame</h4>
					<p>Copie y pegue el siguiente código en su página web</p>
					<div>
						<textarea rows="4" cols="70">{{ '{{unbound framecode}}' }}</textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-actions">
				<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Regresar</button>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/html">
	<div class="row">
		<h1 class="sectiontitle">Código HTML del formulario</h1>
		<div class="row">
			<div class="col-md-8">
				<div class="bs-callout bs-callout-info">
					<h4>Código HTML</h4>
					<p>Copie y pegue el siguiente código en su página web</p>
					<div>
						<textarea rows="20" cols="90"><!DOCTYPE html><html><head></head><body>{{ '{{unbound html}}' }}</body></html></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-actions">
				<button class="btn btn-default btn-sm extra-padding" {{ '{{action cancel this}}' }}>Regresar</button>
			</div>
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="forms/link">
	<div class="row">
		<h1 class="sectiontitle">Enlace Formulario de Actualización</h1>
		<div class="row">
			<div class="col-md-6">
				<div class="bs-callout bs-callout-info">
					<h4>Recuerde</h4>
					<p>Seleccione el formulario cuando este creando un correo</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-actions">
				<button class="btn btn-default" {{ '{{action cancel this}}' }}>Regresar</button>
			</div>
		</div>
	</div>
</script>

<script type="text/javascript">
	function preview(id) {
//		$.post("{{url('form/preview')}}/" + id, function(form){
		$.post("{{url('form/preview')}}/" + id, function(obj){	
{#			var f = form.form;
			
			var content = f.title + '<form class="form-horizontal">';
			for(var i = 0; i < f.fields.length; i++) {
				content+= '<div class="form-group ' + f.fields[i].hide + '"><div class="col-md-3">' + f.fields[i].label + '</div><div class="col-md-7">' + f.fields[i].field + '</div></div>'
			}
			content+= f.button + '</form>';
			$('#preview-modal').empty();
			$('#preview-modal').append(content);#}
						
			var form = obj.form;
			$('#preview-modal').empty();
			$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal').contents().find('body').append(form);
		});
	}
	
	function verHTML() {
		var editor = document.getElementById('iframeEditor').contentWindow.catchEditorData();
		$.ajax({
			url: "{{url('template/previewtemplate')}}",
			type: "POST",			
			data: { editor: editor},
			error: function(msg){
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 10000});
			},
			success: function() {
				$("#modal-body-preview").empty();
				$('#modal-body-preview').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('template/previewdata')}}"/>'));
			
			}
		});
		
		document.getElementById('iframeEditor').contentWindow.RecreateEditor();
	}
</script>
