<div class="container">
	<div class="frontend_content no_padding">
		<?php include_once('_profil_header.php'); ?>



		<?php //-------------------------------------------------------------------------------------------
		if(!empty($begin)) { ?>
			<div class="waitinglist_box">
				<?php $count_show = count($begin); ?>
				<h5><?= $count_show ?> séries <span class="font_awesome chevron">&#xf138;</span> <?= $count_total_ep ?> épisodes à voir !</h5>


				<?php
				if($count_show > 2) {
					random_form('random_beginlist', $count_show, 'die4', 'die_random_beginlist'); ?>

					<!--Résultats alétoires-->
					<div id="random_selec"></div> <?php
				} ?>

				<div class="wait_box">
					<?php
					foreach($begin as $show) { ?>
						<div class="single_wait">
							<?php get_file_if_exist('poster_min', $show['id'], $show['img']); ?>
							<div class="count_begin">
								<span class="count"><?= $show['count_ep'];?></span>
								<?php
								if($show['count_ep'] == 1) {
									echo ' épisode à voir';
								} else {
									echo ' épisodes à voir';
								} ?>
							</div>
						</div>
					<?php
					} ?>
				</div>
			</div>
		<?php
		} else { ?>
		<div class="clean_box">
			<img src="<?= dossier_img('icons/emoticon/relax.png'); ?>" alt="relax_icon">
			<p>Ta beginList est vide !</p>
		</div>
	<?php
	} ?>

	</div>
</div>