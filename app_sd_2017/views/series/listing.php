<div class="container">
	<div class="frontend_content">
		<div class="listing_box">
			<?php
			foreach($all_shows as $show) { 
				find_folder($show->id()); ?>
					<div class="single_show">
						<?php get_file_if_exist('poster_min', $show->id(), $show->img()); ?>
						<div class="single_name_listing">
							<?php echo $show->name(); ?>
						</div>
					</div> <?php
				} ?>
		</div><!--end listing_box-->

		<?php //pagination
		echo '<div class="pagination">';
			echo $this->pagination->create_links(); 
		echo '</div>';
		?>
	</div><!--end frontend_Content-->
</div>