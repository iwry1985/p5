<!--banner-->
		<div class="user_banner">
			<?php
			$folder = find_folder($profil->ban_id_series());
			$url_banner = 'web/img/users/banner/'.$folder.'/'.$profil->ban_id_series().'/'.$profil->banner().'.jpg';

			if(file_exists($url_banner) && !empty($profil->banner())) {
				echo '<img src="'.banner_user('banner', $folder, $profil->ban_id_series(), $profil->banner()).'" alt="banner">';
			} else {
				echo '<img src="'.dossier_img('/users/banner/00.jpg').'" alt="no_banner">';
			} ?>
		</div><!--end user_banner-->

		<div class="user_header">
			<!--avatar-->
			<div class="user_avatar">
				<?php
				$folder = find_folder($profil->id());
				$url_avatar = 'web/img/users/avatar/'.$folder.'/'.$profil->avatar();
				if(file_exists($url_avatar) && !empty($profil->avatar())) {
					echo '<img src="'.img_users_url('avatar', $folder, $profil->avatar()).'?'.time().'" alt="avatar">';
				} else {
					echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
				}
				
				if($profil->shows_running() != 0 && $profil->id() == $user->id()) { ?> 
				<div class="encart_avatar">
					<a href="<?php echo base_url('profil/watchlist') ?>" class="username kalambold">Vers la watchlist</a>
				</div>
				<?php } 

				if($profil->id() != $user->id() && empty($friend)) { ?>
					<div class="encart_avatar">
						<form class="btn_friend" action="" method="post">
							<input type="submit" value="Ajouter comme ami" class="btn btn-success btn_friend" id="add_friend" name="add_friend">
							<input type="hidden" value="<?= $profil->id() ?>" class="profil_id">
							<input type="hidden" value="<?= $profil->username() ?>" class="user_pseudo">
							<input type="hidden" value="<?= base_url() ?>" class="base_url">
						</form>
					</div>
				<?php
				} elseif(!empty($friend) && $friend['friends'] == 'friends' && $profil->id() != $user->id()) { ?>
					<div class="encart_avatar">
						<form class="btn_friend" method="post">
							<input type="submit" value="Supprimer de mes amis" class="btn btn-danger btn_friend" name="delete_friend" id="delete_friend">
							<input type="hidden" value="<?= $profil->id() ?>" class="profil_id">
							<input type="hidden" value="<?= $profil->username() ?>" class="user_pseudo">
							<input type="hidden" value="<?= base_url() ?>" class="base_url">
						</form>
					</div>
				<?php
				} elseif(!empty($friend) && $friend['friends'] == 'demande') { ?>
					<div class="encart_avatar">
						<button class="btn btn-warning btn_friend">En attente de validation</button>
					</div> <?php
				}
				?>
				<div class="encart_avatar encart_attente_demande hide">
					<span class="btn btn-warning btn_friend">En attente de validation</span>
				</div>
				
			</div><!--end user_avatar-->


			<div class="user_infos">
				<div class="profil_barre">
					<div class="user_username">
						<h3 class="kalambold"><?= strip_tags($profil->username()) ?></h3>
						<div class="user_presentation">
							<p class="playtime"><?= strip_tags($profil->presentation()) ?></p>
						</div>
					</div><!--end user_username-->

					<div class="btn_update_profil">
						<?php
						if($profil->id() == $user->id()) { ?> 
						<a href="<?= base_url('profil/update') ?>" class="btn btn-primary"><span class="font_awesome">&#xf0ad;</span>Modifier le profil</a><?php
						} ?>
					</div>
				</div><!--end profil_barre-->

				<div class="count_shows_profil_box">
					<?php count_shows_box($profil, 'count_shows_profil'); ?>
				</div><!--end count_shows_profil-->

			</div><!--end user_infos-->
		</div><!--end user_header-->


		<?php include_once('_profil_menu.php'); ?>