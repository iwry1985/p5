<?php $now = date('Y-m-d', time()); ?>
<table class="table table-hover tableau_ep">
	<thead>
		<tr>
			<th class="th_ep">Episode</th>
			<th class="th_titre">Titre</th>
			<th class="th_date">Date de diffusion</th>
			<th>
				<?php
				$coche_season = $episodes[0]->season();
				$nb_ep = count($unseen_ep_from_season);

				if(isset($watch) && !empty($watch) && $watch != 'no_user' && $watch['statut'] == '3' && $unseen_ep_from_season != 'no_user') { ?>
				<div class="btn_tout_cocher">
					<?php 
					if(empty($nb_ep)) {
						add_all_unseen_ep_from_season('eye_decoche', $coche_season, 'Décocher la saison', 'delete', count($episodes), 'serie');
					} else {
						if($nb_ep > 1) {
							add_all_unseen_ep_from_season('eye_coche', $coche_season, 'Cocher toute la saison', 'add', $nb_ep, 'serie');
						}
					} ?>
				</div> <?php
				} else {
					echo '<span class="coche_decoche hidden_eye">';
						add_all_unseen_ep_from_season('eye_coche', $coche_season, 'Cocher toute la saison', 'add', $nb_ep, 'serie');
					echo '</span>';
				} ?>
			</th>
		</tr>
	</thead>

	<tbody>

	<?php
	foreach($episodes as $episode) {
		//si $user a la série dans sa liste
		if(isset($watch) && !empty($watch) && $watch != 'no_user') {

			//si l'épisode a été diffusé ou qu'il est diffusé auj sur netflix
			if(($episode->airdate() < $now && $episode->airdate() != '0000-00-00') || ($episode->airdate() == $now && $show->network_id() == 6)) {

				//si $user a vu l'épisode ou que la série est cochée en terminée
				if(in_array($episode->id_ep(), $watchlist_user) || $watch['statut'] == '4') {
					echo '<tr class="tr_ep_seen tr_ep_aired tr_tableau_ep">';
				} else { //ép diffusé et $user ne l'a pas encore vu
						echo '<tr class="tr_ep_aired tr_tableau_ep">';
				}
					
			} else { //ép pas encore diffusé
				echo '<tr class="tr_ep_not_aired tr_tableau_ep">';
				}
			//END IF ISSET($watchlist) && !empty($watchlist)------ 


		//$user n'a pas la série dans sa liste
		} else {
			//ép déjà diffusé ou diffusé auj sur netflix

			if($episode->airdate() < $now  && $episode->airdate() != '0000-00-00' || ($episode->airdate() == $now && $show->network_id() == '6')) {
				echo '<tr class="tr_ep_aired tr_tableau_ep">';
			} else {
				echo '<tr class="tr_ep_not_aired tr_tableau_ep">';
			}
		} //END ELSE ------- ------------------------------------------------------------------------------------------------------------------------------?>

					
		<div itemprop="episode" itemscope itemtype="http://schema.org/TVEpisodes">
			<th class="th_ep">S<?= $episode->season(); ?>E<span itemprop="episodeNumber"><?= $episode->number(); ?></span></th>
						
			<th class="th_ep_name"><span itemprop="name" class="episode_name"><?= $episode->name(); ?></span>
				<?php
				if(isset($user) && $user->admin() == 'admin') { 
					//form admin pour modification épisode ?>
					<div class="admin_ep hidden">
						<form action="" method="post">
							<input type="text" value="<?= $episode->name() ?>" class="ep_name_change">
							<input type="hidden" value="<?= $episode->id_ep() ?>" class="id_ep">
							<input type="image" src="<?= dossier_img('icons/validate.png')?>" class="validate_ep">
							<input type="image" src="<?= dossier_img('icons/return.png')?>" class="return_ep">
						</form>
					</div>
					<a href="" class="font_awesome btn_edit_episode" data-toggle="tooltip" title="Editer" data-placement="top">&#xf040;</a> <?php
				} ?>
			</th>

			<?php
			if($episode->airdate() != '0000-00-00') {
				echo '<th class="th_ep_date"><span class="episode_airdate">'.$episode->date_fr().'</span>';
			} else {
				echo '<th class="th_ep_date"><span class="episode_airdate">NC</span>';
			}

			if(isset($user) && $user->admin() == 'admin') { 
					//form admin pour modification épisode ?>
					<div class="admin_ep_airdate hidden">
						<form action="" method="post">
							<input type="date" value="<?= $episode->airdate() ?>" class="ep_date_change">
							<input type="image" src="<?= dossier_img('icons/validate.png')?>" class="validate_ep_date">
							<input type="hidden" value="<?= $episode->id_ep() ?>" class="id_ep">
							<input type="hidden" value="<?= base_url() ?>" class="base_url">
							<input type="image" src="<?= dossier_img('icons/return.png')?>" class="return_ep_date" value="supprimer">
						</form>
					</div>
					<a href="" class="font_awesome btn_edit_ep_airdate" data-toggle="tooltip" title="Editer" data-placement="top">&#xf040;</a> <?php
				} ?>
			</th>
		</div>
						
			<th class="th_icon_eye">
			<?php
			//si la série est en cours dans la liste de $user
			if($watch != 'no_user' && $watch['statut'] == '3' && ($episode->airdate() < $now && $episode->airdate() != '0000-00-00') || ($episode->airdate() == $now && $show->network_id() == 6)) {

				echo '<span class="coche_decoche">';

				//si il a vu l'ép
				if(in_array($episode->id_ep(), $watchlist_user)) {
					add_delete_ep($episode->id_ep(), 'eye_decoche', $show->id());
				} else { //s'il n'a pas vu l'ép
					add_delete_ep($episode->id_ep(), 'eye_coche', $show->id());
				}
					echo '</span>';

			//eye caché pour jquery
			} else { ?>
				<span class="coche_decoche hidden_eye">
					<?php add_delete_ep($episode->id_ep(), 'eye_coche', $show->id()); ?>
				</span>
			<?php
			}
			echo '</th>';

			echo '<th class="delete_ep_btn">';
			if(isset($user) && $user->admin() == 'admin') { ?>
				<a href="" class="font_awesome btn_delete_ep" data-toggle="tooltip" title="Supprimer" data-placement="top">&#xf1f8;</a> <?php
			} ?>
			</th>
		</tr>
		<?php
	} //------------END FOREACH-------------------------------------------------------------------------------------------- ?>
	</tbody>
</table>
	