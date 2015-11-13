<?php
/* 	-------------------------------------------------------------------------------------
*	The Content of the Main Page.
*  -------------------------------------------------------------------------------------*/
?>
<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered">
			<tbody>
			<?php
				global $application_list;
				foreach($application_list as $slug => $app){
					echo '<tr><td><a href="'.URL.'/?p='.$slug.'|home" class="btn btn-large btn-block btn-primary" type="button">'.$app['name'].'</a></td>';
					echo '<td>'.$app['description'].'</td></tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>