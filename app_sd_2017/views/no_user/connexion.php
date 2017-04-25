<div class="container-fluid">
	<div class="welcome_banner">
		<div class="welcome_insc">
					
			<div class="cx_titre">
				<span class="ligne_deco"></span><h2>Se connecter</h2><span class="ligne_deco">
			</div>
		</div><!--end welcome_insc-->

			<img src="<?= img_show_url('cx_banner', '03'); ?>" alt="banner">
	</div><!--end welcome_banner-->

	<div class="inscription_form">
		<form action="" method="post">
		<!--Errors-->
		<?php
		if(!empty($_SESSION['flash'])) {
			foreach($_SESSION['flash'] as $type => $message) {
				echo '<div class="alert alert-'.$type.'">'.$message.'</div>';
			}
			unset($_SESSION['flash']); 
		} ?>

		<?php
		if(!isset($token)) { ?>
			<!--pseudo-->
			<label for="cx_username">Pseudo</label>
			<input type="text" name="cx_username" id="cx_username" placeholder="Ton pseudo" class="form-control" required>


			<!-- password-->
			<label for="cx_password">Mot de passe</label>
			<input type="password" name="cx_password" id="cx_password" placeholder="Ton mot de passe" class="form-control" required>

			<!--submit-->
			<div class="register_button">
				<input type="submit" name="cx_submit" value="Valider" class="btn btn-primary pacificoregular">
				<!--remember me-->
				<span class="check_remember"> 
					<input type="checkbox" name="remember_me" id="remember_me"><label for="remember_me"> Se souvenir de moi</label>
				</span>
			</div>
				
		</form>
		<span id="mdpPerdu">
			<p><a href="<?= base_url('recupmdp'); ?>">Mot de passe perdu ?</a></p>
		</span><?php
		} ?>
	</div>
</div><!-- end container-fluid-->