{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Listado de plantillas para PDF</h1>
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			{{flashSession.output()}}
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 text-right">
			<a href="{{url('pdf/loadtemplate')}}" class="btn btn-sm btn-primary">
				<i class="glyphicon glyphicon-plus"></i> Cargar nueva plantilla
			</a>
		</div>
	</div>
	<!-- Lista de mis correos -->
	{% if pdfs|length != 0%}
		{% for pdf in pdfs %}
			<div class="mail-block">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
						<div class="hexagon hexagon-sm hexagon-primary">
							<div class="hexagon-wrap">
								<a href="javascript: void(0);" class="hexagon-inner toolTip" style="cursor: none;text-decoration: none; text-align: center;">
									<i>{{pdf.idPdftemplate}}</i>
								</a>
							</div>
						</div>
					</div>
						
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="mail-info">
							<div class="mail-name">
								{{pdf.name}}
							</div>
							<div class="mail-detail">
								Creado el {{date('d/M/Y g:i a', pdf.created)}} 
							</div>
							<div class="mail-detail" style="color: #777;">
								Actualizado el {{date('d/M/Y g:i a', pdf.created)}}
							</div>
						</div>
					</div>
						
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 text-right">
						<button class="btn btn-sm btn btn-info tooltip-b3" data-toggle="collapse" data-target="#preview{{pdf.idPdftemplate}}" data-placement="top" title="Detalles">
							<span class="glyphicon glyphicon-collapse-down"></span>
						</button>
							
						<a class="btn btn-sm btn btn-primary tooltip-b3" href="{{url('pdf/edit')}}/{{pdf.idPdftemplate}}" data-placement="top" title="Editar">
							<span class="glyphicon glyphicon-edit"></span>
						</a>

						<button class="ShowDialog btn btn-sm btn btn-danger tooltip-b3" data-toggle="modal" href="#modal-simple" data-id="{{ url('pdf/delete') }}/{{pdf.idPdftemplate}}" data-placement="top" title="Eliminar">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
				</div>
				<div id="preview{{pdf.idPdftemplate}}" class="collapse row">
					<hr>	
					<div style="font-size: 1.8em; text-align: center; color: #777;">Detalles</div><br />
					<div class="col-xs-12 col-sm-12 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nombre</th>
								</tr>
							</thead>
							<tbody>
								{% for account in accounts[pdf.idPdftemplate]%}
									<tr>
										<td>{{account['idAccount']}}</td>
										<td>{{account['companyName']}}</td>
									</tr>
								{% endfor %}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="small-space"></div>
		{% endfor %}
	{% else %}
		<div class="row">
			<div class="bs-callout bs-callout-warning">
				<h4>No hay ninguna plantilla para PDF en el sistema</h4>
				<p>
					Para cargar una haga <a href="{{url('pdf/loadtemplate')}}">clic aqui</a>
				</p>
			</div>
		</div>
	{% endif %}
			
			
	<div id="modal-simple" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Eliminar plantilla para PDF</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Está seguro que desea eliminar esta plantilla?
					</p>
					<p>
						Recuerde que si elimina esta plantilla se perderán todos los datos asociados
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a href="" id="delete" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#delete").attr('href', myURL );
		});
	</script>
{% endblock %}