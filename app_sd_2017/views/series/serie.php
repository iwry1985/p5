<?php //functions pour toutes les données cachées nécessaires 
donnees_cachees($show->id()); ?>


<div class="container">
	<div class="frontend_content no_padding" itemscope itemtype="http://schema.org/TVSeries">


<?php /*------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			BANNER - FORM_USER_WATCH - AVIS USER - PROGRESSION 
------------------------------------------------------------------------------------------------------------------------------------------------------------*/ ?>
		<div class="show_header">
			<div class="show_banner">
				<!-- INFOS WATCH-->
				<?php
				if($watch != 'no_user') { ?>
				<div class="infos_watch">
					<div class="infos_user_watch">
						<div class="watch_form">
							<span class="color_status">
							<?php
							//si la série est dans la liste 
							if(!empty($watch)) { ?>
							<div class="add_show_hidden add_show">
								<?php //bouton ajouter pour jquery
								add_show_button($show->id()); ?>
							</div>

								<?php
								//carré couleur
								status_color($watch['statut']);
							echo '</span>';


								//si la série n'est pas cochée en 'not interested'
								if($watch['statut'] != '6') { ?>	
									<div class="watch_status episodes_box">
										<?php 
										//icon + form select statut + bouton delete
										status_form($statuts,  $watch['statut'], $show->id(), $show->etat()); ?>
									</div><!--end watch_status-->

								<?php
								//si la série est cochée en 'not interested'
								} else {
									status_color($watch['statut']);
								}

							//si la série n'est pas dans la liste
							} else { ?>
								<div class="add_show">
									<?php add_show_button($show->id()); ?>
								</div>

								<div class="status_hidden">
									<div class="watch_status">
										<?php
										status_color($watch['statut']);
										status_form($statuts, $watch['statut'], $show->id(), $show->etat()); ?>
									</div>
								</div>
							<?php
							} ?>
						</div><!--end watch_form-->

						<!-- NOTE USER-->
						<?php
						if(!empty($watch) && $watch['statut'] != '1' && $watch['statut'] != '6') { 
								note_user($watch['note'], $notes, $show->id());

						} else {
							//watch_note hidden pour jquery ?>
							<span class="hidden_note">
							<?php note_user('1', $notes, $show->id()) ?>
							</span>
						<?php
						} ?>
					</div><!--end infos_user_watch-->

					<?php
					if(!empty($watch) && $watch['statut'] != '6' && $watch['statut'] != '5' && $show->nb_episodes() > 0) { ?>
						<div class="infos_progress">
							<?php 
							//si la série est terminée, le nb d'ép vus == nb_ép_total
							if($watch['statut'] == '4' || empty($watchlist_user)) {
								$nb_ep_vus = 0;
							} else {
								$nb_ep_vus = $watchlist_user['nb_ep_vus'];	
							}

							progress($nb_ep_vus, $show->nb_episodes(), $watch['statut']);
							?>
						</div>
					<?php
					} else { ?>
						<div class="infos_progress infos_progress_hidden">
							<?php 
							progress(0, $show->nb_episodes(), 1);
							?>
						</div>
						<?php 
						} ?>
					
					
				</div><!--end infos_watch--><?php
				} ?>

				<?php
				$banner = 'web/img/show/banner/'.$show->folder().'/'.$show->img().'.jpg';
				if(file_exists($banner)) { ?>
					<img src="<?= folder_img_url('banner', $show->folder(), $show->img()).'?'.time() ?>" alt="show_banner"> 
				<?php
				} else  { ?>
					<img src="<?= img_show_url('banner', '00'); ?>" alt="no_banner">
				<?php
				} ?>
				
			</div><!--end show_banner-->
		</div><!--end show_header-->



