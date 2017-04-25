<?php
		
		foreach($watch_show as $episode) { ?>
		<?php
		//si on a l'id tv_maze, l'ép vient d'être cocher avec le btn 'cocher la saison'. (il faut juste un détail pour séparer les seen et unseen)
		if(isset($episode->tv_maze)) { ?>
			<tr class="tr_ep_seen"> <?php
		} else { ?>
			<tr class="tr_ep_aired"><?php
		} ?>
				<th class="th_ep">
					S<?= $episode->season(); ?>E<?= $episode->number(); ?>
				</th>

				<th class="th_ep_name">
					<?= $episode->name(); ?>
				</th>

				<th class="th_ep_date">
					<?= $episode->date_fr(); ?>
				</th>

				<?php
				if(!isset($episode->tv_maze)) { ?>
					<th class="th_icon_eye">
						<span class="coche_decoche">
							<?php add_delete_ep($episode->id_ep(), 'eye_coche', $episode->id_series()); ?>
						</span>
					</th> <?php
				} else { ?>
					<th class="th_icon_eye">
						<span class="coche_decoche">
						<?php add_delete_ep($episode->id_ep(), 'eye_decoche', $episode->id_series()); ?>
						</span>
					</th> <?php
				} ?>
		</tr> <?php
		}
		

		$count_ep = count($count_nbr_ep);
		if($count_ep > 10) {
			echo '<div class="plus_ep">';
				echo '<a href="">Plus d\'épisodes</a>';
				echo '<span class="nb_ep hide">'.$count_ep.'</span>';
				echo '<span class="season hide">'.$all_ep_from_season.'</span>';
			echo '</div>';
		}
?>