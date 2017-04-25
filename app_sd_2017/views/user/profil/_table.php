<table class="table table-hover tableau_ep tableau_watchlist">
	<thead>
		<tr>
			<th class="th_ep">Episode</th>
			<th class="th_titre">Titre</th>
			<th class="th_date">Date de diffusion</th>
			<th>
				<?php
				$coche_season = $watch_show[0]->season(); ?>

				<div class="btn_tout_cocher">
					<?php 
					$nb_ep = count($unseen_ep_from_season);
					if($nb_ep > 1) {
							add_all_unseen_ep_from_season('eye_coche', $coche_season, 'Tout cocher', 'add', $nb_ep, 'watchlist');
					} ?>
				</div>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		include_once('_tbody.php'); ?>
	</tbody>
</table>