{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Correos{% endblock %}
{%block sectionsubtitle %}Administre sus correos{% endblock %}
{% block content %}
<!-- aqui inicia mi contenido -->
<div class="row-fluid">
	<div class="box">
		<div class="box-section news with-icons">
			<div class="avatar green">
				<i class="icon-lightbulb icon-2x"></i>
			</div>
			<div class="news-content">
				<div class="news-title">
					Administre sus correos
				</div>
				<div class="news-text">
					Esta es la página principal de los correos en la cuenta, aqui podrá encontrar información acerca de la configuración
					de cada correo enviado, programado, en borrador, etc. Además podrá ver las estadisticas de cada correo enviado.
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span7">
	{{ flashSession.output() }}
	</div>
	<div class="span5 text-right"> 
		<a href="{{ url('mail/setup') }}" class="btn btn-default">
			<i class="icon-plus"></i> Crear Correo
		</a>
	</div>
</div>
<br />
<div class="row-fluid">
		<!-- Lista de mis correos -->
	<div class="box">
		<div class="box-header">
			<div class="title">
				Lista de correos
			</div>
		</div>
		<div class="box-content">
	{%for item in page.items%}
			<table class="table table-normal">
				<thead></thead>
				<tbody>
					<tr>
						<td class="span5">
							<div class="box-section news with-icons">
								<div class="avatar blue">
									<i class="icon-envelope icon-2x"></i>
								</div>
								<div class="news-content">
									<div class="news-title">
										<a href="{{ url('mail/#') }}{{item.idMail}}">{{item.name}}</a>
									</div>
									<div class="news-text">
										{{item.status}} <br /> 
										Creado el {{date('Y-m-d', item.createdon)}} - Actualizado el {{date('Y-m-d', item.updatedon)}}
									</div>
								</div>
							</div>
						</td>
						{%if item.status == 'Send'%}
						<td class="span5">
							<ul class="inline pull-right sparkline-box">
								<li class="sparkline-row">
									<h4 class="green"><span>Clickeados</span> 0 </h4>
								</li>

								<li class="sparkline-row">
									<h4 class="gray"><span>Abiertos</span> 0 </h4>
								</li>
							</ul>
						</td>
						{%endif%}
						<td class="span2">
							<div class="pull-right">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
										{%if item.status == 'Draft'%}
										<li><a href="{{ url('mail/#') }}{{item.idMail}}"><i class="icon-pencil"></i> Enviar</a></li>
										<li><a href="{{ url('mail/#') }}{{item.idMail}}"><i class="icon-pencil"></i> Editar</a></li>
										{%else%}
										<li><a href="{{ url('mail/#') }}{{item.idMail}}"><i class="icon-pencil"></i> Copiar</a></li>
										{%endif%}
										<li><a href="{{ url('mail/#') }}{{item.idMail}}"><i class="icon-trash"></i> Eliminar </a></li>
									</ul>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
	{%endfor%}
		</div>
		<div class="box-footer padded">
			<div class="row-fluid">
				<div class="span5">
					<div class="pagination">
						<ul>
							{% if page.current == 1 %}
								<li class="previous"><a href="#" class="inactive"><<</a></li>
								<li class="previous"><a href="#" class="inactive"><</a></li>
							{% else %}
								<li class="previous active"><a href="{{ url('mail/index') }}"><<</a></li>
								<li class="previous active"><a href="{{ url('mail/index') }}?page={{ page.before }}"><</a></li>
							{% endif %}

							{% if page.current >= page.total_pages %}
								<li class="next"><a href="#" class="inactive">></a></li>
								<li class="next"><a href="#" class="inactive">>></a></li>
							{% else %}
								<li class="next active"><a href="{{ url('mail/index') }}?page={{page.next}}">></a></li>
								<li class="next active"><a href="{{ url('mail/index') }}?page={{page.last}}">>></a></li>		
							{% endif %}
						</ul>
					 </div>
				 </div>
				 <div class="span5">
					 <br />
					 Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
					 Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
				 </div>
			</div>
		</div>
	</div>
		<!-- Fin de mi lista de correos -->
</div>

{% endblock %}
