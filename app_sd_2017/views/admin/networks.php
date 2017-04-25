<div class="container">
	<div class="backend_content">
	<?php
	if(!empty($_SESSION['flash'])) {
		$user->getFlash();
	} ?>
		<div class="networks_box">
			<div class="row">
				<?php
				foreach($networks as $network) { ?>
				<div class="single_network" id="net_<?= $network->id() ?>">
					<div class="col-sm-2">
						<?php
						$lien = 'web/'.$network->network();
						if(file_exists($lien)) { ?>
							<img src="<?= img_url($network->network())?>" alt="network_logo">
						<?php
						} else { ?>
							<img src="<?= dossier_img('networks/00.png'); ?>" alt="network_logo">
						<?php
						} ?>

						<form action="" method="post" class="network_form">
							<label for="network_name">Nom</label>
							<input type="text" name="network_name" class="network_name form-control" value="<?= $network->network_name(); ?>">

							<label for="network_country">Pays</label>
							<input type="text" value=<?= $network->country(); ?> class="form-control network_country" name="network_country">

							<input type="submit" value="Modifier" name="update_network" class="update_network btn btn-primary char_submit network_submit">
						</form>
						<a href="" class="btn btn-danger delete_char_btn">Supprimer</a>
						<?php
						//form upload_img
						load_img_form('IMG_network', $network->id(), '', $network->network()); ?>
					</div>
				</div><!--end single_network-->
				<?php
				}
				?>
			</div><!--end row-->
		</div><!--end network_box-->
	</div><!--end backend_content-->
</div><!--end container-->