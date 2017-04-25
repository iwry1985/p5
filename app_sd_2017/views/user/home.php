<?php
if(isset($user)) { ?>
<div class="container">
	<div class="frontend_content no_padding">
	<div class="user_watching_infos">
		<div class="user_presentation_home">
			<div class="header_home">
				<a href="<?= base_url('profil'); ?>">
				<?php include_once('_avatar.php'); ?>
				</a>
<?php //--------------------------------------------------------------------------------------------------
			if($weekly_count != 0) { ?>
				<div class="infos_show_home">
					<div class="weekly_ep_box">
						<h5>
						<span class="font_awesome chevron_orange">&#xf138;</span>
						<span class="pacificoregular bigger">Salut, <span class="count_yellow"><?= $user->username().'</span> !</span>';
						echo ' Tu as vu ';
						echo '<span class="count_yellow">'.$weekly_count.'</span>';
							if($weekly_count == 1) {
								 echo ' épisode';
							} else {
								echo ' épisodes';
							} ?> 
							<span class="italic">cette semaine ! (du lundi au dimanche)</span>
						</h5>
					
						<div class="slider_header">

							<?php //SLIDER IMG EPISODES VUS LA SEMAINE
							foreach ($seen_this_week as $weekly) { 
								$folder = find_folder($weekly['img']);
				
								$url_poster = 'web/img/show/poster_min/'.$folder.'/'.$weekly['img'].'.jpg';

								echo '<div class="single_weekly">';
									echo '<a href="'.base_url('series/show/'.$weekly['id']).'">';

											if(file_exists($url_poster)) {
												echo '<img src="'.folder_img_url('poster_min', $folder, $weekly['img']).'">';
											} else {
												echo '<img src="'.dossier_img('/show/poster_min/00.jpg').'" alt="poster">';
											} 

									echo '</a>';
									echo '<div class="count_weekly_by_show">
											'.$weekly['count_ep'].'
										  </div>';

								echo '</div>';
							} ?>
						</div><!--end #slider_weekly-->
					</div><!--weekly_ep_box-->
				</div><!--end infos_show_home--> <?php
			


			} else { //SI PAS D'EPISODES VUS PENDANT LA SEMAINE, on affiche les counts séries de $user
				if($user->count_seen_shows() != 0 && $user->shows_toBegin() != 0) {
					count_shows_box($user, 'count_shows_home');
				} else {
				//si $user n'a pas de séries, on affiche un message d'accueil
				echo '<div class="msg_accueil">Bienvenue sur Seriesdom <br/><span class="username_accueil">'.$user->username().'</span> !</div>';
				}
			} ?>

			</div><!--end header_home
		</div><!--end user_presentation_home-->
	</div><!--end user_watching_infos-->

	<?php
//---------------------------------------------------------------------------------------------------
	//EPISODES DIFFUSES LA VEILLE
	if(!empty($aired_yesterday)) { ?>
	<div class="yesterday_ep_box">
		<div class="yest_ep_title">
			<?php
			$nb_aired = count($aired_yesterday);
			if($nb_aired == 1) {
				echo '<h4>Diffusé hier</h4>';
			} else {
				echo '<h4>Diffusés hier</h4>';
			} ?>
			<button id="hide_ep" class="btn btn-warning">Afficher</button>
		</div><!--end title-->
		
		<div id="slider_box">
			<div class="slider_ep_aired_yesterday">
			<?php

			foreach($aired_yesterday as $serie) {
				$folder = find_folder($serie['img']);
		
				$url_poster = 'web/img/show/poster_min/'.$folder.'/'.$serie['img'].'.jpg';

			echo '<div class="single_aired_yesterday">';
				echo '<a href="'.base_url('series/show/'.$serie['id']).'" data-toggle="tooltip" title="'.$serie['name'].'">';
				
				if(file_exists($url_poster)) {
					echo '<img src="'.folder_img_url('poster_min', $folder, $serie['img']).'" alt="poster">';
				} else {
					echo '<img src="'.dossier_img('/show/poster_min/00.jpg').'" alt="poster">';
				} 
				echo '</a>'; //fin du lien vers la fiche de la série

				echo '<div class="ep_yest_infos">';
				$season = double_unit($serie['season']);
				$episode = double_unit($serie['number']);

				echo 'S<span class="number">'.$season.'</span>E<span class="number">'.$episode.'</span>';
				echo '</div>';

			echo '</div>';  //END SINGLE_AIRED_YESTERDAY
			}
			echo '</div>'; 
		echo '</div>';  //END SLIDER_EP_AIRED_YESTERDAY
	echo '</div>'; //END slider box
	} 
//------------------------------------------------------------------------------------------END EP DIFFUSES LA VEILLE ?>

	<div class="menu_home_visionnages">
		<h4>Derniers épisodes vus</h4>
		<div class="menu_home">
			<ul><span class="base_url hide"><?= base_url(); ?></span>
			<?php if(!empty($friends)) { 
					if($info == 'friends_and_user') { ?>
						<li class="menu_home_active"><a href="<?= base_url('home/feed/friends') ?>">Mes amis</a><?php
					} else { ?>
						<li><a href="<?= base_url('home/feed/friends') ?>">Mes amis</a><?php
						}
				}
				
				if($info == 'all') { ?>
					<li class="menu_home_active"><a href="<?= base_url('home/feed/all') ?>">Tout le monde</a> <?php
				} else { ?>
					<li><a href="<?= base_url('home/feed/all') ?>">Tout le monde</a><?php
				}
				

				if($count_ep_users > 0) {
					if($info == 'just_user') { ?>
 						<li class="menu_home_active"><a href="<?= base_url('home/feed/me') ?>">Moi uniquement</a> <?php
					} else { ?>
						<li><a href="<?= base_url('home/feed/me') ?>">Moi uniquement</a> <?php
					}
				} ?>
				
			</ul>
		</div><!--end menu_home-->
	</div><!--end menu_home_visionnages-->

	<?php
	if(!empty($user->visionnages())) { ?>
	<div class="watching_box">
	
		<?php require_once('_watching_box.php'); ?>
	</div><!--END WATCHING_BOX-->

	<?php
	} else { ?>
		<div class="clean_box">
			<p>Aucune activité.</p>
		</div> <?php
	}//si aucun épisodes à afficher
	?>
	</div><!--END FRONTEND_CONTENT-->
</div><!--END CONTAINER-->





<?php //pas pas de $user
} else {
	redirect(base_url('connexion'));
}
