<div class="row header-background">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row" onClick="expandMailPreview();" style="cursor: pointer;">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
				<div class="header">
					<div class="title">
						{{mail.name}}
					</div>
					<div class="title-info">
						Enviado	el {{date('d/M/Y g:i a', item.finishedon)}}
					</div>	
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
				<div class="contact-indicator">
					<span class="total-contacts">
						{{statisticsData.total|numberf}}
					</span>
					<br />
					<span class="text-contacts" style="padding-right: 14px;"> 
						correos enviados
					</span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
			
		<div class="row oculto" onClick="expandMailPreview();" id="mail-preview">
			<hr />
			<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
				<table class="table">
					<thead></thead>
					<tbody>
						<tr>
							<td>
								<div class="bg-thumb">
									<div class="window">
										<img {% if mail.previewData is defined%}
												src="data: image/png;base64, {{mail.previewData}}"
											 {% else %}
												src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSI+PC9yZWN0Pjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4="
											 {% endif %} class="" alt="thumbnail del correo" />
									</div>
								</div>
							</td>
							<td>
								<table class="table">
									<thead></thead>
									<tbody>
										<tr>
											<td>
												De
											</td>
											<td>
												{{mail.fromName}} &lt;{{mail.fromEmail}}&gt;
											</td>
										</tr>
										<tr>
											<td>
												Asunto
											</td>
											<td>
												{{mail.subject}}
											</td>
										</tr>
										<tr>
											<td>
												Para
											</td>
											<td>
												{{target}}
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>	
	</div>
</div>
