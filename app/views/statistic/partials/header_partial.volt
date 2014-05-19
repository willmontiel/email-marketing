<div class="wrap">
	<div class="col-md-5">
		<h4 class="sectiontitle numbers-contacts">{{mail.name}}</h4>
	</div>
	<div class="col-md-7">
		<div class="col-md-6">
			<p><span class="blue big-number">{{statisticsData.total|numberf}} </span>correos enviados</p>
		</div>
		<div class="col-md-6">
			<br><p class="text-right">Enviado el: {{date('Y-m-d', mail.finishedon)}}</p>
		</div>
	</div>
	<div class="clearfix"></div>
</div>