{% extends "templates/index.volt" %}

{% block content %}
<div class="container-fluid"> 
{{ content() }} 
   <div class="row-fluid">
 		<h1>DashBoard Cuentas</h1>
  </div>
  <div class="alert-success"><h4>{{ flashSession.output() }}</h4></div>
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
 				<th>Espacio disponible en disco (Mb)</th>
 				<th>Limite de mensajes/contactos</th>
 				<th>Modo de pago</th>
				<th>Fecha de registro</th>
				<th>Última actualización</th>
 			</tr>
 		 {%for all in allAccount%}
 			<tr>
 				<td>{{all.idAccount}}</td>
				<td>{{all.companyName}}</a></td>
 				<td>{{all.accountingMode}}</td>
 				<td>{{all.fileSpace}}</td>
 				<td>{{all.messageQuota}}</td>
 				<td>{{all.subscriptionMode}}</td>
				<td>{{all.createdon}}</td>
				<td>{{all.updatedon}}</td>
				<td>
				 <a href="account/show/{{all.idAccount}}">Ver</a><br>
				 <a href="account/edit/{{all.idAccount}}">Editar</a><br>
				 <a href="#">Eliminar</a>
				</td>
 			</tr>
 		 {%endfor%}
 	    </table>
 	 </div>
	 <div class="span12"></div>
	 <div class="text-right">
		 {{link_to('emarketing', 'class':"btn btn-inverse", "Regresar")}}
	 </div>
    </div>
</div>
{% endblock %}