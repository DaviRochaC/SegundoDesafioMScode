<nav class="navbar-default navbar-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">

			<li><a class="active-menu waves-effect waves-dark" href="http://localhost/mscode/challengetwo/views/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<?php if ($_SESSION['admin']['admin_master']) { ?>
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
						<a href="http://localhost/mscode/challengetwo/views/admin/orcamentos/novoOrcamento.php">Novo orçamento</a>
					</li>
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/orcamentos/listarOrcamentosCriados.php">Orçamentos criados</a>
					</li>
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/orcamentos/listarOrcamentosAceitos.php">Orçamentos aceitos</a>
					</li>
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/orcamentos/listarOrcamentosRejeitados.php">Orçamentos rejeitados</a>
					</li>
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/orcamentos/listarOrcamentosCancelados.php">Orçamentos cancelados</a>
					</li>
					<li>
						<a href="http://localhost/mscode/challengetwo/views/admin/orcamentos/listarOrcamentosFaturados.php">Orçamentos faturados</a>
					</li>

				</ul>
			</li>
		</ul>

	</div>

</nav>