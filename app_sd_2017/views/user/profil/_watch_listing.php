<div class="watching_show_box">
	<div class="single_listing">
		<div class="info_show_watch">
			<?php

			get_base_url_val();
			$info_show = $watch_show[0];

			get_file_if_exist('poster', $info_show->id_series(), $info_show->img, 'poster_watch');
			?>

			<div class="infoshow_watchlist">
				<div class="statut_form">
					<div class="status_icon icon_watch">
						<img class="img_icon" src="<?= img_url($info_show->icon); ?>" alt="statut_icon" data-toggle="tooltip" data-placement="bottom" title="<?= $info_show->statut ?>">
					</div><!--end status_icon-->
					<div class="watchlist_status">
						<?php select_form_user('watch_status', $statuts, $info_show->stat_id, $info_show->id_series(), $info_show->etat); ?>
					</div>
				</div><!--end statut_form-->

				<div class="progress_bar_watchlist">
				<?php
				progress($ep_vus, $total_ep, $info_show->stat_id) ?>
				</div>
			</div><!--end infoshow_watchlist-->

		</div><!--end info_show_watch-->

		<div class="listing_">
			<div class="listing_seasons_to_watch kalambold">
				<?php

				if(count($nbr_season_to_see) != 0) { 
					$i = $info_show->season_single_number();
					foreach($nbr_season_to_see as $to_see) {
						if($to_see['season'] == $info_show->season()) {
							echo '<span class="seasons_watchlist season_active">
									<a href="" class="seasons_to_watch">s'.$to_see['season'].'</a>
								</span>';
						} else {
							echo '<span class="seasons_watchlist">
									<a href="" class="seasons_to_watch">s'.$to_see['season'].'</a>';
							echo '<span class="hidden changeto_season">'.$to_see['season'].'</span>';
							echo '<span class="hidden show_seasons">'.$info_show->seasons.'</span>
								</span>';
						}
						
					}
				} else {
					echo '<span class="seasons_watchlist season_active">
							<a href="" class="seasons_to_watch">s'.$info_show->seasons.'</a>
						</span>';
				} ?>
			</div><!--end listing_seasons_to_watch-->

			<div class="episodes_box">
				<div class="listing_ep_to_watch">

						<?php include_once('_table.php'); ?>
						
				</div><!--end listing_ep_to_watch-->
			</div>
		</div>
	</div><!--end single_listing-->
</div>
