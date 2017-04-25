<div class="container">
	<div class="backend_content">


	<?php //si pas de personnages pour la série
	if(isset($error)) {
		echo '<div class="error">
				<p>Mauvais identifiant TV Maze ou aucun personnage répertorié.</p>
			  </div>';
		
	} else { 
		donnees_cachees($id_series); ?>

		<div class="admin_characters_box">
			<div class="row">
			<?php
			foreach($tv_maze as $maze) {

				echo '<div class="col-md-3 col-sm-6">';
						$maze_char = $maze['character'];
						$character_maze_id = $maze_char['id'];

						if(in_array($character_maze_id, $id_tv_maze)) {
							echo '<div class="single_characters char_added">';
						} else {
							echo '<div class="single_characters">';
						}

							$src_img = $maze_char['image']['medium'];
							if(!empty($maze_char['image'])) {
								echo '<img src="'.$src_img.'" alt="img_character">';
							} else {
								echo '<img src="'.dossier_img('show/characters/00.jpg').'" alt="no_img">';
								} ?>

							<div class="add_char_form">
								<?php 
								//si le personnage n'est pas déjà ajouté, on affiche le bouton 'ajouter'
								if(!in_array($character_maze_id, $id_tv_maze)) {
									add_update_characters_form('', $maze_char['id'], $maze_char['name'], $maze['person']['name'], $num_img, $id_series, 'Ajouter');
								} else {
									add_update_characters_form('', $maze_char['id'], $maze_char['name'], $maze['person']['name'], $num_img, $id_series);
								} ?>
							</div>
						</div>
				</div>
			<?php
			}

			//FORM pour ajouter un personnage non répertorié sur tv_maze
				echo '<div class="col-md-3 col-sm-6">';
					echo '<div class="single_characters">';
							echo '<div class="add_char_form">';
									echo '<img src="'.dossier_img('show/characters/00.jpg').'" alt="no_img">';
									add_update_characters_form('', 0, '','', $num_img, $id_series, 'Ajouter');
								echo '</div>';
					echo '</div>';
				echo '</div>';
			?>
			</div>
		</div> <!-- end characters_box-->
		<?php
		} ?>

		<!--boutons menu-->
		<div class="box_btn_lien btn_show_form">
			<div class="row">
				<div class="col-sm-4">
					<a href="<?= base_url('admin/characters/'.$id_series.'')?>" class="btn btn-success btn_lien">Modifier les personnages</a>
				</div>

				<div class="col-sm-4">
					<?php 
					//lien retour sur la fiche
					btn_lien_series($id_series, 'btn btn_lien btn-primary', 'admin/show', 'Modifier la série'); 
				echo '</div>';

				echo '<div class="col-sm-4">';
					//lien retour page modifications
					btn_lien_series($id_series, 'btn btn-info btn_lien', 'series/show', 'Retour sur la fiche série'); ?>
				</div>
			</div>
		</div><!-- end boutons menu-->
		

	</div><!--end backend_content-->
</div><!--end container-->