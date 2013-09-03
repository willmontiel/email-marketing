<div class="box-section news with-icons relative">
		<?php echo '{{#if isSpam}}'; ?>
		<span class="triangle-button red">
			<i class="icon-warning-sign"></i>
		</span>
		<?php echo '{{/if}}'; ?>
	<div <?php echo '{{bindAttr class=":avatar isReallyActive:green:blue"}}'; ?>>
		<i class="icon-user icon-2x"></i>
	</div>
	<div class="news-content">
		<div class="pull-right">
			<div class="btn-group">
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><?php echo '{{#linkTo "contacts.edit" this}}'; ?><i class="icon-pencil"></i> Editar<?php echo '{{/linkTo}}'; ?></li>
					<li><?php echo '{{#linkTo "contacts.show" this}}'; ?><i class="icon-search"></i> Ver detalles<?php echo '{{/linkTo}}'; ?></li>
					<li><?php echo '{{#linkTo "contacts.delete" this}}'; ?><i class="icon-trash"></i> Eliminar<?php echo '{{/linkTo}}'; ?></li>
				</ul>
			</div>
		</div>
		<div class="news-title"><?php echo '{{#linkTo "contacts.show" this}}{{email}}{{/linkTo}}'; ?></div>
		<?php echo '{{#if isSpam}}'; ?>
		<span class="badge badge-dark-red">Spam</span>
		<?php echo '{{/if}}'; ?>
		<?php echo '{{#if isBounced}}'; ?>
		<span class="badge badge-red">Rebotado</span>
		<?php echo '{{/if}}'; ?>
		<?php echo '{{#unless isSubscribed}}'; ?>
		<span class="badge badge-gray">Desuscrito</span>
		<?php echo '{{/unless}}'; ?>
		<?php echo '{{#unless isActive}}'; ?>
		<span class="badge badge-blue">Sin confirmar</span>	
		<?php echo '{{/unless}}'; ?>
		<div class="news-text">
			<?php echo '{{name}}'; ?><br/>
			<?php echo '{{lastName}}'; ?>
		</div>
	</div>
</div>
