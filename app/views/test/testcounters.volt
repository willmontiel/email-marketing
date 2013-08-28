<div class="row-fluid">
 	 <div class="span12" >
 		<table class='table table-striped'>
 		 {%for resutlado in resutlados%}
 			<tr>
 				<td>{{resutlado.Ctotal}}</td>
				<td>{{resutlado.Cactive}}</a></td>
				<td>{{resutlado.Cunsubscribed}}</td>
 				<td>{{resutlado.Cbounced}}</td>
 				<td>{{resutlado.Cspam}}</td>
 			</tr>
 		 {%endfor%}
 	    </table>
 	 </div>
</div>