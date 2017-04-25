<div class="container-fluid">
	<div class="welcome_banner">
		<div class="welcome_insc">
					
			<div class="cx_titre">
				<span class="ligne_deco"></span><h2>Réinitialisation du mot de passe</h2><span class="ligne_deco">
			</div>
		</div><!--end welcome_insc-->

			<img src="<?= img_show_url('cx_banner', '04'); ?>" alt="banner">
	</div><!--end welcome_banner-->

	<div class="explications_mdp">
		<p class="ex_big yellow kalambold">Bienvenue sur la nouvelle version de <stron>SeriesDOM</stron> !</p>
		<p>Comme tu l'as remarqué, beaucoup de choses ont changé et quelques modifications ont été nécessaires... à commencer par le système de connexion !<p>
		<p>Afin d'accroître la sécurité, le site utilise un nouvel algorithme qui nécessite donc la réinitialisation de ton mot de passe.<br/>
		Tu peux soit conserver l'ancien et simplement le réintroduire dans les champs ci-dessous, soit changer et introduire le nouveau.</p>

		<p>Merci de ta compréhension et toutes mes excuses pour la gêne occasionée.<br/>
		J'espère que la nouvelle version te plaira !
		</p>
	</div>

	<div class="inscription_form">
		<form action="" method="post">
		<?php
		if(!empty($_SESSION['flash'])) {
			foreach($_SESSION['flash'] as $type => $message) {
				echo '<div class="alert alert-'.$type.'">'.$message.'</div>';
			}
			unset($_SESSION['flash']); 
		} ?>

			<!--pseudo-->
			<label for="init_username">Votre pseudo</label>
			<input type="text" name="init_username" id="username" placeholder="Votre pseudo" class="form-control" required>

			<!--mail-->
			<label for="init_old_password">Votre ancien mot de passe</label>
			<input type="password" name="init_old_password" id="old_password" placeholder="Votre ancien mot de passe" class="form-control" required>

			<!-- password-->
			<label for="init_password">Votre nouveau mot de passe (minimum 5 caractères)</label>
			<input type="password" name="init_password" id="password" placeholder="Votre nouveau mot de passe" class="form-control" required>

			<!--password confirm-->
			<label for="init_password_confirm">Confirmez votre nouveau mot de passe</label>
			<input type="password" name="init_password_confirm" id="password_confirm" class="form-control" placeholder="Confirmez votre mot de passe" required>

			<!--submit-->
			<span class="register_button">
				<input type="submit" name="init_submit" value="Valider" class="btn btn-warning pacificoregular">
			</span>
				
		</form>
	</div>
</div><!-- end container-fluid-->