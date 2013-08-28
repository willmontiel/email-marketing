<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('css/style.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
        {{ stylesheet_link ('css/prstyles.css') }}
		{{ stylesheet_link ('css/style.css') }}
		{{ stylesheet_link ('css/emarketingstyle.css') }}
    </head>
    <body>

<div class="row-fluid">
 	 <div class="span6" >
 		<table class="table">
			<thead>
				<tr>
					<th> </th>
					<th colspan="5">Base de Datos</th>
					<th colspan="5">Listas</th>
				</tr>
				<tr>
					<th>
						Descripcion
					</th>
					<th>
						Total Contactos
					</th>
					<th>
						Activos
					</th>
					<th>
						Des-Suscritos
					</th>
					<th>
						Rebotados
					</th>
					<th>
						Spam
					</th>
					<th>
						Total Contactos
					</th>
					<th>
						Activos
					</th>
					<th>
						Des-Suscritos
					</th>
					<th>
						Rebotados
					</th>
					<th>
						Spam
					</th>
				</tr>
			</thead>
			<tbody>
 		 {%for result in results%}
 			<tr>
				<td>{{result["Desc"]}}</td>
 				<td>{{result["CtotalDB"]}}</td>
				<td>{{result["CactiveDB"]}}</a></td>
				<td>{{result["CunsubscribedDB"]}}</td>
				<td>{{result["CbouncedDB"]}}</td>
 				<td>{{result["CspamDB"]}}</td>
				<td>{{result["CtotalList"]}}</td>
				<td>{{result["CactiveList"]}}</a></td>
				<td>{{result["CunsubscribedList"]}}</td>
				<td>{{result["CbouncedList"]}}</td>
 				<td>{{result["CspamList"]}}</td>
 			</tr>
 		 {%endfor%}
			</tbody>
 	    </table>
 	 </div>
</div>