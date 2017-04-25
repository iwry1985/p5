<div class="container">
	<div class="frontend_content">
		<?php
		if(isset($count_membres)) { ?>
			<div class="search_count">
				<h5>Stats</h5>
			</div>
			<div class="listing_box">
				<?php
				echo $count_membres.' membres'; ?>
			</div> <?php
		}

		if(isset($show_ended) && !empty($show_ended)) { ?>
			<div class="search_count">
				<h5>Changer le statut/Séries terminées</h5>
			</div>
				<div class="listing_box">	
					<?php		
					foreach($show_ended as $show) { 
						find_folder($show->id()); ?>
							<div class="single_show">
								<?php get_file_if_exist('poster_min', $show->id(), $show->img()); ?>
								<div class="single_name_listing">
									<?php echo $show->name(); ?>
								</div>
							</div>
					<?php
					} ?>
			</div><!--end listing_box-->
		<?php
		} else { 
			echo '<div class="listing_box">';
			echo '<a href="'.base_url('admin/get_shows_that_ended').'" class="btn btn-default">Vérifier les séries terminées</a>';
			echo '</div>';
		}


		
		if(isset($new_show_started) && !empty($new_show_started)) { ?>
		<div class="search_count">
			<h5>Changer le statut/Séries commencées</h5>
		</div>
			<div class="listing_box">	
				<?php		
				foreach($new_show_started as $show) { 
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
		}

		if(isset($wrong_seasons) && !empty($wrong_seasons)) { ?>
		<div class="search_count">
			<h5>Changer la saison</h5>
		</div>
			<div class="listing_box">	
				<?php	
				foreach($wrong_seasons as $show) { 
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
		} ?>
		
	</div>
</div>