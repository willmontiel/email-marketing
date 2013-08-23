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
 		 {%for item in page.items%}
 			<tr>
 				<td>{{item.idAccount}}</td>
				<td>{{item.companyName}}</a></td>
				<td>{{item.accountingMode}}</td>
 				<td>{{item.fileSpace}}</td>
 				<td>{{item.messageQuota}}</td>
 				<td>{{item.subscriptionMode}}</td>
				<td>{{date('F j, Y', item.createdon)}}</td>
				<td>{{date('F j, Y', item.updatedon)}}</td>
				<td>
				 <a href="account/show/{{item.idAccount}}">Ver</a><br>
				 <a href="account/edit/{{item.idAccount}}">Editar</a><br>
				 Eliminar
				</td>
 			</tr>
 		 {%endfor%}
 	    </table>
 	 </div>
	 <div class="span5">
		<div class="pagination">
			<ul>
				<li class="previous"><a href="emarketing/account/index"><span class="fui-arrow-left"><span class="fui-arrow-left"></span></span></a></li>
				<li class="previous"><a href="emarketing/account/index?page=<?=$page->before;?>"><span class="fui-arrow-left"></span></a></li>							
				<li class="next"><a href="emarketing/account/index?page=<?=$page->next;?>"><span class="fui-arrow-right"></span></a></li>
				<li class="next"><a href="emarketing/account/index?page=<?=$page->last;?>"><span class="fui-arrow-right"><span class="fui-arrow-right"></span></span></a></li>
			</ul>
		 </div>
	 </div>
	 <div class="span3">
		 <br><br>
		 Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
		 Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
	 </div>
	 <div class="span3 text-right">
		 <br>
		 {{link_to('emarketing', 'class':"btn btn-inverse", "Regresar")}}
	 </div>
    </div>
</div>
{% endblock %}