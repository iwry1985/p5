<div class="container">
	<div class="frontend_content">
		<div class="search_count">
			<h5>Dernières séries ajoutées</h5>
		</div>


		<div class="listing_box">	
			<?php		
			foreach($last_added as $show) { 
				find_folder($show->id()); ?>
					<div class="single_show">
						<?php get_file_if_exist('poster_min', $show->id(), $show->img()); ?>
						<div class="single_name_listing">
							<?php echo $show->name(); ?>
						</div>
					</div> <?php
				} ?>
		</div><!--end listing_box-->


	</div><!--end frontend_content-->
</div>