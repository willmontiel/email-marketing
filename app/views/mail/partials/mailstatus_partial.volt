<div {{'{{bind-attr class=": isMailReadyToSend:bg-success:bg-warning"}}'}}>
	<div class="wrapper">
		{{ '{{#if isMailReadyToSend}}' }}
			<div class="row">
				<div class="col-md-6">
					<div class="">
						<h4 class="text-center">Fecha del envío:</h4>
						<div class="bg-wrap-calendar center-block">
							<div class="date">
								<h1 class="day-send">{{'{{scheduleDay}}'}}</h1>
								<h6 class="month-send">{{'{{scheduleMonth}}'}}</h6>
								<h6 class="year-send">{{'{{scheduleYear}}'}}</h6>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="">
						<h4 class="text-center">Hora del envío:</h4>
						<div class="bg-wrap-time center-block">
							<span class="time-send">{{'{{scheduleTime}}'}}</span>
						</div>
					</div>
				</div>
					<div class="col-md-8">
						<h4>El correo está listo para enviarse en la fecha y hora programadas</h4>
					</div>

					<div class="col-md-4">
						<button class="ShowDialogSendTest btn btn-primary btn-sm extra-padding" data-toggle="modal" data-target="#myModal">Enviar prueba</button>
						<button onClick="sendMail()" class="btn btn-success btn-sm extra-padding" id="send-mail">Continuar</button>
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

