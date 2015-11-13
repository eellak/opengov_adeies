<div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">
		<ul class="nav" id="side-menu">
		<?php 
			global $side_menu;
			if(!empty($side_menu) and $side_menu != '')
				print_sidebar(); 
			else{ 
		?>
				<li>
					<a href="<?=URL?>"><i class="fa fa-home fa-fw"></i> Πίνακας Ελέγχου</a>
				</li>
		<?php 
			}
		?>
		</ul>
	</div>
	<!-- /.sidebar-collapse -->
</div>