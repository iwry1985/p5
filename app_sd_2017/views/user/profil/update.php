<div class="container">
	<div class="frontend_content no_padding">
		<!--banner-->
		<div class="user_banner">
			<?php
			$folder = find_folder($user->ban_id_series());
			$url_banner = 'web/img/users/banner/'.$folder.'/'.$user->ban_id_series().'/'.$user->banner().'.jpg';

			if(file_exists($url_banner) && !empty($user->banner())) {
				echo '<img src="'.banner_user('banner', $folder, $user->ban_id_series(), $user->banner()).'" alt="banner">';
			} else {
				echo '<img src="'.dossier_img('/users/banner/00.jpg').'" alt="no_banner">';
			} ?>
		</div><!--end user_banner-->

		<?php
		if(!empty($_SESSION['flash'])) {
			$user->getFlash();
		} ?>

		<div class="update_avatar">
			<div class="user_avatar_update">
				<?php
				$folder = find_folder($user->id());
				$url_avatar = 'web/img/users/avatar/'.$folder.'/'.$user->avatar();
				if(file_exists($url_avatar) && !empty($user->avatar())) {
					echo '<img src="'.img_users_url('avatar', $folder, $user->avatar()).'?'.time().'" alt="avatar">';
				} else {
					echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
				} ?>
			</div>

			<!--changer avatar-->
			<div class="update_avatar_form">
				<?php
				if(!file_exists($url_avatar) || empty($user->avatar())) { ?>
				<form method="post" action="<?= base_url('Frontend_ajax_ctler/update_avatar_user') ?>" enctype="multipart/form-data" class="form_upload_avatar">
					<label for="avatar">Télécharge ton avatar (250px * 250px)</label>
					<p>Taille max. 300Ko. Formats acceptés : JPG, PNG ou GIF.
					<input type="file" class="btn btn-default form_avatar" name="avatar">
					<input type="submit" value="Envoyer" name="submit_avatar" class="btn btn-primary form_avatar">
				</form><?php
				}

				//si déjà avatar, afficher bouton supprimer
				if(file_exists($url_avatar) && !empty($user->avatar())) { ?>
					<form method="post" action="<?= base_url('Frontend_ajax_ctler/delete_avatar_user') ?>" class="btn_delete_img">
						<label for="delete_avatar">Pour uploader un nouvel avatar, supprime d'abord l'ancien !</label><br/>
						<input type="submit" value="Supprimer" name="delete_avatar" class="btn btn-danger btn_delete_img">
					</form> <?php
				} ?>
			</div>
		</div><!--end user_avatar-->

		<div class="update_profil">
			<form action="<?= base_url('Frontend_ajax_ctler/update_profil_user') ?>" method="post" class="update_profil_form">
				<div class="row">
					<div class="col-xs-12">
						<label for="presentation">A propos de toi (100 caractères max.)</label><br/>
						<textarea class="form_control" name="presentation" maxlength="100" class="presentation" rows="10" cols="50"><?= !empty($user->presentation()) ? strip_tags($user->presentation()) : '' ?></textarea>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6">
						<input type="submit" value="Modifier le profil" name="submit_update" class="btn btn-warning submit_update">
						<input type="hidden" value="<?= base_url() ?>" class="base_url">
					</div>
					<div class="col-sm-6">
						<a href="<?= base_url('profil'); ?>" class="btn btn-info btn_retour">Retour vers le profil</a>
					</div>
				</div>
			</form>
		</div><!--end update_profil-->

		<form action="" method="post" class="delete_account" >
			<input type="submit" class="btn btn-danger submit_update" name="btn_delete_account" id="btn_delete_account" value="Supprimer le compte">
		</form>

		
		
	</div>
</div>