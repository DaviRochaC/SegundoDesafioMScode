<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">

			<li><a class="active-menu waves-effect waves-dark" href="http://localhost/mscode/challengetwo/views/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<?php if($_SESSION['admin']['admin_master']) { ?>
			<li>
				<a href="#" class="waves-effect waves-dark"><i class="fas fa-users"></i>Administradores</a>
				<ul class="nav nav-second-level">
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/cadastrarAdmin.php">Novo Administrador</a>
					</li>

					
						<li>
							<a href="http://localhost/mscode/challengetwo/views/admin/gerenciarAdmin.php">Gerenciar Administradores</a>
						</li>
				
				</ul>
			</li>
			<?php } ?>

			<li>
				<a href="#"><i class="far fa-id-card"></i>Clientes</a>
				<ul class="nav nav-third-level">
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/clientes/cadastrar.php">Novo Cliente</a>
					</li>
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/clientes/gerenciarClientes.php">Gerenciar Clientes</a>
					</li>




				</ul>

			</li>


			<li>
				<a href="#" class="waves-effect waves-dark"><i class="fas fa-usd-circle"></i>Orçamentos</a>
				<ul class="nav nav-second-level">
					<li>
						<a href="#">Second Level Link</a>
					</li>
					<li>
						<a href="#">Second Level Link</a>
					</li>

				</ul>
			</li>
			<li>
				<a href="empty.html" class="waves-effect waves-dark"><i class="fa fa-fw fa-file"></i> Empty Page</a>
			</li>
		</ul>

	</div>

</nav>