{{ content() }}
<div class="row-fluid">
    <div class="modal-header">
        <h1>{{sdbase.name}}</h1>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><span>Nombre</span></td>
                    <th><span>Descripcion</span></th>
                    <th><span>Descripcion Contactos</span></th>
                    <th><span>Contactos</span></th>
                    <th><span>Des-suscritos</span></th>
                    <th><span>Rebotados</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{sdbase.name}}
                    </td>
                    <td>
                        {{sdbase.description}}
                    </td>
                    <td>
                        {{sdbase.descriptionContacts}}
                    </td>
                    <td>
                        {{sdbase.contact}}
                    </td>
                    <td>
                        {{sdbase.unsubscribed}}
                    </td>
                    <td>
                        {{sdbase.bounced}}
                    </td>
                </tr>
            </tbody>
        </table>
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

