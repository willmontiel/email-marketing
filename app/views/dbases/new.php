<div class="row-fluid">
    <div class="modal-header">
        <h1>Nombre Base de Datos</h1>
    </div>
    <div class="row-fluid">
        {{form('Dbases/edit')}}
        <div class="row-fluid">
            <div class="span3">
                <span>Nombre</span>
            </div>
            <div class="span4">
                {{editform.render("name")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <span>Descripcion</span>                
            </div>
            <div class="span4">
                {{editform.render("description")}}
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <span>Descripcion de los Contactos</span>
            </div>
            <div class="span4">
                {{editform.render("descriptionContacts")}}
            </div>
        </div>
    </div>
    <div class="row-fluid text-center">
        {{submit_button(Guardar)}}
    </div>
    </form>
</div>
