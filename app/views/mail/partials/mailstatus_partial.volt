<div {{'{{bind-attr class=": isMailReadyToSend:bg-success:bg-warning"}}'}}>
	<div class="panel-body">
		{{ '{{#if isMailReadyToSend}}' }}
			<div class="row">
				<div class="col-md-12">
					<h3>El correo esta listo para enviarse</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h2>{{ '{{scheduleDate}}' }}</h2>
				</div>
				<div class="col-md-6">
					<button class="ShowDialogSendTest btn btn-primary" data-toggle="modal" data-target="#myModal">
						Enviar prueba
					</button>
					<button onClick="sendMail()" class="btn btn-success" id="send-mail">Continuar</button>
				</div>
			</div>
		{{ '{{else}}' }}
			<div class="row">
				<div class="col-md-12">
					<h3>El correo aún no esta listo para enviarse</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<strong>{{ '{{summaryMail}}' }}</strong>
				</div>
			</div>
		{{ '{{/if}}' }}
	</div>
</div>

