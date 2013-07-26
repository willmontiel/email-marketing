{{ content() }} 
<div class="row-fluid">
	<div id="specific" class="hero-unit">
		<h1>Mail gorilla</h1>
	</div>
 </div>
  	 <div class="text-right">
	  <h3><a href="account/new" >Crear nueva cuenta</a></h3>
     </div>
 <div class="row-fluid">
	 <div class="span12" >
		 <table class='table table-striped'>
			<tr>
				<th>Id</th>
				<th>Nombre de la cuenta</th>
				<th>Modo de uso</th>
				<th>Espacio para archivos</th>
				<th>Cuota de mensajes</th>
				<th>Modo de pago</th>
			</tr>
		 {%for all in allAccount%}
			<tr>
				<td>{{all.idAccount}}</td>
				<td>{{all.companyName}}</a></td>
				<td>{{all.modeUse}}</td>
				<td>{{all.fileSpace}}</td>
				<td>{{all.messageQuota}}</td>
				<td>{{all.modeAccounting}}</td>
				<td>
				 <a href="account/show/{{all.idAccount}}">Ver</a><br>
				 <a href="account/edit/{{all.idAccount}}">Editar</a><br>
				 <a href="#delete" data-toggle="modal">Eliminar</a>
				</td>
			</tr>
		 {%endfor%}
	    </table>
	 </div>
 </div>

 <div id="delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Seguro que Desea Eliminar</h3>
  </div>

  <div class="modal-body">
    <form action = "/emarketing/account/delete/{{all.idAccount}}" method="post">
      <p>Para eliminar escriba la palabra "DELETE"</p>
      {{text_field("delete")}}
  </div>

  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    <button class="btn btn-primary">Eliminar</button>
  </div>

 </div>

