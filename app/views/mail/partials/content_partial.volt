<div id="choose-content" style="display: block;">
	<div class="row">
		<div class="col-md-3 text-center">
			<button type="button" class="btn btn-default btn-lg" id="editor">
				<span class="glyphicon glyphicon-star"></span> Editor avanzado
			</button>
		</div>
		<div class="col-md-3 text-center">
			<button type="button" class="btn btn-default btn-lg" id="template">
				<span class="glyphicon glyphicon-star"></span> Plantillas prediseñadas
			</button>
		</div>
		<div class="col-md-3 text-center">
			<button type="button" class="btn btn-default btn-lg" id="html">
				<span class="glyphicon glyphicon-star"></span> Html desde cero
			</button>
		</div>
		<div class="col-md-3 text-center">
			<button type="button" class="btn btn-default btn-lg" id="import">
				<span class="glyphicon glyphicon-star"></span> Importar desde una url
			</button>
		</div>
	</div>
</div>		
<br />
<div id="show-content"></div>
<div id="buttons-content" style="display: none;">
	<br />
	<div class="row">
		<h4>Texto plano</h4>
		{{ '{{view Ember.TextArea valueBinding="plainText" name="plainText" id="plainText" rows="4" class="col-sm-12"}}' }}
	</div>
	<br />
	<div class="row text-right">
		<a href="#" class="btn btn-default">Descartar cambios</a>
		<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
	</div>
</div>
	{#
	<div class="span3 action-nav-button">
		<a href="#" title="New Project">
			<i class="icon-picture"></i>
			<span>Editor avanzado</span>
		</a>
		<span class="triangle-button green"><i class="icon-plus"></i></span>
		<br />
		<p>
			Cree contenido con estilo, bien estructurado y con los mejores estandares
			de forma muy sencilla en unos pocos minutos.
		</p>
		</div>

		<div class="span3 action-nav-button">
		<a href="#" title="Messages">
			<i class="icon-magic"></i>
			<span>Plantillas prediseñadas</span>
		</a>
		<span class="triangle-button green"><i class="icon-ok"></i></span>
		<br />
		<p>
			Utilice alguna de nuestras plantillas prediseñadas, que le servirán de guía para 
			crear un contenido con estilo y llamativo a los correos.
		</p>
		</div>

		<div class="span3 action-nav-button">
		<a href="#" title="Files">
			<i class="icon-pencil"></i>
			<span>Html desde cero (avanzado)</span>
		</a>
		<span class="triangle-button blue"><i class="icon-align-left"></i></span>
		<br />
		<p>
			Utilice nuestro editor de código html, para crear contenido de correos desde código fuente
			(recomendado solo a usuarios avanzados).
		</p>
		</div>

		<div class="span3 action-nav-button">
		<a href="#" title="Users">
			<i class="icon-upload-alt"></i>
			<span>Importar desde una url</span>
		</a>
		<span class="triangle-button blue"><i class="icon-download-alt"></i></span>
		<br />
		<p>
			Importe contenido html desde un enlace externo
		</p>
	</div>	
	#}