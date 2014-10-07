{{ '{{#if isContentAvailable}}' }} 
	{{ '{{#unless isAttachementExpanded}}' }}
		<div {{'{{bind-attr class=": attachmentEmpty:bg-warning: "}}'}} style="cursor: pointer;">
			<div class="wrapper">
				<dl class="dl-horizontal" {{ '{{action "expandAttachment" this}}' }}>
				{{'{{#if attachmentEmpty }}'}}
					<dt>Archivos adjuntos:</dt> <dd>Este correo no contiene archivos adjuntos</dd>
				{{'{{else}}'}}
					<dt>Archivos adjuntos:</dt> 
					<dd>
						{{ '{{#each attachmentsFiles}}' }}
							<span class="attachment-badge">
							{{ '{{attach}}' }}
							</span>
						{{ '{{/each}}' }}
					</dd>
				{{'{{/if}}'}}
				</dl>
			</div>
		</div>
	{{ '{{else}}' }}
		<div class="wrapper">
			<h4 class="paneltitle">Adjuntar archivos</h4>
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
						Adjuntar uno o varios archivos: 
					</label>
					<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="margin-top: 10px;">
						<div class="input-group fileWrapper">
							<input type="text" class="fileInputText form-control" id="input-file-decorator" readonly="readonly">
							<span class="input-group-btn">
								<button type="button" class="fileInputButton btn btn-primary btn-no-rc">Examinar</button>
							</span>
							{{ '{{file-upload id="input-file-style"}}' }}
						</div>
					</div>
				</div>
					
				<div class="form-group text-right">
					<div class="col-sm-12">
						<button class="btn btn-sm btn-default btn-delete extra-padding" onclick="resetAttachment();" {{'{{action "refreshModel" this}}'}}>Eliminar todos los archivos adjuntos</button>
						<button class="btn btn-default btn-sm extra-padding" {{'{{action "contractingAttachment" this}}'}}>Cancelar</button>
						<button class="btn btn-default btn-guardar btn-sm extra-padding" id="attach-file" {{ '{{action "refreshModel" this}}' }}>Aplicar cambios</button>
					</div>
				</div>
			</div>
		</div>
	{{ '{{/unless}}' }}
{{ '{{/if}}' }}