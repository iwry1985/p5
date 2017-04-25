<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//SELECT FORM USER
function select_form_user($name, $data, $watch, $id_show, $show_status = '') { ?>
	<form action = "" method="post">
		<select name="<?= $name; ?>" class="btn btn-default <?= $name; ?>">
		<?php foreach($data as $donnee) {
			//si la série est différente de 'terminée'
			if($name == 'watch_status' && $show_status != 'Terminée') { 

				//si elle est commandée, on affiche seulement 'à commencer'
				if($show_status == 'Commandée') {
					if($donnee->id() == '1') {
						echo '<option value ="'.$donnee->id().'" selected>'.$donnee->libelle().'</option>';
					}
				//si la série est 'en cours', on affiche pas 'terminée'
				} else {
					if($donnee->id() != '4') {
						if($donnee->id() == $watch) {
							echo '<option value ="'.$donnee->id().'" selected>'.$donnee->libelle().'</option>';
							} else {
							echo '<option value ="'.$donnee->id().'">'.$donnee->libelle().'</option>';
							}
					}
				} //---------------------------------------------------------
			//si la série est en cours + NOTES_USER
			} else {
				if($donnee->id() == $watch) {
				echo '<option value ="'.$donnee->id().'" selected>'.$donnee->libelle().'</option>';
				} else {
				echo '<option value ="'.$donnee->id().'">'.$donnee->libelle().'</option>';
				}	
			} 
		} ?>
		</select>
		<input type="hidden" class="etat_show" value="<?= $show_status ?>">
	</form>
<?php
}
//------------------------------------------------


//---------------------------------------------------------------------------------------------------------------------------------------------
//FORM SELECT SI UNE SERIE EST AJOUTEE
function status_form($data, $statut, $id_show, $show_status) {  
	if(empty($statut)) { ?>
		<div class="status_icon">
			<img class="img_icon" src="<?= dossier_img('icons/eye.png'); ?>" alt="statut_icon" data-toggle="tooltip" data-placement="bottom" title="A commencer">
		</div><!--end status_icon-->
	<?php
	}
	//icone
	foreach($data as $stat) {
		if($stat['id'] == $statut) { ?>
			<div class="status_icon">
				<img class="img_icon" src="<?= img_url($stat->icon()); ?>" alt="statut_icon" data-toggle="tooltip" data-placement="bottom" title="<?= $stat->libelle(); ?>">
			</div><!--end status_icon-->
		<?php
		}
	} ?>
	
	
	<!--select + bouton delete-->
	<div class="status_form">
		<!--form statut select // FONCTION SELECT_FORM_USER-->
		<?php select_form_user('watch_status', $data, $statut, $id_show, $show_status); ?>

		<!--form bouton delete-->
		<form action="" method="post" class="form_del_show">
			<input type="submit" class="btn btn-danger" name="delete_show" id="serie_delShow_id" value="Supprimer la série">
		</form>
	</div>
<?php
}
//------------------------------------------------------------------------------------------------------------------------------------------------


//------------------------------------------------------------------------------
//CARRE COLOR STATUT
function status_color($id_status) {
	switch($id_status) {
		case 1:
		echo '<div id="carre" class="bck_commencer"></div>';
		break;

		case 2:
		echo '<div id="carre" class="bck_rattraper"></div>';
		break;

		case 3:
		echo '<div id="carre" class="bck_enCours"></div>';
		break;

		case 4:
		echo '<div id="carre" class="bck_terminer"></div>';
		break;

		case 5:
		echo '<div id="carre" class="bck_abandonner"></div>';
		break;

		case 6:
		echo '<div id="carre" class="bck_notInterested"></div>';
		break;

		default:
		echo '<div id="carre" class="bck_commencer"></div>';
	}
}

//---------------------------------------------------------------------------------------------------------------------------------------------------------
 

//--------------------------------------------------------
//BOUTON ADD SHOW
function add_show_button($id_show) { ?>
	<form action="" method="post" class="form_add_show">
		<input type="submit" class="btn btn-success" name="serie_addShow" id="serie_addShow" value="Ajouter la série" />
	</form>
<?php
}
//--------------------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------
//COCHE/DECOCHE UN EPISODE
function add_delete_ep($id_ep, $img, $id_show) { ?>
	<form action="" method="post" class="ep_button">
		<input type="hidden" class="id_ep" value="<?= $id_ep;?>"/>
		<input type="hidden" class="id_show" value="<?= $id_show;?>"/>
		<input type="hidden" class="url_icon" value="<?= dossier_img('icons/'); ?>">
		<input type="image" src="<?php echo dossier_img('icons/'.$img.'.png')?>" class="eye_button" value="<?= $img; ?>"/>
	</form>
<?php
}
//--------------------------------------------------------------------------------------------------------------------------------------------------


//-----------------------------------------------------------------------------
//FORM AVIS_USER
function note_user($watch_note, $notes, $id_show) {

	echo '<div class="watch_note">';
	select_form_user('select_user_note', $notes, $watch_note, $id_show);

	foreach($notes as $note_emo) {
		if($note_emo->id() == $watch_note) {

			echo '<div class="watch_note_emo">
					<img src="'.img_url($note_emo->icon()).'" alt="emoticon" data-toggle="tooltip" data-placement="bottom" title="'.$note_emo->libelle().'">
				</div>';
		}
	}
	echo '</div>';
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//----------------------------------------------------------------------
//FORM AJOUTER UNE SERIE (!PAS SUR LA FICHE!)
function add_this_show($id_series) { ?>
	<form action="" post="method" class="add_this_sugg form_add_show">
		<input type="submit" value="Ajouter la série">
	</form>
<?php
}
//-----------------------------------------------------

//FORM POUR AJOUTER TOUS LES EP NONS VUS D'UNE SAISON EN UNE FOIS
function add_all_unseen_ep_from_season($img, $season, $title, $type, $nb_ep, $page) { ?>
	<form action="" method="post" class="btn_tout_cocher" data-toggle="tooltip" title="<?= $title ?>">
		<input type="hidden" class="url_icon" value="<?= dossier_img('icons/'); ?>">
		<input type="hidden" class="coche_season" value="<?= $season ?>">
		<input type="hidden" class="coche_type" value="<?= $type ?>">
		<input type="hidden" class="nb_ep" value="<?= $nb_ep ?>">
		<input type="hidden" class="page" value="<?= $page ?>">

		<input type="image" src="<?php echo dossier_img('icons/'.$img.'.png')?>" class="eye_button btn_tout_cocher_eye btn btn_lien" value="<?= $img; ?>">
	</form>
<?php
}