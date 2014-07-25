<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('vendors/bootstrap_v2/css/bootstrap.css') }}
        {{ stylesheet_link('css/style.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
        {{ stylesheet_link ('css/prstyles.css') }}
                {{ stylesheet_link ('css/style.css') }}
                {{ stylesheet_link ('css/emarketingstyle.css') }}
    </head>
    <body>
                <div class="row">
                         <div class="span6" >
                                <table class="table table-bordered">
                                        <thead>
                                                <tr>
                                                        <th> </th>
                                                        <th colspan="6">Base de Datos</th>
                                                        <th colspan="6">Lista</th>
                                                        <th colspan="6">Lista Nueva</th>
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
                                                                Inactivos
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
                                                                Inactivos
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
                                                                Inactivos
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
                                                <td>{{result["CactiveDB"]}}</td>
                                                <td>{{result["CinactiveDB"]}}</td>
                                                <td>{{result["CunsubscribedDB"]}}</td>
                                                <td>{{result["CbouncedDB"]}}</td>
                                                <td>{{result["CspamDB"]}}</td>
                                                <td>{{result["CtotalList"]}}</td>
                                                <td>{{result["CactiveList"]}}</td>
                                                <td>{{result["CinactiveList"]}}</td>
                                                <td>{{result["CunsubscribedContactlist"]}}</td>
                                                <td>{{result["CbouncedContactlist"]}}</td>
                                                <td>{{result["CspamList"]}}</td>
                                                <td>{{result["CtotalListNew"]}}</td>
                                                <td>{{result["CactiveListNew"]}}</td>
                                                <td>{{result["CinactiveListNew"]}}</td>
                                                <td>{{result["CunsubscribedContactlistNew"]}}</td>
                                                <td>{{result["CbouncedContactlistNew"]}}</td>
                                                <td>{{result["CspamListNew"]}}</td>
                                        </tr>
                                 {%endfor%}
                                        </tbody>
                                </table>
                         </div>
                </div>
        </body>
</html>