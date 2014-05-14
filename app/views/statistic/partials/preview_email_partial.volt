{#   Vista en miniatura del correo enviado, con datos del mismo   #}

<div class="row wrapper">
	<div class="col-sm-8 col-md-6">
		<div class="bg-thumb">
			<img src="data: image/png;base64, {{mail.previewData}}" class="" alt="thumbnail del correo" />
		</div>
	</div>
	<div class="col-md-3 ptop-50">
		<p>De: {{mail.fromName}} &lt;{{mail.fromEmail}}&gt;</p>
		<p>Asunto: {{mail.subject}}</p>
		<p>Destinatarios: {{target}}</p>
	</div>
</div>
<hr>
