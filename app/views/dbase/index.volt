{{ content() }}
<div class="alert-error"><h4>{{ flashSession.output() }}</h4></div>
<div class="row-fluid">
	<div class="modal-header">
		<h1>Bases de Datos</h1>
	  <div class="text-right"> <a href="dbase/new"><h5>Crear Base de Datos</h5></a></div>
	</div>
</div>
<div class="row-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="navbar">
				<ul class="nav-tabs">
					<li class="active"><a href="#">General</a></li>
					<li><a href="#">Campos</a></li>
					<li><a href="#">Listas</a></li>
					<li><a href="#">Segmentos</a></li>
					<li><a href="#">Estadisticas</a></li>
					<li><a href="#">Formularios</a></li>
				</ul>
			</div>
		</div>
	</div>
	{%for dbase in dbases%}
		<div class="row-fluid">
			<div class="span6" >
				<div class="row-fluid break-word">
					<h3><a href="dbase/show/{{dbase.idDbase}}">{{dbase.name}}</a></h3>
					<span>{{dbase.description}}</span>
				</div>
				<div class="row-fluid">
					<div class="span3 text-center">
						<div class="row-fluid">
							<span class="number-medium text-gray-color text-center">26</span>
						</div>
						<div class="row-fluid">
							<span class="fui-radio-checked"></span> Segmentos
						</div>	
					</div>
					<div class="span3 text-center">
						<div class="row-fluid">
							<span class="number-medium text-gray-color ">12</span>
						</div>
						<div class="row-fluid">
							<span class="fui-list"></span> Listas
						</div>	
					</div>
				</div>
			</div>
			<div class="span3 ">
				<div class="row-fluid">
					<table>
						<tr>
							<td class="text-right">
								<dl>
									<dd>{{dbase.Cactive|numberf}}</dd>
									<dd>{{dbase.Cinactive|numberf}}</dd>
									<dd>{{dbase.Cunsubscribed|numberf}}</dd>
									<dd>{{dbase.Cbounced|numberf}}</dd>
									<dd>{{dbase.Cspam|numberf}}</dd>
								</dl>
							</td>
							<td class="text-left">
								<dl>
									<dd>Activos</dd>
									<dd>Inactivos</dd>
									<dd>Des-suscritos</dd>
									<dd>Rebotados</dd>
									<dd>Spam</dd>
								</dl>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="span2">
				<dl>
					<dd><a href="dbase/edit/{{dbase.idDbase}}">Editar</a></dd>
					<dd><a href="#delete{{dbase.idDbase}}" data-toggle="modal">Eliminar</a></dd>
					<dd><a href="#">Agregar Contacto</a></dd>
				</dl>
			</div>
		</div>
	{%endfor%}
 </div>
		
{%for dbase in dbases%}
<div id="delete{{dbase.idDbase}}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Seguro que Desea Eliminar</h3>
	</div>
	<div class="modal-body">
		<form action = "/emarketing/dbase/delete/{{dbase.idDbase}}", method="post">
			<p>Para eliminar escriba la palabra "DELETE"</p>
			{{text_field("delete")}}
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
		<button class="btn btn-primary">Eliminar</button>
	</div>
    </form>
</div>
{%endfor%}
    