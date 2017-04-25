<div class="container">
	<div class="frontend_content">
		<?php
		if(!empty($banners)) { ?>
			<form method="post" action="" class="form-group form_ban">
				<p>Choisissez une bannière pour votre profil</p>
					<div class="show_users_banners">
					<?php
					foreach($banners as $ban) {
						donnees_cachees($ban['id_series']);
						$folder = find_folder($ban['id_series']);
						$url_banner = 'web/img/users/banner/'.$folder.'/'.$ban['id_series'].'/'.$ban['img'].'.jpg';
						
						if(file_exists($url_banner)) {
							echo '<div class="single_ban">';
							if($user->banner() == $ban['img']) { ?>
								<label class="banner_selected">
									<input type="radio" value="<?= $ban['id_banner'] ?>" name="banner" class="radio-inline num_banner this_ban" checked>
								<img src="<?= banner_user('banner_min', $folder, $ban['id_series'], $ban['img']); ?>" alt="banner">
								<div class="flash"></div>

								</label> <?php
							} else { ?>
								<label class="banner_selected">
									<input type="radio" value="<?= $ban['id_banner'] ?>" name="banner" class="radio-inline num_banner this_ban">
								<img src="<?= banner_user('banner_min', $folder, $ban['id_series'], $ban['img']); ?>" alt="banner">
								<div class="flash"></div>
								</label> <?php
							} ?>
							</div>
						<?php
						}
					} ?>
					</div><!-- end show_users_banners-->
			</form>
			<div class="update_ban_btn">
				<a href="<?= base_url('profil'); ?>" class="btn-info btn_lien">Retour sur le profil</a>
				<a href="<?= base_url('series/show/'.$banners[0]['id_series']); ?>" class="btn-primary btn_lien">Retour sur la fiche série</a>
			</div>
	<?php
	} else { ?>
		<div class="clean_box">
			<p>Cette série n'a pas encore de bannière.</p>
		</div> <?php
	} ?>
	</div>
</div>