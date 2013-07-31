<?php echo $this->getContent(); ?>
<div class="row-fluid">
        <div class="span12">
                <h4>Campos de la Base de Datos</h4>
                <p>Esta seccion esta dedicada a la Lectura\n
                y Edicion de los Campos Personalizados
                </p>
        </div>
</div>
<div class="row-fluid add">
	<a href="#">Adicionar</a>
</div>
<div class="row-fluid">
        <div class="span12">
                <table class="table table-hover">
                        <thead>
                                <tr>
                                        <td>
                                                Etiqueta
                                        </td>
                                        <td>
                                                Tipo
                                        </td>
                                        <td>
                                                Requerido
                                        </td>
                                        <td>
                                                Eliminar
                                        </td>
                                </tr>
                        </thead>
                        <tbody>
                                <tr>
                                        <td>
                                                <?php echo $field->name; ?>
                                        </td>
                                        <td>
                                                <?php echo $field->type; ?>
                                        </td>
                                        <td>
                                                <?php if (($field->required == 'Si')) { ?>
													<input type="checkbox" checked="checked">
												<?php } else { ?>
													<input type="checkbox" checked="unchecked">
												<?php } ?>
                                        </td>
                                        <td>
											<div class="demo-icons text-center">
												<a href="#"><span class="fui-cross"></span></a>
											</div>
                                        </td>
                                </tr>
                        </tbody>
                </table>
        </div>
</div>
<div class="row-fluid add">
        <a href="#">Adicionar</a>
</div>
