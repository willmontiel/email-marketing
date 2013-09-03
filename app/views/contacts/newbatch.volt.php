 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">

		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

        <?php echo Phalcon\Tag::getTitle(); ?>

        <?php echo Phalcon\Tag::stylesheetLink('stylesheets/application.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/prstyles.css'); ?>
		
		<!--[if lt IE 9]>
		<?php echo Phalcon\Tag::javascriptInclude('javascripts/vendor/html5shiv.js'); ?>
		<?php echo Phalcon\Tag::javascriptInclude('javascripts/vendor/excanvas.js'); ?>
		<![endif]-->
		
		
		<?php echo Phalcon\Tag::javascriptInclude('javascripts/application.js'); ?>
		

        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<!-- nav bar -->
		<div class="navbar navbar-inverse">
			<div class="navbar-inner">
				<div class="container-fluid">
					<div class="nav-collapse collapse" id="nav-collapse-01">
						<a class="brand" href="/emarketing/">Mail Station</a>
						<ul class="nav pull-right">
							<li class="toggle-primary-sidebar hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-primary">
								<button type="button" class="btn btn-navbar"><i class="icon-th-list"></i></button>
							</li>
							<li class="hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-top"><button type="button" class="btn btn-navbar"><i class="icon-align-justify"></i></button></li>
						</ul>

						
						<div class="nav-collapse nav-collapse-top collapse">

							<ul class="nav full pull-right">
								<li class="dropdown user-avatar">
									<!-- the dropdown has a custom user-avatar class, this is the small avatar with the badge -->
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<span>
											<img class="menu-avatar" src="<?php echo $this->url->get('images/avatars/avatar1.jpg'); ?>" /> <span> <?php echo $this->userObject->username; ?> <i class="icon-caret-down"></i></span>
											
											<span class="badge badge-dark-red">0</span>
											
										</span>
									</a>
									<!-- Menu desplegable del usuario -->
									<ul class="dropdown-menu">
										<!-- imagen del usuario -->
										<li class="with-image">
											<div class="avatar">
												<img src="<?php echo $this->url->get('images/avatars/avatar1.jpg'); ?>" />
											</div>
											
											<span><?php echo $this->userObject->firstName; ?> <?php echo $this->userObject->lastName; ?></span>
										</li>
										<li class="divider"></li>

										<li><a href="#"><i class="icon-cog"></i> <span>Configuración</span></a></li>
										<li>
											<a href="#"><i class="icon-envelope"></i><span>Mensajes</span>
												
												<span class="label label-dark-red pull-right">0</span>
											</a>
										</li>
										<li><a href="<?php echo $this->url->get('session/logout'); ?>"><i class="icon-off"></i> <span>Logout</span></a></li>
									</ul>
								</li>
							</ul>
							<ul class="nav pull-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administrar <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="<?php echo $this->url->get('account'); ?>">Cuentas</a></li>
										<li><a href="#">Usuarios</a></li>
									</ul>
								</li>
							</ul>
						</div>
						
					</div>	
				</div>
			</div>
		</div>
		<!-- /nav bar -->
			

		<!-- sidebar -->
		<div class="sidebar-background">
			<div class="primary-sidebar-background"></div>
		</div>
		<!-- /sidebar -->
		<div class="primary-sidebar">

			<!-- Main nav -->
			<ul class="nav nav-collapse collapse nav-collapse-primary">
				<li class="active">
					<span class="glow"></span>
					<a href="<?php echo $this->url->get(''); ?>">
						<i class="icon-dashboard icon-2x"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="<?php echo $this->url->get('contactlist#/lists"'); ?>">
						<i class="icon-user icon-2x"></i>
						<span>Contactos</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="<?php echo $this->url->get(''); ?>">
						<i class="icon-envelope icon-2x"></i>
						<span>Campañas</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="<?php echo $this->url->get(''); ?>">
						<i class="icon-edit icon-2x"></i>
						<span>Autorespuestas</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="<?php echo $this->url->get(''); ?>">
						<i class="icon-bar-chart icon-2x"></i>
						<span>Estadísticas</span>
					</a>
				</li>
				<li class="dark-nav ">

					<span class="glow"></span>
					<a class="accordion-toggle collapsed " data-toggle="collapse" href="#zwz2Ux5SfP">
						<i class="icon-beaker icon-2x"></i>
						<span>UI Lab<i class="icon-caret-down"></i></span>

					</a>

					<ul id="zwz2Ux5SfP" class="collapse ">

						  <li class="">
							<a href="../ui_lab/buttons.html">
								<i class="icon-hand-up"></i> Buttons
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/general.html">
								<i class="icon-beaker"></i> General elements
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/icons.html">
								<i class="icon-info-sign"></i> Icons
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/grid.html">
								<i class="icon-th-large"></i> Grid
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/tables.html">
								<i class="icon-table"></i> Tables
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/widgets.html">
								<i class="icon-plus-sign-alt"></i> Widgets
							</a>
						  </li>

					</ul>

				</li>


			</ul>

			<div class="hidden-tablet hidden-phone">
			  <div class="text-center" style="margin-top: 60px">
				<div class="easy-pie-chart-percent" style="display: inline-block" data-percent="89"><span>89%</span></div>
				<div style="padding-top: 20px"><b>CPU Usage</b></div>
			  </div>

			  <hr class="divider" style="margin-top: 60px">

			  <div class="sparkline-box side">

				<div class="sparkline-row">
				  <h4 class="gray"><span>Orders</span> 847</h4>
				  <div class="sparkline big" data-color="gray"><!--28,11,24,24,8,20,26,22,16,6,12,15--></div>
				</div>

				<hr class="divider">
				<div class="sparkline-row">
				  <h4 class="dark-green"><span>Income</span> $43.330</h4>
				  <div class="sparkline big" data-color="darkGreen"><!--16,20,6,19,25,22,9,13,7,10,15,4--></div>
				</div>

				<hr class="divider">
				<div class="sparkline-row">
				  <h4 class="blue"><span>Reviews</span> 223</h4>
				  <div class="sparkline big" data-color="blue"><!--20,18,21,17,5,7,29,9,8,14,23,8--></div>
				</div>

				<hr class="divider">
			  </div>
			</div>

		</div>

		<!-- content -->
		<div class="main-content">
			<div class="container-fluid">
				<div class="row-fluid">

					<div class="area-top clearfix">
						<div class="pull-left header">
							<h3 class="title">
								<i class="icon-dashboard"></i>Dashboard
						</h3>
						  </div>

					</div>
				</div>
			</div>
			<div class="container-fluid padded">
				<div class="row-fluid">
					<!-- Inicio de contenido -->
					
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<h3>Creacion Rapida de Contactos</h3>
		</div>
		<br>
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="box">
					<div class="box-header">
						<span class="title">Resultado importación por lotes de contactos</span>
						<ul class="box-toolbar">
							<li><span class="label label-green"><?php echo $total; ?> Contactos válidos</span></li>
						</ul>
					</div>
					<div class="box-content">
						<table class="table table-normal">
							<thead>
								<tr>
									<td>Email</td>
									<td>Nombre</td>
									<td>Apellido</td>
									<td>Estado</td>
								</tr>
							</thead>
							<tbody>
						<?php foreach ($batch as $content) { ?>
								<tr>
									<td><?php echo $content['email']; ?></td>
									<td><?php echo $content['name']; ?></td>
									<td><?php echo $content['last_name']; ?></td>
									<?php if ($content['status'] == '1') { ?>
									<td>Crear</td>
									<?php } else { ?>
									<td>Repetido</td>
									<?php } ?>
								</tr>
						<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<br><br>
				<?php if ($total + $limit > $limit) { ?>
					<div class="alert alert-block">
						<a class="close" data-dismiss="alert">×</a>
						<h4 class="alert-heading">Advertencia!</h4><br>
						<p>Ha sobrepasado la capacidad máxima para guardar contactos:</p>
							<dl>
								<dd>Capacidad máxima de contactos: <span class="green-label"><?php echo $limit; ?></span></dd>
								<dd>Contactos actuales: <span class="blue-label"><?php echo $limit; ?></span></dd>
								<dd>Contactos que intenta crear: <span class="orange-label"><?php echo $total; ?></span></dd>
							</dl>
							<p>
								Se ha excedido en <span class="red-label"><?php echo ($limit + $total) - $limit; ?></span> contactos, si continúa con el proceso se guardarán los contactos hasta que llegue al limite,
								el resto serán ignorados.
							</p>
							<p>
								Si esta seguro y desea continuar dé click en crear
							</p>
					</div>
				<?php } ?>
				<br><br>
				<a href="/emarketing/contacts/importbatch/<?php echo $idContactlist; ?>" class="btn btn-success">Crear</a>
				<a href="/emarketing/contactlist/show/<?php echo $idContactlist; ?>#/contacts/newbatch" class="btn btn-inverse">Cancelar</a>
			</div>
		</div>
	</div>
</div>

					<!-- Fin de contenido -->
				</div>
				
			</div>		
		</div>
		<!-- /content -->
    </body>
</html>
