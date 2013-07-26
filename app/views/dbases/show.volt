{{ content() }}
<div class="row-fluid">
	<div class="row-fluid">
		<div class="row-fluid">
			<div class="span8">
				<div class="modal-header">
					<h1>{{sdbase.name}}</h1>
				</div>
			</div>
			<div class="span4" >
				<span class="return-upper-right-corner"><a href="/emarketing/dbases"><h3>Regresar</h3></a></span>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span8">
				<div class="row-fluid">
					Descripcion: {{sdbase.description}}
				</div>
				<div class="row-fluid">
					Descripcion de Contactos: {{sdbase.Cdescription}}
				</div>
				<div class="row-fluid">
					Fecha
				</div>
			</div>
			<div class="span4">
				<div class="badge-number-dark">
					<span class="number-huge">{{ sdbase.Ctotal|numberf }}</span>
                    <br/>
					<span class="regular-text">Contactos totales</span>
				</div>
				<div class="badge-number-light">
					<span class="number-large text-green-color">{{ sdbase.Cactive|numberf }}</span>
                    <br/>
					<span class="regular-text">Contactos Activos</span>
                    <br/>
					<span class="number-large text-gray-color">{{ sdbase.Cinactive|numberf }}</span>
                    <br/>
                    <span class="regular-text">Contactos Inactivos</span>
                    <br/>
					<span class="number-large text-gray-color">{{ sdbase.Cunsubscribed|numberf }}</span>
                    <br/>
                    <span class="regular-text">Contactos Des-suscritos</span>
                    <br/>
					<span class="number-large text-brown-color">{{sdbase.Cbounced|numberf }}</span>
                    <br/>
                    <span class="regular-text">Contactos Rebotados</span>
                    <br/>
					<span class="number-large text-red-color">{{sdbase.Cspam|numberf }}</span>
                    <br/>
                    <span class="regular-text">Contactos Spam</span>
                    <br/>
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

