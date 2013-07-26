{{ content() }}
<div class="row-fluid">
	<div class="row-fluid" id="top-info">
		<div class="row-fluid" id="nombre-bd">
			<div class="span8">
				<div class="modal-header">
					<h1>{{sdbase.name}}</h1>
				</div>
			</div>
			<div class="span4">
				<a href="dbases"><h3>Regresar</h3></a>
			</div>
		</div>

		<div class="row-fluid" id="info-bd">
			<div class="span8" id="descripTotal-bd">
				<div class="row-fluid" id="descrip-db">
					Descripcion: {{sdbase.description}}
				</div>
				<div class="row-fluid" id="descrip-contact">
					Descripcion de Contactos: {{sdbase.Cdescription}}
				</div>
				<div class="row-fluid" id="fecha-add">
					Fecha
				</div>
			</div>
			<div class="span4" id="info-contact">
				<div class="row-fluid" id="total-contacts">
					<p>{{sdbase.Ctotal}}</p>
					<p>Contactos</p>
				</div>
				<div class="row-fluid" id="detalles-contacts">
					<p>{{sdbase.Ctotal}}</p>
					<p>Contactos Activos</p>
					<p>{{sdbase.Ctotal}}</p>
					<p>Contactos Inactivos</p>
					<p>{{sdbase.Ctotal}}</p>
					<p>Contactos Des-suscritos</p>
					<p>{{sdbase.Ctotal}}</p>
					<p>Contactos Rebotados</p>
					<p>{{sdbase.Cspam}}</p>
					<p>Contactos Spam</p>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="row-fluid">
    <div class="span12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Direccion de Correo</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Añadido en la Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>            
        </table>
    </div>    
</div>