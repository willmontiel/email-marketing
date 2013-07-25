{{ content() }}
        <div class="row-fluid">
            <div class="modal-header">
                <h1>Bases de Datos</h1>
            </div>
            <div class="modal-header">
                <h2>{{sdbase.name}}</h2>
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

