<div class="container-fluid">
	<div class="welcome_banner">
		<div class="welcome_insc">
					
			<div class="cx_titre">
				<span class="ligne_deco"></span><h2>Mot de passe perdu</h2><span class="ligne_deco">
			</div>
		</div><!--end welcome_insc-->

			<img src="<?= img_show_url('cx_banner', '05'); ?>" alt="banner">
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

			<!--pseudo-->
			<label for="recup_mail">Indique l'adresse email que tu as donnée lors de ton inscription</label>
			<input type="email" name="recup_mail" id="recup_mail" placeholder="Ton adresse mail" class="form-control" required>


			<!--submit-->
			<div class="register_button">
				<input type="submit" name="recup_submit" value="Valider" class="btn btn-primary pacificoregular">
				<!--remember me-->
			</div>
				
		</form>
		<span id="mdpPerdu">
			<p><a href="<?= base_url('connexion'); ?>">Retour sur la page de connexion</a></p>
		</span>
	</div>
</div><!-- end container-fluid-->