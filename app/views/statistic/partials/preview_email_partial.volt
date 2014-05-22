<div class="center-block">
	<span onClick="expandMailPreview();" class="anchor enlace">Ver detalle del correo</span>
</div>
{#   Vista en miniatura del correo enviado, con datos del mismo   #}
<div id="mail-preview" class="oculto">
	<div class="row wrapper">
		<div class="col-sm-8 col-md-5 col-md-offset-1">
			<div class="bg-thumb">
				<div class="window">
					<img {% if mail.previewData is defined%}
							src="data: image/png;base64, {{mail.previewData}}"
						 {% else %}
							src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSI+PC9yZWN0Pjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4="
						 {% endif %} class="" alt="thumbnail del correo" />
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