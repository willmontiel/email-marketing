{{ content() }}
<div class="row-fluid">
    <div class="modal-header">
        <h1>{{edbase.name}}</h1>
    </div>
    <div class="row-fluid">
        <form action = "/emarketing/dbases/edit/{{edbase.idDbases}}", method="post">
        <div class="row-fluid">
            <div class="span3">
                <label for="name">Nombre</label>
            </div>
            <div class="span4">
                {{editform.render("name")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="description">Descripcion</label>                
            </div>
            <div class="span4">
                {{editform.render("description")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="descriptionContacts">Descripcion de los Contactos</label>
            </div>
            <div class="span4">
                {{editform.render("descriptionContacts")}}
            </div>
        </div>
    </div>
    <div class="row-fluid">
		{{submit_button("Guardar", 'class' : "btn btn-success")}}
		{{link_to('dbases', 'class':"btn btn-inverse", "Cancelar")}}
    </div>
    </form>
</div>