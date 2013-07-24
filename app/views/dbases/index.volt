{{ content() }}
        <div class="row-fluid">
            <div class="modal-header">
                <h1>Bases de Datos</h1>
            </div>
        </div>
        <div class="row-fluid">
            {%for dbase in dbases%}
                <div class="row-fluid">
                    <div class="span6" >
                        <h3><a href="dbases/read?id={{dbase.idDbases}}">{{dbase.name}}</a></h3>
                        <span>{{dbase.description}}</span>
                        <div class="">
                                Segmentos (icono)
                                Listas (icono)
                        </div>
                    </div>
                    <div class="span3">
                        <dl>
                            <dd>{{dbase.contact}} Contactos</dd>
                            <dd>{{dbase.unsubscribed}} Desuscritos</dd>
                            <dd>{{dbase.bounced}} Bounced</dd>
                        </dl>
                    </div>
                    <div class="span2">
                        <dl>
                            <dd><a href="dbases/edit?id={{dbase.idDbases}}">Editar</a></dd>
                            <dd><a href="#">Eliminar</a></dd>
                            <dd><a href="#">Agregar Contacto</a></dd>
                        </dl>
                    </div>
                </div>
            {%endfor%}
         </div>
    