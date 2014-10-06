{# {{ '{{#if isContentAvailable}}' }} #}
	{{ '{{#unless isAttachementExpanded}}' }}
		<div {{'{{bind-attr class=": attachmentEmpty:bg-warning: "}}'}}>
			<div class="wrapper">
				<dl class="dl-horizontal" {{ '{{action "expandAttachment" this}}' }}>
				{{'{{#if attachmentEmpty }}'}}
					<dt>Archivos adjuntos:</dt> <dd>Este correo no contiene archivos adjuntos</dd>
				{{'{{else}}'}}
					<dt>Archivos adjuntos:</dt> <dd>{{'  {{attachmentsName}} '}}</dd>
				{{'{{/if}}'}}
				</dl>
			</div>
		</div>
	{{ '{{else}}' }}
		<div class="wrapper">
			<h4 class="paneltitle">Adjuntar archivos</h4>
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
						Adjuntar uno o varios archivos: 
					</label>
					<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="margin-top: 10px;">
						{{ '{{file-upload}}' }}
					</div>
				</div>
					
				<div class="form-group text-right">
					<div class="col-sm-12">
						<button class="btn btn-default btn-sm extra-padding" {{'{{action "discardAttachment" this}}'}}>Descartar cambios</button>
						<button class="btn btn-default btn-guardar btn-sm extra-padding" id="attach-file" {{'{{action "save" this}}'}}>Aplicar cambios</button>
					</div>
				</div>
			</form>
		</div>
	{{ '{{/unless}}' }}
{# {{ '{{/if}}' }} #}