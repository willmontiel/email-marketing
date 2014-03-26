{% extends "templates/index_b3.volt" %}

{% block sectiontitle %}Dashboard{% endblock %}
{% block content %}
{{flashSession.output()}}
	<div class="row">
		{%for widget in stats.fullPeriodStats()%}
		<div class="col-xs-6 col-md-3 col-lg-3">
			<div class="box-dashboard-summary summary-{{ widget.getClassName() }}" style="margin-bottom: 20px;">
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
			<div class="box-dashboard-summary summary-fb">
				<div>
					<p>{{widget.getTitle()}}</p>
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				<div class="container-fluid">
					<div class="row">
					{%for value in widget.getSecondaryValues()%}
						<div class="col-xs-6">
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
		<div class="row-fluid">
			<div class="col-xs-4">
				<a href=""><img src="images/button-anchor-email.png" class="img-rounded"/><Crear un correo</a>
				<a href=""><img src="images/button-anchor-add-contact.png" class="img-rounded"/>Crear contactos</a>
				<a href=""><img src="images/button-database.png" class="img-rounded"/>Bases de datos</a>
			</div>
		</div>
	</div>

{% endblock %}