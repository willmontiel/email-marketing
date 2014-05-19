{#   Estadisticas de redes sociales   #}

<h4 class="sectiontitle">Interacciones en redes sociales</h4>
<div class="container-fluid">
	<div class="social ptop facebook">
		<div class="sm-icons-stats-facebook center-block"></div>
		<span class="number-stats-dashboard-summary">{{statisticsSocial.share_fb|numberf}}</span>
		<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_fb|numberf}}</span></p></div>
		<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_fb|numberf}}</span></p></div>
	</div>
	<div class="social ptop twitter">
		<div class="sm-icons-stats-tweet center-block"></div>
		<span class="number-stats-dashboard-summary">{{statisticsSocial.share_tw|numberf}}</span>
		<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_tw|numberf}}</span></p></div>
		<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_tw|numberf}}</span></p></div>
	</div>
	<div class="social ptop google">
		<div class="sm-icons-stats-gplus center-block"></div>
		<span class="number-stats-dashboard-summary">{{statisticsSocial.share_gp|numberf}}</span>
		<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_gp|numberf}}</span></p></div>
		<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_gp|numberf}}</span></p></div>
	</div>
	<div class="social ptop linkedin">
		<div class="sm-icons-stats-linkedin center-block"></div>
		<span class="number-stats-dashboard-summary">{{statisticsSocial.share_li|numberf}}</span>
		<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_li|numberf}}</span></p></div>
		<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_li|numberf}}</span></p></div>
	</div>
</div>
