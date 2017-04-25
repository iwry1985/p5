<div class="container-fluid">
	<div class="welcome_banner">
		<div class="welcome_insc">
					
			<div class="cx_titre">
				<span class="ligne_deco"></span><h2>Réinitialisation du mot de passe</h2><span class="ligne_deco">
			</div>
		</div><!--end welcome_insc-->

			<img src="<?= img_show_url('cx_banner', '06'); ?>" alt="banner">
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

			<!--mot de passe-->
			<label for="reset_password">Ton nouveau mot de passe (min. 5 caractères):</label>
			<input type="password" name="reset_password" id="recup_mail" placeholder="Ton nouveau mot de passe" class="form-control" required>

			<label for="reset_password_confirm">Confirme ton mot de passe :</label>
			<input type="password" name="reset_password_confirm" id="recup_mail" placeholder="Ton mot de passe" class="form-control" required>


			<!--submit-->
			<div class="register_button">
				<input type="submit" name="reset_submit" value="Valider" class="btn btn-primary pacificoregular">
				<!--remember me-->
			</div>
				
		</form>
		<span id="mdpPerdu">
			<p><a href="<?= base_url('connexion'); ?>">Retour sur la page de connexion</a></p>
		</span>
	</div>
</div><!-- end container-fluid-->