{% extends "templates/index_b3.volt" %}

{% block sectiontitle %}Dashboard{% endblock %}
{% block content %}
{{flashSession.output()}}
	<div class="row">
		{%for widget in stats.fullPeriodStats()%}
		<div class="col-xs-6 col-md-3 col-lg-2">
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
		<div class="col-xs-6 col-md-3 col-lg-2">
			<div class="box-dashboard-summary summary-fb">
				<div>
					<p>{{widget.getTitle()}}</p>
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				{%for value in widget.getSecondaryValues()%}
					<div class="title-stats-dashboard-summary">
						{{value.name}}
					</div>
					<div class="number-stats-dashboard-summary">
						{{value.value}}
					</div>
				{%endfor%}
			</div>
		</div>
		{%endfor%}
	</div>
<br />
<br />
<br />
<br />
	<div class="row-fluid">
		<div class="col-sm-12 col-lg-10">
			<table class="table table-normal">
				<thead>
					<tr>
						<td>Ultimos 3 Envios</td>
						<td>Aperturas</td>
						<td>Clics</td>
						<td>Desuscripciones</td>
						<td>Rebotes</td>
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
{% endblock %}