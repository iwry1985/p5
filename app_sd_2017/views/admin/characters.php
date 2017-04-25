<div class="container">
	<div class="backend_content">


	<?php //si pas de personnages pour la série
	if(isset($error)) {
		echo '<div class="error">
				<p>Aucun personnage de répertorié pour le moment</p>
			  </div>';
		
	} else { 
		donnees_cachees($id_series); ?>
		

		<div class="admin_characters_box">
			<div class="row">
			<?php
			if(!empty($_SESSION['flash'])) {
				$user->getFlash();
			}
			foreach($characters as $character) {
				$id_series = $character->id_series();

				echo '<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="single_characters single_char_add" id="char_'.$character->img().'">';
							
							$url_img = characters_img_url($character->folder(), $character->id_series(), $character->img());
							$char_img = 'web/img/show/characters/'.$character->folder().'/'.$character->id_series().'/'.$character->img().'.jpg';

							if(!file_exists($char_img)) {
							//form upload_img
							load_img_form('IMG_personnage', $character->id_series(), $character->folder(), $character->img());
							} else { ?>
								<form method="post" action="<?= base_url('admin/delete_img') ?>" class="btn_delete_img">
									<input type="submit" value="Supprimer l'image" name="delete_character_img" class="btn btn-danger">
									<input type="hidden" value="<?= $char_img ?>" name="chemin">
									<input type="hidden" value="<?= $character->id() ?>" name="id_char">
									<input type="hidden" value="<?= $character->id_series() ?>" name="id_series">
								</form> <?php
							}


								if(file_exists($char_img)) {
									echo '<img src="'.$url_img.'?'.time().'" alt="img_character">';
								} else {
									echo '<img src="'.dossier_img('show/characters/00.jpg').'" alt="no_img">';
								} ?>
								

								<div class="add_char_form">
									<?php add_update_characters_form($character->id(), $character->id_tv_maze(), $character->name(), $character->actor_name(), $character->img(), $character->id_series(), 'Modifier'); ?>
									<a href="" class="btn btn-danger delete_char_btn">Supprimer</a>
								</div>
							
						</div>
					</div>
					<?php
			} ?>
			
			</div>
		</div> <!-- end characters_box-->
		<?php
		} ?>

		<!--boutons menu-->
		<div class="box_btn_lien btn_show_form">
			<div class="row">
				<div class="col-sm-4">
					<a href="<?= base_url('admin/characters_tvmaze/'.$id_series.'')?>" class="btn btn-success btn_lien">Ajouter des personnages</a>
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