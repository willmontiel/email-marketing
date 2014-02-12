{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-bullhorn"></i> Mensajes administrativos{%endblock%}
{% block sectionsubtitle %}Lista de mensajes administrativos activos e inactivos{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span6">
			{{flashSession.output()}}
		</div>
		<div class="span6">
			<a href="{{url('flashmessage/new')}}" class="btn btn-default pull-right"><i class="icon-plus"></i> Crear nuevo mensaje</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Mensajes informativos
					</div>
				</div>
				<div class="box-content">
					<table class="table table-bordered">
						<thead>
						</thead>
						<tbody>
							 {%for item in page.items%}
								<tr>
									<td>
										<div class="preview-message-{{item.type}}"></div>
									</td>
									<td>
										<h5>{{item.name}}</h5>
										{{ date('d/M/Y',item.createdon)}}<br />
										Desde el <strong>{{ date('d/M/Y H:i',item.start)}}</strong>, Hasta el <strong>{{ date('d/M/Y H:i',item.end)}}</strong>
									</td>
									<td class="pull-right">
										<a href="#" class="btn btn-green" rel="tooltip" data-placement="left" title="" data-original-title="{{item.message}}"><i class="icon-search"></i> Ver</a>
										<a href="{{url('flashmessage/edit')}}/{{item.idFlashMessage}}" class="btn btn-default"><i class="icon-pencil"></i> Editar</a>
										<a class="ShowDialog btn btn-default" data-toggle="modal" href="#modal-simple" data-id="{{url('flashmessage/delete')}}/{{item.idFlashMessage}}"><i class="icon-trash"></i> Eliminar</a>
									</td>
								</tr>
							{% endfor %}
						</tbody>
					</table>
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
										<li class="previous active"><a href="{{ url('flashmessage/index') }}"><<</a></li>
										<li class="previous active"><a href="{{ url('flashmessage/index') }}?page={{ page.before }}"><</a></li>
									{% endif %}

									{% if page.current >= page.total_pages %}
										<li class="next"><a href="#" class="inactive">></a></li>
										<li class="next"><a href="#" class="inactive">>></a></li>
									{% else %}
										<li class="next active"><a href="{{ url('flashmessage/index') }}?page={{page.next}}">></a></li>
										<li class="next active"><a href="{{ url('flashmessage/index') }}?page={{page.last}}">>></a></li>		
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
		</div>
	</div>
	
	<div id="modal-simple" class="modal hide fade" aria-hidden="false">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <h6 id="modal-tablesLabel">Eliminar Mensaje</h6>
		</div>
		<div class="modal-body">
			Esta seguro que desea eliminar este mensaje.
		</div>
		<div class="modal-footer">
		  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
		  <a href="" id="deleteMsg" class="btn btn-danger" >Eliminar</a>
		</div>
	</div>

	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMsg").attr('href', myURL );
		});
	</script>
{% endblock %}