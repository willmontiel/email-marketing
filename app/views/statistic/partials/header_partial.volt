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
			<hr>	
			<div style="font-size: 1.8em; text-align: center; color: #777;">Detalles</div><br />
			<div class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1 col-lg-3 col-lg-offset-1">
				<div class="preview-mail img-wrap">
					<div class="not-available">
				{% if mail.previewData is not defined%}
						<span class="glyphicon glyphicon-eye-close icon-not-available"></span>
						<label>Previsualización no disponible</label>
				{% else %}
						<img src="data: image/png;base64, {{mail.previewData}}" />
				{% endif %}	
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<div class="small-widget">
					<div class="widget-icon"><span class="glyphicon glyphicon-send"></span></div>
					<div class="widget-content">
						<div class="widget-header">Fecha de envío</div>	
						<div class="widget-body">{{date('d/M/Y g:i a', item.startedon)}}</div>	
					</div>
				</div>

				<div class="small-widget">
					<div class="widget-icon"><span class="glyphicon glyphicon-tag"></span></div>
					<div class="widget-content">
						<div class="widget-header">Asunto</div>	
						<div class="widget-body">
							{% if item.subject is empty%}<span style="color: #bd1b06">Este correo no contiene un asunto</span>{% else %}{{item.subject}}{% endif %}
						</div>	
					</div>
				</div>

				<div class="small-widget">
					<div class="widget-icon"><span class="glyphicon glyphicon-share-alt"></span></div>
					<div class="widget-content">
						<div class="widget-header">Remitente</div>	
						<div class="widget-body">
							{% if item.fromName is empty OR item.fromEmail is empty%}<span style="color: #bd1b06">Este correo no contiene un remitente</span>{% else %} {{item.fromName}}&lt;{{item.fromEmail}}&gt;{% endif %}
						</div>	
					</div>
				</div>

				<div class="small-widget">
					<div class="widget-icon"><span class="glyphicon glyphicon-retweet"></span></div>
					<div class="widget-content">
						<div class="widget-header">Responder a</div>	
						<div class="widget-body">
							{% if item.replyTo is empty%}<span style="color: #777">Este correo no tiene configurado un "Responder a"</span>{% else %}{{item.replyTo}}{% endif %}
						</div>	
					</div>
				</div>

				<div class="small-widget">
					<div class="widget-icon"><span class="glyphicon glyphicon-asterisk"></span></div>
					<div class="widget-content">
						<div class="widget-header">Tipo de correo</div>	
						<div class="widget-body">
							{% if item.type is empty%}<span style="color: #777">Indefinido</span>{% else %}{{item.type}}{% endif %}
						</div>	
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>
