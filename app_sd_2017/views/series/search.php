<div class="container">
	<div class="frontend_content">
		<?php
		if(!empty($result)) {
			$count = count($result);

			echo '<div class="search_count">
					<h5>';
						if($count == 1) {
							echo '<span class="count chevron_orange">'.$count.'</span> série correspondante';
						} else {
							echo '<span class="count chevron_orange">'.$count.'</span> séries correspondantes';
						}
			echo '</h5>
				</div>'; ?>
					

			<div class="listing_box">	
				<?php		
				foreach($result as $show) { 
					find_folder($show->id()); ?>
						<div class="single_show">
							<?php get_file_if_exist('poster_min', $show->id(), $show->img()); ?>
							<div class="single_name_listing">
								<?php echo $show->name(); ?>
							</div>
						</div> <?php
					} ?>
			</div><!--end listing_box-->

		<?php
		} else { ?>
			<div class="retour">
				<img src="<?= dossier_img('icons/emoticon/07.png'); ?>" alt="not_found">
				<p>Aucune série trouvée</p>

				<a href="<?= base_url('series/listing'); ?>" class="btn btn-info btn_lien">Toutes les séries</a>

				<?php
				if($user->admin() == 'admin') { ?>
					<a href="<?= base_url('admin/show'); ?>" class="btn btn_lien btn-warning">Ajouter une série</a> <?php
				} ?>
			</div>
		<?php
		} ?>
	</div><!--end frontend_content-->
</div>