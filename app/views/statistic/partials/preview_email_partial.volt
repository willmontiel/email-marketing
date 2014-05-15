<h4 class="sectiontitle" style="text-decoration: underline;">
	<span onClick="expandMailPreview();">Ver detalle del correo</span>
</h4>

{#   Vista en miniatura del correo enviado, con datos del mismo   #}
<div id="mail-preview" style="display: none;">
	<div class="row wrapper">
		<div class="col-sm-8 col-md-5 col-md-offset-1">
			<div class="bg-thumb">
				<div class="window">
					<img src="data: image/png;base64, {{mail.previewData}}" class="" alt="thumbnail del correo" />
				</div>
			</div>
		</div>
		<div class="col-sm-8 col-md-5 ptop-50">
			<dl>
				<dt>De: </dt>
				<dd>{{mail.fromName}} &lt;{{mail.fromEmail}}&gt;</dd>
				<dt>Asunto: </dt>
				<dd>{{mail.subject}}</dd>
				<dt>Destinatarios: </dt>
				<dd>{{target}}</dd>
			</dl>
		</div>
	</div>
	<hr>
</div>