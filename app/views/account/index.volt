 <div class="row-fluid">
	<div id="specific" class="hero-unit">
		<h1>Mail gorilla</h1>
	</div>
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
				<td><a href="account/show/{{all.idAccount}}">{{all.companyName}}</a></td>
				<td>{{all.modeUse}}</td>
				<td>{{all.fileSpace}}</td>
				<td>{{all.messageQuota}}</td>
				<td>{{all.modeAccounting}}</td>
				<td><a href="account/edit/{{all.idAccount}}">Editar</a></td>
			</tr>
		 {%endfor%}
	    </table>
	 </div>
 </div>

