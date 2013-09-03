<div class="box-section news with-icons relative">
		{{'{{#if isSpam}}'}}
		<span class="triangle-button red">
			<i class="icon-warning-sign"></i>
		</span>
		{{'{{/if}}'}}
	<div {{'{{bindAttr class=":avatar isReallyActive:green:blue"}}'}}>
		<i class="icon-user icon-2x"></i>
	</div>
	<div class="news-content">
		<div class="pull-right">
			<div class="btn-group">
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li>{{ '{{#linkTo "contacts.edit" this}}' }}<i class="icon-pencil"></i> Editar{{ '{{/linkTo}}' }}</li>
					<li>{{ '{{#linkTo "contacts.show" this}}' }}<i class="icon-search"></i> Ver detalles{{ '{{/linkTo}}' }}</li>
					<li>{{ '{{#linkTo "contacts.delete" this}}' }}<i class="icon-trash"></i> Eliminar{{ '{{/linkTo}}' }}</li>
				</ul>
			</div>
		</div>
		<div class="news-title">{{ '{{#linkTo "contacts.show" this}}{{email}}{{/linkTo}}' }}</div>
		{{ '{{#if isEmailBlocked}}' }}
		<span class="badge badge-dark-red">Correo bloqueado</span>
		{{ '{{/if}}' }}
		{{ '{{#if isSpam}}' }}
		<span class="badge badge-dark-red">Spam</span>
		{{ '{{/if}}' }}
		{{ '{{#if isBounced}}' }}
		<span class="badge badge-red">Rebotado</span>
		{{ '{{/if}}' }}
		{{ '{{#unless isSubscribed}}' }}
		<span class="badge badge-gray">Desuscrito</span>
		{{ '{{/unless}}' }}
		{{ '{{#unless isActive}}' }}
		<span class="badge badge-blue">Sin confirmar</span>	
		{{ '{{/unless}}' }}
		<div class="news-text">
			{{'{{name}}'}}<br/>
			{{'{{lastName}}'}}
		</div>
	</div>
</div>