<?php /*---------------------------------------------------------------------------------------------------------------------------------------------------
			POSTER - NOTE - ORG - NAME - VF - STATUS - RENEW/CANCEL
			BEGINNING SHOW_BOX
------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>

		<div class="show_box">

			<div class="show_poster">
				<?php
				$poster = 'web/img/show/poster/'.$show->folder().'/'.$show->img().'.jpg';
				if(file_exists($poster)) { ?>
					<img src="<?= folder_img_url('poster', $show->folder(), $show->img()).'?'.time() ?>" alt="show_poster">
				<?php
				} else { ?>
					<img src="<?= img_show_url('poster', '00'); ?>" alt="no_poster">
				<?php
				}
				if(!empty($user) && !empty($banners)) { ?>
					<div class="ban_btn">
						<a href="<?= base_url('series/banner/'.$show->id()) ?>" class="btn btn-primary btn_lien">Choisir une bannière de profil</a>
					</div><?php
				} ?>
			</div><!--end show_poster-->
				

				

			<div class="show_details">
				<?php
				//Note (if note == 0,0, no show_note)
				if($show->note() != '0,0') { 
					if($show->note() > 5) { ?>
						<div class="show_note green">
					<?php
					} else { ?>
						<div class="show_note red">
					<?php
					}
					echo $show->note().'<span class="surDix"><br/>/10</span>' ?>
					</div>
					<img class="img_star" src="<?= base_url('/web/img/icons/star_full.png'); ?>" alt="note"/>
				<?php
				} ?>
				<div class="show_title">
					<div class="show_name">
						<!--drapeau-->
						<span class="show_org" itemprop="countryOfOrigin" itemscope itemtype="http://schema.org/Country">
							<span itemprop="name" content="<?= $show->country_name(); ?>">
								<img src="<?= base_url('web/'.$show->origine()); ?>" data-toggle="tooltip" title="<?= $show->country_name(); ?>" data-placement="left" alt="<?= $show->country_name(); ?>">
							</span>
						</span>

						<!--name-->
						<h2 class="kalambold" itemprop="name">
							<?= $show->name();
							
							//bouton éditer (uniquement visible pour admin)
							if(isset($user) && $user->admin() == 'admin') { ?>
								<a href="<?= base_url('admin/show/'.$show->id().''); ?>" class="font_awesome btn_edit" data-toggle="tooltip" title="Editer" data-placement="right">&#xf040;</a> <?php
							} ?>
						</h2>
						

						<?php
						//VF
						if($show->VF() != $show->name()) { ?>
							<div class="show_vf playtime">
								<h3><?= $show->VF(); ?></h3>
							</div>
						<?php
						} ?>

						<!--statut + renouvelée/annulée-->
						<div class="show_etat">
							<div class="show_status <?php echo $show->back_color(); ?>">
								<?= $show->etat(); ?>
							</div>
							<?php 
							if($show->etat_id() == '1') { 
								if($show->renew() == 'renew') { ?>
									<div class="show_renew bck_green">
								Renouvelée
								</div>
								<?php
								}
								if($show->renew() == 'cancel') { ?>
									<div class="show_renew bck_abandonner">Annulée</div>
								<?php
								} 
							} ?>	
						</div><!--end show_etat-->
					</div><!--end show_name-->
				</div><!--end show_title-->


<?php /*---------------------------------------------------------------------------------------------------------------------------------------------------
			DATES - PRODUCER - GENRE - SEASONS - RUNTIME - NETWORK
------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>

				<!--infos sur la série (network, saisons, producer, ...)-->
				<div class="show_infos">
					<div class="show_infos_1st">
						<!-- date de début et fin-->
						<div class="show_dates">
							<span class="bold begin_date"><?= $show->begin_date(); ?></span>
							<?php
							if($show->end_date() != 0) {
								echo ' <br/><span class="bold end_date">'.$show->end_date().'</span>';
							} ?>
						</div><!--end show_dates-->
						<div class="show_infos_txt">
							<p>
							<span itemprop="author" itemscope itemtype="http://schema.org/Person">
								<?php
								//producer
								if($show->producer() != '?') {
									echo 'Créée par <span class="bold infos_color" itemprop="name">'.$show->producer().'</span>'; ?>
								<?php	
								} ?>
							</span>

							<br/>
								Genre : <span class="bold infos_color"><?= $show->genre(); ?></span>

									<span class="font_awesome little_margin chevron"> &#xf138; </span>

								<!--durée-->
								Durée : <span class="bold infos_color"><?= $show->runtime(); ?> min</span>


								<?php 
								//si la série est commencée
								if($show->etat() != 'Commandée') { ?>

										<span class="font_awesome little_margin chevron"> &#xf138; </span>

									<!--saisons-->
									<span class="bold infos_color" itemprop="numberOfSeasons"><?= $show->seasons(); ?></span> 
									<?php
									if($show->seasons() == 1) {
										echo 'saison';
									} else {
										echo 'saisons';
									} ?>

										<span class="font_awesome little_margin chevron"> &#xf138; </span>

									<!--épisodes-->
									<span class="bold infos_color" itemprop="numberOfEpisodes">
									<?= $show->nb_episodes(); ?>
									</span>
									<?php
									if($show->nb_episodes() == 1) {
										echo 'épisode';
									} else {
										echo 'épisodes';
									} 
								} ?>
							</p>
						</div>
					</div><!--end show_infos_1st-->

					<!--logo network-->
					<div class="show_network">
						<a href="#" data-toggle="tooltip" title="<?= $show->network_name(); ?>"><img src="<?= img_url($show->network()); ?>" alt="logo_network"></a>
					</div>
				</div><!--end show_infos-->


<?php /*---------------------------------------------------------------------------------------------------------------------------------------------------
			SYNOPSIS - STATS
------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>

				<div class="synopsis_stats">
					<div class="show_synopsis">
						<h4>Synopsis</h4>
						<p><?= strip_tags($show->synopsis()); ?></p>
						<p class="source">Source : allocine.fr</p>
					</div><!--end show_synopsis-->

					<?php
					if($show->nb_users() > 0) { ?>
						<div class="show_stats">
							<h4>Stats</h4>
							<div class="stats_nb_users">
								<?php
								if($show->nb_users() == 1) {
									echo '<span class="bold">'.$show->nb_users(),'</span> membre a ajouté la série';
								} else {
									echo '<span class="bold">'.$show->nb_users(),'</span> membres ont ajouté la série';
								} ?>
								</div><!--end stats_nb_users-->
								<?php stats_status_users($show->stats_statut(), 'statut'); ?>
								<?php stats_status_users($show->stats_note(), 'note'); ?>
						</div><!--end show_stats-->
					<?php
					} 
					?>
				
					
				</div><!--end synopsis_stats-->
			</div><!--end show_details-->
		</div><!--end show_box-->
<?php /*---------------------------------------------------------------------------------------------------------------------------------------------------
			END SHOW_BOX
------------------------------------------------------------------------------------------------------------------------------------------------------------*/




