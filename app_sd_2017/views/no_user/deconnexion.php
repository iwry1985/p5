<div class="container-fluid">
	<div class="welcome_banner">
		<div class="welcome_insc">
					
			<div class="cx_titre">
				<span class="ligne_deco"></span><h2>Au revoir !</h2><span class="ligne_deco">
			</div>
		</div><!--end welcome_insc-->
			<img src="<?= img_show_url('cx_banner', '06'); ?>" alt="banner">
	</div><!--end welcome_banner-->

		<!--Errors-->
		<?php
		if(!empty($_SESSION['flash'])) {
			foreach($_SESSION['flash'] as $type => $message) {
				echo '<div class="alert alert-'.$type.'">'.$message.'</div>';
			}
			unset($_SESSION['flash']); 
		} ?>

	<div class="logo_deco">
		<a class="welcome_logo" href="<?= base_url(); ?>"><img src="<?php base_url(); ?>web/img/logo/logo2.png"></a>
		<h2>Merci d'avoir utilisé SeriesDOM</h2>
	</div>

</div><!-- end container-fluid-->