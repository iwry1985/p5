<div class="container">
	<div class="frontend_content no_padding">
		<?php include_once('_profil_header.php'); ?>

		<div id="section_bilan">
			<?php if(!empty($dates)) { ?>
			<div class="menu_bilan">
				<!--year-->
				<div class="menu_bilan_year">
					<?php
					$prec = $year - 1;
					if(in_array($prec, $dates))  {
						echo '<a class="btn_year" href="'.base_url('profil/bilan/12/'.$prec.'/#section_bilan').'" data-toggle="tooltip" title="'.$prec.'"><span class="font_awesome chevron_suiv">&#xf137;</span></a>';
					}
					echo '<p class="year_affich">'.$year.'</p>';
					$suiv = $year +1;

					if(in_array($suiv, $dates))  {
						echo '<a class="btn_year" href="'.base_url('profil/bilan/1/'.$suiv.'/#section_bilan').'" data-toggle="tooltip" title="'.$suiv.'"><span class="font_awesome chevron_suiv">&#xf138;</span></a>';
					} ?>
					
				</div>

				<!--months-->
				<ul class="menu_bilan_month">
					<?php
					foreach($mois as $mois_select) {
						if($mois_select['number'] == $month) {
							echo '<li class="active_month btn_month"><a href="'.base_url('profil/bilan/'.$mois_select['number'].'/'.$year.'/#section_bilan').'">'.$mois_select['french'].'</a></li>';
						} else {
							echo '<li><a class="btn_month" href="'.base_url('profil/bilan/'.$mois_select['number'].'/'.$year.'/#section_bilan').'">'.$mois_select['french'].'</a></li>';
						}
					} ?>
				</ul>
			</div><!--end menu_bilan--><?php
			} 


			//-----------------------------------------------------------------------------------------------------------------------------
			// BILAN DE L'ANNEE (uniquement quand l'année est terminée et s'affiche au mois de décembre)
			//-----------------------------------------------------------------------------------------------------------------------------
			$cu_year = date('Y');
			if($year != $cu_year && !empty($bilan_year)) {
				echo '<h5 class="titre_bilan_annee">
						<span class="font_awesome chevron">&#xf138;</span> 
						Bilan de l\'année '.$year;
				echo '</h5>';

				echo '<div class="bilan_count">';
					afficher_count_bilan($bilan_year['yearly_ep_seen'], $bilan_year['yearly_began_shows'], $bilan_year['yearly_shows_ended'], $bilan_year['yearly_shows_added'], $bilan_year['yearly_fav']);
				echo '</div>';
			} ?>


			<!-- Bilan du mois-->
			<h5>
				<span class="font_awesome chevron">&#xf138;</span> 
				Bilan du mois
			</h5>

			<div class="bilan_count">
				<?php afficher_count_bilan($total_ep_seen, count($monthly_show_begin), count($monthly_show_ended), count($shows_added), count($coup_de_coeur)); ?>
			</div>

			


			<?php
			if(!empty($dates)) {
				echo '<div class="section_bilan_all">';
				//----------------------------------------------------------------------------------------EPISODES VUS DU MOIS
				if(!empty($ep_seen)) { ?>
					<h5><span class="font_awesome chevron">&#xf138;</span> 
					<?php if ($total_ep_seen == 1) {
						echo '1 épisode vu';
					} else {
						echo $total_ep_seen .' épisodes vus';
					} ?> </h5>

				<div class="row_most_watched">
					<?php
					foreach ($ep_seen as $show) { 
						afficher_poster_et_decompte_series($show);
							} ?>
				</div> <?php
				}


				//----------------------------------------------------------------------------------------SERIES COMMENCEES
				if(!empty($monthly_show_begin)) { ?>
					<h5><span class="font_awesome chevron">&#xf138;</span> 
					<?php if (count($monthly_show_begin) == 1) {
						echo '1 série commencée';
					} else {
						echo count($monthly_show_begin) .' séries commencées';
					} ?> </h5>

				<div class="row_most_watched">
					<?php
					foreach ($monthly_show_begin as $show) { 
						afficher_poster_series($show);
							} ?>
				</div> <?php
				}


				//----------------------------------------------------------------------------------------SERIES TERMINEES
				if(!empty($monthly_show_ended)) { ?>
					<h5><span class="font_awesome chevron">&#xf138;</span> 
					<?php if (count($monthly_show_ended) == 1) {
						echo '1 série terminée';
					} else {
						echo count($monthly_show_ended) .' séries terminées';
					} ?> </h5>

				<div class="row_most_watched">
					<?php
					foreach ($monthly_show_ended as $show) { 
						afficher_poster_series($show);
							} ?>
				</div> <?php
				}


				//----------------------------------------------------------------------------------------SERIES AJOUTES
				if(!empty($shows_added)) { ?>
					<h5><span class="font_awesome chevron">&#xf138;</span> 
					<?php if (count($shows_added) == 1) {
						echo '1 série ajoutée';
					} else {
						echo count($shows_added) .' séries ajoutées';
					} ?> </h5>

				<div class="row_most_watched">
					<?php
					foreach ($shows_added as $show) { 
						afficher_poster_series($show);
							} ?>
				</div> <?php
				} 


				//----------------------------------------------COUP DE COEUR
				if(!empty($coup_de_coeur)) { ?>
					<h5><span class="font_awesome chevron">&#xf138;</span> 
					<?php if (count($coup_de_coeur) == 1) {
						echo '1 coup de coeur';
					} else {
						echo count($coup_de_coeur) .' coups de coeur';
					} ?> </h5>

				<div class="row_most_watched">
					<?php
					foreach ($coup_de_coeur as $show) { 
						afficher_poster_series($show);
							} ?>
				</div> <?php
				} 
			 echo '</div>';
			} ?>
		</div><!--end section_bilan-->
	</div>
</div>