<div class="container-fluid">
	<div class="welcome_banner">
		<div class="welcome_insc">
					
			<div class="cx_titre">
				<span class="ligne_deco"></span><h2>S'inscrire</h2><span class="ligne_deco">
			</div>
		</div><!--end welcome_insc-->

			<img src="<?= img_show_url('cx_banner', '02'); ?>" alt="banner">
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
			<label for="username">Choisissez un pseudo (Entre 3 et 20 caractères)</label>
			<input type="text" name="username" id="username" placeholder="Votre pseudo" class="form-control" required>

			<!--mail-->
			<label for="email">Votre adresse email</label>
			<input type="email" name="email" id="email" placeholder="Votre adresse email" class="form-control" required>

			<!-- password-->
			<label for="password">Votre mot de passe (minimum 5 caractères)</label>
			<input type="password" name="password" id="password" placeholder="Votre mot de passe" class="form-control" required>

			<!--password confirm-->
			<label for="password_confirm">Confirmez votre mot de passe</label>
			<input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirmez votre mot de passe" required>

			<!--submit-->
			<span class="register_button">
				<input type="submit" name="sign_in" value="Valider" class="btn btn-warning pacificoregular">
			</span>
				
		</form>
	</div>
</div><!-- end container-fluid-->