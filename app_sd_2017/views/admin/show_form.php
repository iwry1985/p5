<div class="container">
	<div class="backend_content">
		<div class="_form_show">

			<?php
			if(!empty($_SESSION['flash'])) {
				$user->getFlash();
			}
			
			if(isset($show)) { ?>
				<!--poster-->
				<div class="img_show">
					<div class="form_poster">
					<?php
					$poster = 'web/img/show/poster/'.$show->folder().'/'.$show->img().'.jpg';
					$chemin = $show->folder().'/'.$show->img().'.jpg';

					if(file_exists($poster)) { ?>
						<img src="<?= folder_img_url('poster', $show->folder(), $show->img()) ?>" alt="show_poster"> 

						<form method="post" action="<?= base_url('admin/delete_img') ?>" class="btn_delete_img">
							<input type="submit" value="Supprimer le poster" name="delete_poster" class="btn btn-danger">
							<input type="hidden" value="<?= $chemin ?>" name="chemin">
							<input type="hidden" value="<?= $show->id() ?>" name="id_series">
						</form> <?php
					} else { ?>
						<img src="<?= img_show_url('poster', '00'); ?>" alt="no_poster"> <?php
					} ?>
				</div>
					
				<!-- banner-->
				<div class="form_banner">
					<?php
					$banner = 'web/img/show/banner/'.$show->folder().'/'.$show->img().'.jpg';
					if(file_exists($banner)) { ?>
						<img src="<?= folder_img_url('banner', $show->folder(), $show->img()); ?>" alt="show_banner">

						<form method="post" action="<?= base_url('admin/delete_img') ?>" class="btn_delete_img">
							<input type="submit" value="Supprimer la banner" name="delete_banner" class="btn btn-danger">
							<input type="hidden" value="<?= $chemin ?>" name="chemin">
							<input type="hidden" value="<?= $show->id() ?>" name="id_series">
						</form> <?php
					} else { ?>
						<img src="<?= img_show_url('banner', '00'); ?>" alt="no_poster"> <?php
					} ?>
				</div>
			</div> <?php
			} ?>


			<?php 
			//FORM UPLOAD IMG
			if(isset($show)) {
				echo '<div class="row">';
					//form_upload pour le poster
					echo '<div class="col-sm-6">';
						load_img_form('IMG_poster', $show->id(), $show->folder(), $show->img());
					echo '</div>';

					//form upload pour la banner
					echo '<div class="col-sm-6">';
						load_img_form('IMG_banner', $show->id(), $show->folder(), $show->img());
					echo '</div>';
				echo '</div>';
			} ?>

			

			<!--FORM-->
			<?php if(isset($show)) {
				echo '<form action="'.base_url('admin/executeUpdate').'" method="post">';
			} else {
				echo '<form action="'.base_url('admin/executeAdd_show').'" method="post">';
			} ?>
			

				<!-- nom VO -->
				<div class="row">
					<div class="col-md-12">
						<label for="name">Nom VO</label>
						<input type="text" name="name" class="form-control"  value="<?= isset($show) ? $show->name() : '' ?>" required>
					</div>
				</div>

				<!-- nom VF (si pas, VO) -->
				<div class="row">
					<div class="col-md-12">
						<label for="vf">(si) Nom français (sinon VO)</label>
						<input type="text" name="vf" class="form-control"  value="<?= isset($show) ? $show->VF() : '' ?>" required>
					</div>
				</div>

				
				<div class="row">
					<!-- id tv_maze -->
					<div class="col-xs-6">
						<label for="tv_maze">TV Maze</label>
						<input type="number" min="1" class="form-control" name="tv_maze" value="<?= isset($show) ? $show->tv_maze() : '' ?>" required>
					</div>

					<!--numéro img-->
					<div class="col-xs-6">
						<label for="img">Num img</label>
						<input type="number" class="form-control" name="img" value="<?= isset($show) ? $show->img() : $num_img ?>" required>
					</div>
				</div>


				<!-- synopsis -->
				<div class="row">
					<div class="col-md-12">
						<label for="synopsis">Synopsis</label>
						<textarea class="form-control" name="synopsis" rows="10" cols="50"><?= isset($show) ? $show->synopsis() : ''; ?></textarea>
					</div>
				</div>

				<!--Producer-->
				<div class="row">
					<div class="col-md-12">
						<label for="producer">Créée par</label>
						<input type="text" value="<?= isset($show) ? $show->producer() : '' ?>" class="form-control" name="producer">
					</div>
				</div>
				
				<div class="row">
					<!-- begin_date -->
					<div class="col-md-3 col-sm-6">
						<label for="begin_date">Date début</label>
						<input type="number" class="form-control" name="begin_date" value="<?= isset($show) ? $show->begin_date() : '' ?>">
					</div>

					<!-- end_date -->
					<div class="col-md-3 col-sm-6">
						<label for="end_date">(Si) Date de fin (sinon 0)</label>
						<input type="number" class="form-control" name="end_date" value="<?= isset($show) ? $show->end_date() : '' ?>">
					</div>

					<!--Durée des épisodes-->
					<div class="col-md-3 col-xs-6">
						<label for="runtime">Durée</label>
						<input type="number" class="form-control" value="<?= isset($show) ? $show->runtime() : '' ?>" name="runtime">
					</div>

					<!-- nombre de saisons-->
					<div class="col-md-3 col-xs-6">
						<label for="seasons">Saison(s)</label>
						<input type="number" value="<?= isset($show) ? $show->seasons() : '' ?>" class="form-control" name="seasons">
					</div>
				</div>

				
				<div class="row">
					<!-- origine -->
					<div class="col-sm-4">
						<label for="origine">Origine</label>
						<select class="form-control" name="origine">
							<?php foreach($org as $country) {
								if(isset($show) && $show->country_name() == $country['country']) {
									echo '<option value="'.$country['id'].'" selected>'.$show->country_name().'</option>';
								} else {
									echo '<option value="'.$country['id'].'">'.$country['country'].'</option>';
								}
							} ?>
						</select>
					</div>

					<!--networks-->
					<div class="col-sm-4">
						<label for="network">Chaîne</label>
						<select class="form-control" name="network">
							<?php foreach($org as $org_net) {
									echo '<optgroup label="'.$org_net->country().'">';

									foreach($networks as $net) {
										if($net->country() == $org_net->country()) {
											if(isset($show) && $show->network_id() == $net->id()) {
												echo '<option value="'.$show->network_id().'" selected>'.$show->network_name().'</option>';
											}  else {
												echo '<option value="'.$net->id().'">'.$net->network_name().'</option>';
											}
										}
									}

									echo '</optgroup>';
							} ?>
						</select>
					</div>

					<!-- statut (==etat)-->
					<div class="col-sm-4">
						<label for="etat">Statut</label>
						<select class="form-control" name="etat">
							<?php foreach($etats as $etat) {
								if(isset($show) && $show->etat_id() == $etat['id']) {
									echo '<option value="'.$show->etat_id().'" selected>'.$show->etat().'</option>';
								} else {
									echo '<option value="'.$etat['id'].'">'.$etat['etat'].'</option>';
								}
							} ?>
						</select>
					</div>
				</div>

				<div class="row">
					<!-- genre -->
					<div class="col-sm-4">
						<label for="genre">Genre</label>
						<select name="genre" class="form-control">
							<?php foreach ($genres as $genre) {
								if(isset($show) && $show->genre() == $genre['genre']) {
									echo '<option value="'.$genre['id'].'" selected>'.$genre['genre'].'</option>';
								} else {
									echo '<option value="'.$genre['id'].'">'.$genre['genre'].'</option>';
								}
							} ?>
						</select>
					</div>


					<!-- RANDOM (apparait en mode random ou pas. Oui (0) par défaut -->
					<div class="col-sm-4">
						<label for="random">En random</label>
						<select name="random" class="form-control">
							<?php if(isset($show) && $show->random() == 1) {
								echo '<option value="0">Oui</option>
									  <option value="1" selected>Non</option>';
							} else {
								echo '<option value="0" selected>Oui</option>
									  <option value="1">Non</option>';
							} ?>
						</select>
					</div>


					<!-- statut renouvellement (seulement pour les séries en cours -->
					<?php
					if(isset($show) && $show->etat_id() == '1') { ?>
					<div class="col-sm-4">
						<label for="renew">Renouvelée ?</label>
						<select name="renew" class="form-control">
							<?php if(isset($show) && $show->renew() == 'renew') {
								echo '<option value="renew" selected>Oui</option>
									  <option value="cancel">Non</option>
									  <option value="NC">Inconnu</option>';
							} elseif(isset($show) && $show->renew() == 'cancel') {
								echo '<option value="renew">Oui</option>
									  <option value="cancel" selected>Non</option>
									  <option value="NC">Inconnu</option>';
							} else {
								echo '<option value="renew">Oui</option>
									  <option value="cancel">Non</option>
									  <option value="NC" selected>Inconnu</option>';
							} ?>
						</select> 
					</div><?php
					} ?>
				</div>


				<div class="btn_show_form">
				<?php
					//btn submit
					if(!isset($show)) {
						echo '<div class="row">
								<div class="col-sm-6">
									<input type="submit" value="Ajouter la série" class="btn btn-success submit_form_show" name="add_show">
									<input type="hidden" value="'.$num_img.'" name="id_series">
								</div>
							</div>';


					} else {
						echo '<div class="row">
								<div class="col-sm-6">
									<input type="submit" value="Confirmer modifications" class="btn btn-warning submit_form_show" name="update_show">
									<input type="hidden" value="'.$show->id().'" name="id_series">
								</div>
								<div class="col-sm-6">';
									btn_lien_series($show->id(), 'btn btn-info btn_lien', 'series/show', 'Retour sur la fiche série');
							echo '</div>
							</div>';

						echo '<div class="row">
								<div class="col-sm-6">';
								btn_lien_series($show->id(), 'btn btn_lien btn-success', 'admin/characters_tvmaze', 'Ajouter des personnages');
							echo '</div>

								<div class="col-sm-6">';
								btn_lien_series($show->id(), 'btn btn_lien btn-warning', 'Scriptepisodes090519852010/add_all_episodes_from_show', 'Ajouter les épisodes');
							echo '</div>
						</div>';
					} ?>
				</div>
			</form>

		<?php
		if(isset($show)) { ?>
		<form action="" method="post" id="delete_show" >
			<input type="submit" class="btn btn-danger submit_update" name="btn_delete_show" value="Supprimer la série">
			<input type="hidden" class="base_url" value="<?= base_url(); ?>">
			<input type="hidden" class="id_series" value="<?= $show->id(); ?>">
		</form> <?php
		} ?>
		</div><!--end _form_show-->
	</div><!--end backend_content-->
</div><!--end container-->