/*-----------------------------------------------------------------------------------------------------------------------------------------------------
					CHARACTERS
------------------------------------------------------------------------------------------------------------------------------------------------------*/
if(!empty($characters) || $user['admin'] == 'admin') { ?>
<div id="section_characters">
	<div class="characters_title">
		<h4>Personnages principaux</h4>
		<?php
		if(isset($user) && $user->admin() == 'admin') { ?>
			<a href="<?= base_url('admin/characters/'.$show->id().''); ?>" class="font_awesome btn_edit_char btn_edit_withoutBckg" data-toggle="tooltip" title="Editer" data-placement="right">&#xf040;</a> <?php
		} ?>
		<button id="hide_char" class="btn btn-warning">Afficher</button>
	</div>

	<div id="characters_box">
		<div class="show_characters">
		<?php
		foreach($characters as $character) { ?>
			<div class="show_single_char">
			<?php
			if(isset($user) && $user->admin() == 'admin') { ?>
				<a href="<?= base_url('admin/characters/'.$show->id().'/#char_'.$character->img().''); ?>" class="font_awesome btn_edit_characters" data-toggle="tooltip" title="Editer" data-placement="left">&#xf040;</a> <?php
			} 


			$char_img = 'web/img/show/characters/'.$show->characters_folder().'/'.$show->id().'/'.$character->img().'.jpg';
			if(file_exists($char_img)) { ?>
				<img src="<?= characters_img_url($show->characters_folder(), $show->id(), $character->img()); ?>" alt="<?= $character->name(); ?>">

				<!--box infos personnages-->
				<div class="character_infos" itemprop="actor" itemscope itemtype="http://schema.org/Person">
			<?php
			} else { ?>
				<img src="<?= img_show_url('characters', '00'); ?>" alt="<?= $character->name(); ?>">
				<div class="character_infos_noPic" itemprop="actor" itemscope itemtype="http://schema.org/Person">
			<?php 
			} ?>
				
					<p class="character_name"><?= $character->name(); ?></p>
					<a href="#" class="character_actor" itemprop="name"><?= $character->actor_name(); ?></a>
				</div><!--end characters_infos-->
			</div><!--end show_single_char-->
			<?php
			}

			if(isset($user) && $user['admin'] == 'admin') {
				echo '<a href="'.base_url('admin/characters_tvmaze/'.$show->id()).'" class="btn btn-info btn_lien_add_char"><span class="font_awesome _plus">&#xf067;</span><br>Ajouter un personnage</a>';
			} ?>
		<button id="close_characters">Fermer</button>
		</div><!--end show_characters-->
	</div>
	
</div><!--end section_characters-->
<?php
}
//--------------------END CHARACTERS ------------------------------------------------------------------------------------------------------------------------------------------------





 /*---------------------------------------------------------------------------------------------------------------------------------------------------
			EPISODES
------------------------------------------------------------------------------------------------------------------------------------------------------------*/
if(!empty($episodes)) { 
$now = date('Y-m-d', time()); ?> 

<div id="section_episodes">
	<div class="episodes_title">
		<h4>Episodes</h4>
			<?php
			//si la série n'est pas terminée, on affiche le décompte du prochain épisode
			if($show->etat_id() != '2') {
				//si le prochain ép est dans la bdd_next
				if(!empty($next_ep) && $next_ep->decompte() >= '0') {
					echo '<div class="ep_decompte">';
					echo '<span class="next_ep">';
							if($next_ep->decompte() == 0) {
								echo '<span class="decompte_ep_jours">Aujourd\'hui !</span><br/>';
							} else {
								echo 'J- <span class="decompte_ep_jours">'.$next_ep->decompte().'</span><br/>';
							}
							echo 'S'.$next_ep->season().'E'.$next_ep->number().' 
							<span class="decompte_ep_name">'
							.$next_ep->name().'</span>
							</span>
					</div>'; //end ep_decompte
				} 
			} ?>
	</div><!--end episodes_title-->

	<div class="listing_seasons">
	<?php
	$seasons = $show->seasons_tbEp();
	$s = 1;

	for($s; $s <= $seasons; $s++) {

		if($s == $season_userIsWatching) {
			echo '<span class="season_ season_userIsWatching">';
		} else {
			echo '<span class="season_">';
		}	
		echo '<a class="season_button" href="">s'.$s.'</a>
				<span class="hidden changeto_season">'.$s.'</span>
			 </span>';

	} ?>
	</div><!--end listing_seasons-->

	<div class="episodes_box">
		<?php require '_episodes.php'; ?>
	</div><!--end episodes_box-->
</div><!--end section_episodes-->
<?php
} ?><!-- END SECTION EPISODES-->




