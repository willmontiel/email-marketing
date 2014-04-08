{% extends "templates/index_b3.volt" %}


{% block sectiontitle %}Dashboard{% endblock %}
{% block content %}
{{flashSession.output()}}
	<div class="row">
		<h4 class="sectiontitle">Interacciones de los últimos quince días</h4>
		{%for widget in stats.fullPeriodStats()%}
		<div class="col-xs-6 col-md-3 col-lg-3">
			<div class="box-dashboard-summary summary-{{ widget.getClassName() }}">
				<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
				<div class="title-stats-dashboard-summary">
					{{widget.getTitle()}}
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				<div class="sparkline big" data-color="white">
					<!--
					{%for statvalue in widget.getSecondaryValues()%}
						{{statvalue.value}},
					{%endfor%}
					-->
				</div>
			</div>
		</div>

		{%endfor%}
		{%for widget in stats.fullSocialStats()%}
		<div class="col-xs-6 col-md-3 col-lg-3">
			<div class="box-dashboard-summary summary-box-social">
				<div class="sm-icons-summary-{{ widget.getClassName() }} center-block"></div>
				<div>
					<p>{{widget.getTitle()}}</p>
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				<div class="container-fluid">
					<div class="row border-top">
					{%for value in widget.getSecondaryValues()%}
						<div class="col-xs-6 social-sec-box">
							<div class="">
								{{value.name}}
							</div>
							<div class="">
								{{value.value}}
							</div>
						</div>
					{%endfor%}
						
					</div>
					
				</div>
			</div>
		</div>
		{%endfor%}
	</div>
	<div class="row-fluid space"></div>
	<div class="row">
		<h4 class="sectiontitle">Interacciones de los últimos 3 envíos</h4>
		<div class="row-fluid">
			<div class="col-sm-12 col-lg-10">
				<table class="table table-normal table-striped table-bordered">
					<thead>
						<tr>
							<th class="title">Ultimos 3 Envios</th>
							<th class="opens">Aperturas</th>
							<th class="clics">Clics</th>
							<th class="desusc">Desuscripciones</th>
							<th class="bounced">Rebotes</th>
						</tr>
					</thead>
					<tbody>
						{%for mail in stats.getLastMailsWithStats()%}
							<tr>
								<td>{{mail.name}}</td>
								<td>{{mail.uniqueOpens}}</td>
								<td>{{mail.clicks}}</td>
								<td>{{mail.unsubscribed}}</td>
								<td>{{mail.bounced}}</td>
							</tr>
						{%endfor%}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row-fluid space"></div>
	<div class="row">
		<h4 class="sectiontitle">Qué quieres hacer hoy?</h4>
		<div class="row-fluid">
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href=""  class="shortcuts"><span class="sm-button-large-email"></span></a>
				</div>
				<a href="" class="btn-actn">Crear un nuevo email</a>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href=""  class="shortcuts"><span class="sm-button-large-import-contacts"></span></a>
				</div>
				<a href="" class="btn-actn">Crear contactos</a>
			</div>
			<div class="col-xs-6 col-md-3">
				<div class="to-do sm-btn-blue">
					<a href=""  class="shortcuts"><span class="sm-button-large-database"></span></a>
				</div>
				<a href="" class="btn-actn">Bases de datos</a>
			</div>
		</div>
	</div>
	<div class="row-fluid space"></div>
</div>

{% endblock %}