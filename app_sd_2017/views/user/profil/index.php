<div class="container">
	<div class="frontend_content no_padding">
		<?php include_once('_profil_header.php'); ?>


	<?php
	//--------------------------------------------------------------------------------------------------- PLANNING SERIES DE LA SEMAINE
if(!empty($airing_this_week)) {
	$nb_shows = count($airing_this_week); ?>

	<div class="airing_this_week">
		<h5><span class="font_awesome chevron">&#xf138;</span> Planning séries de la semaine
		<?php 
		if($nb_shows == 1) {
				echo '('.$nb_shows.' série diffusée)';
			} else {
				echo '('.$nb_shows.' séries diffusées)';
			} ?></h5>

		<div class="profil_slider">
			<?php
			foreach ($airing_this_week as $ep_week) { 
				$folder = find_folder($ep_week->img);
				
				$url_poster = 'web/img/show/poster_min/'.$folder.'/'.$ep_week->img.'.jpg';
					echo '<div class="single_weekly single_planning">';
					echo '<a href="'.base_url('series/show/'.$ep_week->id_series()).'">';

				if(file_exists($url_poster)) {
					echo '<img src="'.folder_img_url('poster_min', $folder, $ep_week->img).'">';
				} else {
					echo '<img src="'.dossier_img('/show/poster_min/00.jpg').'" alt="poster" >';
				} 
					echo '</a>';
					

				//si nouvelle série, icone 'new'	
				if($ep_week->season() == '1' && $ep_week->number() == '1') {
					echo '<span class="new new_profil">
							<img src="'.dossier_img('icons/new.png').'" alt="new">
						 </span>';
				}

				//decompte
				echo '<div class="decompte_profil">';
					echo ' S<span class="episode_number_profil">'.$ep_week->season().'</span>E<span class="episode_number_profil">'.$ep_week->number().'</span><br/>';
					echo ' <span class="font_awesome chevron_orange">&#xf138;</span><span class="playtime episode_number_profil bigger">';
						if($ep_week->decompte() == 0) {
							echo ' Aujourd\'hui !';
						} elseif($ep_week->decompte() == 1) {
							echo ' Demain';
						} else {
							echo ' '.$ep_week->jour_diffusion();
						}
					echo '</span></div>';
				echo '</div>';
			} ?>
		</div><!--end profil_slider-->
	</div><!--end airing_this_week-->
<?php
} ?> 

	<?php
	//--------------------------------------------------------------------------------------------------- LES SERIES LES PLUS VUES DU MOIS
	if(!empty($most_watched)) { ?>
		<div class="most_watched">
			<h5><span class="font_awesome chevron">&#xf138;</span> Les séries du moment (les séries les plus regardées en 1 mois)</h5>
			<div class="row_most_watched">
				<?php
				foreach ($most_watched as $show) { 
					afficher_poster_et_decompte_series($show);
					} ?>
			</div>
		</div><!--end most_watched-->
	<?php
	} 



	//--------------------------------------------------------------------------------------------------- LES DERNIERES SERIES AJOUTES
	if(!empty($last_added)) { ?>
		<div class="most_watched">
			<h5><span class="font_awesome chevron">&#xf138;</span> Les dernières séries ajoutées</h5>
			<div class="row_most_watched">
				<?php
				foreach ($last_added as $show) { 
					afficher_poster_series($show);
				} ?>
			</div>
		</div><!--end most_watched-->
	<?php
	} 


	if($profil->count_seen_shows() == 0) { //si aucune série ajoutée, message "d'accueil" ?>
		<div class="clean_box">
			<img src="<?= dossier_img('icons/emoticon/smile.png'); ?>" alt="shy_icon"><br/>
			Ajoute des séries pour remplir ton profil !
		</div><?php
	} ?>



	</div><!--end frontend_content-->
</div><!--end container-->