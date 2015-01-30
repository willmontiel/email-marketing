{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
{% endblock%}

{% block content %}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}
	
	<div class="row header-background" style="border-top: 1px solid #362f2d;">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div class="header">
				<div class="title">{{account.companyName}}</div>
				<div class="title-info">Historial de puntos</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
			<div class="contact-indicator">
				<span class="total-contacts">
					{% if score.score is defined%}
						{{score.score}}
					{% else %}
						0
					{% endif %}
				</span><br />
				<span class="text-contacts" style="padding-right: 14px;">Puntos</span>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<a href="{{url('account')}}" class="btn btn-sm btn-default">Regresar</a>
		</div>
	</div>
	
	<div class="small-space"></div>

	{% if page.items|length != 0%}
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 <div class="text-center">
					<ul class="pagination">
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}"><i class="glyphicon glyphicon-fast-backward"></i></a>
						</li>
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}?page={{ page.before }}"><i class="glyphicon glyphicon-step-backward"></i></a>
						</li>
						<li>
							<span><b>{{page.total_items}}</b> registros </span><span>Página <b>{{page.current}}</b> de <b>{{page.total_pages}}</b></span>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}?page={{page.next}}"><i class="glyphicon glyphicon-step-forward"></i></a>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}?page={{page.last}}"><i class="glyphicon glyphicon-fast-forward"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">id</th>
							<th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Gestión inteligente</th>
							<th class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Correo</th>
							<th class="col-xs-2 col-sm-2 col-md-2 col-lg-2">Fecha</th>
							<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1 orange-sigma star-shadow" style="font-size: 0.9em !important;">
								<span class="glyphicon glyphicon-star"></span> Puntuación
							</th>
							<th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								Acumulado
							</th>
						</tr>
					</thead>
					<tbody>
						{% for item in page.items %}
							<tr>
								<td>{{item.idScorehistory}}</td>
								<td>
									<div class="">
										<div class="title" style="font-size: 1em !important;">
											{% if item.smartmanagment.name is defined AND item.smartmanagment.name is not null%}
												{{item.smartmanagment.name}}
											{% else %}
												Indefinido
											{% endif %}		
										</div>
										<div class="title-info" style="padding-left: 0 !important;">
											{% if item.smartmanagment.description is defined AND item.smartmanagment.description is not null%}
												{{item.smartmanagment.description}}
											{% else %}
												Indefinido
											{% endif %}
										</div>
									</div>
								</td>
								<td>
									<div class="">
										<div class="title" style="font-size: 1em !important;">
											{% if item.mail.name is defined AND item.mail.name is not null%}
												{{item.mail.name}}
											{% else %}
												Indefinido
											{% endif %}		
										</div>
										<div class="title-info" style="padding-left: 0 !important;">
											{% if item.mail.subject is defined AND item.mail.subject is not null%}
												{{item.mail.subject}}
											{% else %}
												Indefinido
											{% endif %}
										</div>
									</div>
								</td>
								<td>{{date('d/M/Y H:i', item.createdon)}}</td>
								<td>{{item.score}}</td>
								<td>
									{{accumulated[item.idScorehistory]}}
								</td>
							</tr>
						{% endfor %}
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				 <div class="text-center">
					<ul class="pagination">
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}"><i class="glyphicon glyphicon-fast-backward"></i></a>
						</li>
						<li class="{{ (page.current == 1)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}?page={{ page.before }}"><i class="glyphicon glyphicon-step-backward"></i></a>
						</li>
						<li>
							<span><b>{{page.total_items}}</b> registros </span><span>Página <b>{{page.current}}</b> de <b>{{page.total_pages}}</b></span>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}?page={{page.next}}"><i class="glyphicon glyphicon-step-forward"></i></a>
						</li>
						<li class="{{ (page.current >= page.total_pages)?'disabled':'enabled' }}">
							<a href="{{ url('account/scorehistory') }}/{{account.idAccount}}?page={{page.last}}"><i class="glyphicon glyphicon-fast-forward"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	{% else %}
		<div class="row">
			<div class="bs-callout bs-callout-warning">
				<h4>Esta cuenta no tiene ninguna puntuación</h4>
				<p>
					Esta cuenta no tiene un historial de puntuación, esto quiere decir que es nueva, o no ha cumplido ninguna gestión inteligente
				</p>
			</div>
		</div>
	{% endif %}
{% endblock %}