<?php /*---------------------------------------------------------------------------------------------------------------------------------------------------
			SECTION AMIS
------------------------------------------------------------------------------------------------------------------------------------------------------------*/?>

<?php
//Si on a des amis dans la liste et qu'on regarde la série (pas les statuts 'à commencer', 'not interersted' && 'abandonnée'), on affiche la section
if(isset($show_friends) && !empty($show_friends)) { 
$count = count($show_friends);?>
<div id="section_amis">
	<div class="sugg_title">
		<h4>
		<?php if($count == 1) {
			echo '1 ami(e) a ajouté '.$show->name();
		} else {
			echo $count.' amis ont ajouté '.$show->name();
			} ?>
		</h4>
	</div><!--end sugg_title-->

	<div class="friends_box">
		<div class="friends_watching">
			<?php
			foreach($show_friends as $friend) { ?>
				<div class="infos_friend">
					<a href="<?= base_url('profil/feed/'.$friend->username().''); ?>" data-toggle="tooltip" title="<?= $friend->username() ?>">
						<?php
						$folder = find_folder($friend->id());
						$avatar = 'web/img/users/avatar/'.$folder.'/'.$friend->avatar();
						
						if(file_exists($avatar) && !empty($friend->avatar())) {
						echo '<img src="'.img_users_url('avatar', $folder, $friend->avatar()).'" alt="avatar">';
						} else {
						echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
						} ?>
					</a>
					<div class="statut_show_friends">
						<img src="<?= img_url($friend->statut_icon); ?>" alt="statut_friend" data-toggle="tooltip" title="<?= $friend->statut ?>">
						<?php
						if($friend->note != 'Pas d\'avis') {
							echo '<img src="'.img_url($friend->note_icon).'" alt="statut_friend" data-toggle="tooltip" title="'.$friend->note.'">';
						} ?>
					</div>
				</div>
			<?php
			} //END FOREACH
		?>
		</div><!--END friends_watching-->

	</div><!--END FRIENDS_BOX-->
</div><!--end #section amis-->
<?php
}
//---------------------------END SECTION FRIENDS-------------------------------------


 /*---------------------------------------------------------------------------------------------------------------------------------------------------
			SERIES SIMILAIRES
------------------------------------------------------------------------------------------------------------------------------------------------------------*/
if(isset($similaires) && !empty($similaires)) { ?>
<div id="section_similaires">
	<div class="sugg_title">
		<h4>Autres suggestions....</h4>
	</div>

	<div class="suggestions_box">
		<?php
		foreach($similaires as $sugg) { ?>
			<div class="single_sugg">
				<?php
				$poster_sim = 'web/img/show/poster/'.$sugg->folder().'/'.$sugg->img().'.jpg';
				if(file_exists($poster_sim)) { ?>
					<img src="<?= folder_img_url('poster', $sugg->folder(), $sugg->img()); ?>" alt="show_poster">
				<?php
				} else { ?>
					<img src="<?= img_show_url('poster', '00'); ?>" alt="no_poster"><?php
				} ?>
				
					<span class="synopsis_sugg">
						<?= strip_tags($sugg->synopsis()); ?>
					</span>
					<a href="<?= base_url('series/show/'.$sugg->id()); ?>" class="lien_serie_sugg">Voir fiche</a>
			</div>

		<?php
		} ?>
	</div>
</div>


<?php
}
/*------------------------------------------------------------------------
-------------- END SIMILAIRES-------------------------------*/
?>

	</div><!--end frontend_content-->
</div><!--end container-->