<div class="container">
	<div class="frontend_content no_padding">

	<!--bannière profil-->
	<div class="header_banner">
	<?php
			$folder = find_folder($user->ban_id_series());
			$url_banner = 'web/img/users/banner/'.$folder.'/'.$user->ban_id_series().'/'.$user->banner().'.jpg';

			if(file_exists($url_banner) && !empty($user->banner())) {
				echo '<img src="'.banner_user('banner', $folder, $user->ban_id_series(), $user->banner()).'" alt="banner">';
			} else {
				echo '<img src="'.dossier_img('/users/banner/00.jpg').'" alt="no_banner">';
			} ?>
	</div>


	<div class="header_watchlist user_header">
		<div class="user_avatar avatar_watchlist">
		<?php
		$folder = find_folder($user->id());
		$url_avatar = 'web/img/users/avatar/'.$folder.'/'.$user->avatar();
			
			if(file_exists($url_avatar) && !empty($user->avatar())) {
				echo '<img src="'.img_users_url('avatar', $folder, $user->avatar()).'" alt="avatar">';
			} else {
				echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
			} ?>
			
			<div class="encart_avatar encart_watchlist">
				<a href="<?php echo base_url('profil') ?>" class="username kalambold">Vers le profil</a>
			</div>
		</div><!--end user_avatar-->


		<?php //SLIDER
		if(!empty($coming_shows)) { ?>
			<div class="infos_shows_coming_back">
				<div class="weekly_ep_box box_slider_watchlist">
					
					<div class="slider_header">

						<?php //SLIDER IMG EPISODES VUS LA SEMAINE
						foreach ($coming_shows as $shows) { 
							echo '<div class="single_weekly">';

							get_file_if_exist('poster_min', $shows->id_series(), $shows->img, 'single_weekly');

							//si nouvelle série, on affiche icone 'new'
							if($shows->season() == '1') {
								echo '<span class="new">
										<img src="'.dossier_img('icons/new.png').'" alt="new">
									  </span>';
							}

							//decompte des jours (si J-6, on affiche le jour de diffusion)
							echo '<div class="decompte_box dec_watch">';
								if($shows->season() != '1') {
									echo 'Saison '.$shows->season_single_number();
									echo ' <span class="font_awesome chevron_orange">&#xf138;</span>';
								}
									echo '<span class="playtime episode_number_profil bigger">';
											if($shows->decompte() == 0) {
												echo ' Ce soir !';
											} elseif($shows->decompte() == 1) {
												echo ' Demain';
											} else {
												if($shows->decompte() <= 6) {
													echo ' '.$shows->jour_diffusion();
												} else {
													echo ' J - '.$shows->decompte();
												}
											
											}
									echo '</span>
								</div>';
							echo '</div>';
						} ?>
					</div><!--end slider_header-->
				</div><!--weekly_ep_box-->
		</div><!--infos_shows_coming_back--> 
		<?php
		} ?>
	</div><!--end header_watchlist-->


	<?php 
	//menu 
	include_once('_profil_menu.php'); ?>


	<?php
//--------------------------------------------------------------------------------------------- SECTION WATCHLIST -> LIST NAME + RANDOM + EPISODES
	if(!empty($count_unseen)) { ?>
		<div class="watchlist_box">
			<h5><?= $count_show ?> séries <span class="font_awesome chevron">&#xf138;</span> <?= $count_ep ?>  épisodes à voir !
			</h5>

			<?php
			if($count_show > 2) { 

				random_form('random_watchlist', $count_show, 'die', 'die_random_watchlist') ?>
				
			<!--Résultats alétoires-->
			<div id="random_selec"></div>
			<?php
			} ?>


			<div class="listing_running_shows">
			<?php
			//listing de toutes les séries avec ép à voir
			foreach($count_unseen as $running_shows) {
				echo '<div class="single_name kalambold">';
						echo '<a href="" class="btn_running_show">'.$running_shows['name'].' <span class="count_yellow count_episodes">('.$running_shows['count_episodes'].')</span></a>';
						echo '<span class="hidden id_series">'.$running_shows['id'].'</span>';
						echo '<span class="hidden show_seasons">'.$running_shows['seasons'].'</span>';
				echo '</div>';
			} ?>
			</div>
		</div><!--watchlist_box-->	


		<!--section listing_ep-->
		<div class="watch_listing">
			<div class="episodes_box">
				<?php include_once('_watch_listing.php'); ?>
			</div>
		</div>

<?php
	} else { 
		//sinon, pas d'ép à voir ?>
		<div class="clean_box">
			<img src="<?= dossier_img('icons/emoticon/relax.png'); ?>" alt="relax_icon">
			<p>Ta watchlist est vide !</p>
		</div>
	<?php
	} ?>

	</div><!--end frontend_content-->
</div><!--end container-->