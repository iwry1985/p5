<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//trouver le bon folder pour les img 
//--------------------------------------------------------------
function find_folder($img) {
	$folder = substr($img, 0, 1);
	return $folder;
}

//ajoute un 0 avant 1 chiffre à un nombre
function double_unit($number) {
	if($number < 10) {
	    $number = sprintf( "%02d", $number);
	}
	return $number;
}

//-------------------------------------------------------------

//charge les classes d'obj 
function loadEntity($array) {
	foreach($array as $entity) {
		include(ENTITY_DIR  .$entity.'.php');
	}
}

//----------------------------------------------------------------
//barre de progression de visionnage
//--------------------------------------------------------------------
function progress($nb_ep_vus, $nb_ep_total, $statut) {  

	if($statut == '4') {
		$nb_ep_vus = $nb_ep_total;
	}

	$sur100 = $nb_ep_total/100;
	$vus = $nb_ep_vus/$sur100;	
	$restant = $nb_ep_total - $nb_ep_vus;
	?>

	
	<div class="progress">
		<div id="id_progress" class="progress-bar" role="progressbar" aria-valuenow="<?= $nb_ep_vus; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $vus; ?>%">
		</div>
	</div>
	<span class="progress_txt">
	<?php
	if($restant == 0 && $statut == '4') {
			echo 'Série terminée';
		} elseif($restant == 0) {
		 	echo 'A jour !';
		} elseif($restant == 1) {
			echo '<span class="nb_vus">1</span> épisode à voir';
		} else {
			echo '<span class="nb_vus">'.$restant.'</span> épisodes à voir';
		}
	echo '</span>';
}
//----------------------------------------------------------------------


//--------------------------------------------------------------------
// input cachés nécessaires pour jquery
function donnees_cachees($id_series) { ?>
	<form action="" method="">
		<input type="hidden" value="<?= $id_series; ?>" class="id_show">
		<input type="hidden" value="<?= base_url(); ?>" class="base_url">
	</form>
<?php
}
//-------------------------------------------------------------------

//compte séries profil/home
function count_shows_box($user, $classe) { ?>
	<div class="count_shows_box <?=$classe?>" >
		<div class="count_seen_shows">
			<p class="bold"><span class="count_shows"><?php
				if($user->count_seen_shows() == 0) {
					echo 'Aucune série regardée';
				} elseif($user->count_seen_shows() == 1) {
					echo '<span class="count">'.$user->count_seen_shows().'</span> série regardée';
				} else {
					echo '<span class="count">'.$user->count_seen_shows(). '</span> séries regardées';
				} ?>
			</span></p>
			
			<div class="all_shows_count">
				<p><span class="font_awesome chevron">&#xf138;</span>
				<span class="count_shows"><span class="count"><?= $user->shows_running().'</span> en cours'; ?></span></p>
				
				<p><span class="font_awesome chevron">&#xf138;</span>
				<span class="count_shows"><span class="count"><?= $user->shows_toCatch().'</span> à rattraper'; ?></span></p>

				<p><span class="font_awesome chevron">&#xf138;</span>
				<span class="count_shows">
				<?php 
				if($user->shows_ended() <= 1) {
					echo '<span class="count">'.$user->shows_ended().'</span> terminée';
				} else {
					echo '<span class="count">'.$user->shows_ended().'</span> terminées';
				}
				echo '</span>';
				
				echo '<p><span class="font_awesome chevron">&#xf138;</span>';
				echo '<span class="count_shows">';
				if($user->shows_trashed() <= 1) {
					echo '<span class="count">'.$user->shows_trashed().'</span> abandonnée';
				} else {
					echo '<span class="count">'.$user->shows_trashed().'</span> abandonnées';
				} ?>
				</span></p>
			</div>


		</div><!--end count_seen_shows-->

		<div class="shows_tobegin">
			<p><span class="count_shows bold"><?php
				if($user->shows_toBegin() == 0) {
					echo 'Aucune série à commencer';
				} elseif($user->shows_toBegin() == 1) {
					echo '<span class="count">'.'1 </span> série à commencer';
				} else {
					echo '<span class="count">'.$user->shows_toBegin().'</span> séries à commencer';
				} ?>
			</span></p>
		</div>
	</div><!--end count_shows_box-->
<?php
}
//--------------------------------------------------------------------------------------------------
//FILE_EXISTS 
function get_file_if_exist($type, $id_series, $img) {
	$folder = find_folder($img);
			
	$url_poster = 'web/img/show/'.$type.'/'.$folder.'/'.$img.'.jpg';

			echo '<a href="'.base_url('series/show/'.$id_series).'">';
			if(file_exists($url_poster)) {
				echo '<img src="'.folder_img_url($type, $folder, $img).'" alt="'.$type.'">';
			} else {
				echo '<img src="'.dossier_img('/show/'.$type.'/00.jpg').'" alt="'.$type.'">';
			} 
		echo '</a>';
}


//-------------------------------------------------------------------------------
function mois_in_french($num) {
	$num = htmlspecialchars($num);
	$months = [];

	$months[1] = array('number' => 1, 'french' => 'Janvier');
	$months[2] = array('number' => 2, 'french' => 'Février');
	$months[3] = array('number' => 3, 'french' => 'Mars');
	$months[4] = array('number' => 4, 'french' => 'Avril');
	$months[5] = array('number' => 5, 'french' => 'Mai');
	$months[6] = array('number' => 6, 'french' => 'Juin');
	$months[7] = array('number' => 7, 'french' => 'Juillet');
	$months[8] = array('number' => 8, 'french' => 'Août');
	$months[9] = array('number' => 9, 'french' => 'Septembre');
	$months[10] = array('number' => 10, 'french' => 'Octobre');
	$months[11] = array('number' => 11, 'french' => 'Novembre');
	$months[12] = array('number' => 12, 'french' => 'Décembre');

	$mois = [];
	$m = 1;

	for($m; $m <= $num; $m++) {
		$mois[] = $months[$m];
	}

	return $mois;
}

//----------------------------------------------------------------------------------------
function get_base_url_val() { ?>
	<form>
		<input type="hidden" class="base_url" value="<?= base_url() ?>">
	</form>
<?php
}
//--------------------------------------------------------------------------

function afficher_poster_et_decompte_series($show) {
	$folder = find_folder($show->img());
				
	$url_poster = 'web/img/show/poster_min/'.$folder.'/'.$show->img().'.jpg';

	echo '<div class="single_weekly single_poster">';
		echo '<a href="'.base_url('series/show/'.$show->id()).'">';
			if(file_exists($url_poster)) {
				echo '<img src="'.folder_img_url('poster_min', $folder, $show->img()).'">';
			} else {
				echo '<img src="'.dossier_img('/show/poster_min/00.jpg').'" alt="poster">';
			} 
		echo '</a>';
						
		//count ép vus sur un mois
		echo '<div class="count_most_watched">
			<span class="count_profil ">'.$show->count_ep;
				if($show->count_ep == 1) {
					echo '</span> épisode vu';
				} else {
					echo '</span> épisodes vus';
				}
		echo '</div>';
	echo '</div>';
}

//--------------------------------------------------------------------------------

function afficher_poster_series($show) {
	$folder = find_folder($show->img());
				
	$url_poster = 'web/img/show/poster_min/'.$folder.'/'.$show->img().'.jpg';
	
	echo '<div class="single_weekly single_poster">';
		echo '<a href="'.base_url('series/show/'.$show->id()).'">';
			if(file_exists($url_poster)) {
				echo '<img src="'.folder_img_url('poster_min', $folder, $show->img()).'">';
			} else {
				echo '<img src="'.dossier_img('/show/poster_min/00.jpg').'" alt="poster">';
			} 
		echo '</a>';
	echo '</div>';
}

//-------------------------------------------------------------------------------
function afficher_count_bilan($total_ep_seen, $count_began, $count_ended,$count_added, $count_coeur) { 

	echo '<div class="bilan_single_count">';
		//épisodes vus
		if($total_ep_seen == 0) {
			echo '<span class="glyphicon glyphicon-eye-close grey"></span><p> Aucun épisode vu</p>';
		} elseif($total_ep_seen == 1) {
			echo '<span class="glyphicon glyphicon-eye-open"></span><p> '.$total_ep_seen.' épisode vu</p>';
		} else {
			echo '<span class="glyphicon glyphicon-eye-open"></span><p> '.$total_ep_seen.' épisodes vus</p>';
		}
	echo '</div>';


	echo '<div class="bilan_single_count">';
		//séries commencées
		if($count_began == 0) {
			echo '<span class="glyphicon glyphicon-play grey"></span><p> Aucune série commencée</p>';
		} elseif($count_began == 1) {
			echo '<span class="glyphicon glyphicon-play green"></span><p> '.$count_began.' série commencée</p>';
		} else {
			echo '<span class="glyphicon glyphicon-play green"></span><p> '.$count_began.' séries commencées</p>';
		}
	echo '</div>';

	//séries terminées
	echo '<div class="bilan_single_count">';
		if($count_ended == 0) {
			echo '<span class="glyphicon glyphicon-remove grey"></span><p> Aucune série terminée</p>';
		} elseif($count_ended == 1) {
			echo '<span class="glyphicon glyphicon-ok green"></span><p> '.$count_ended.' série terminée</p>';
		} else {
			echo '<span class="glyphicon glyphicon-ok green"></span><p> '.$count_ended.' séries terminées</p>';
		} 
	echo '</div>';


	echo '<div class="bilan_single_count">';
		//séries ajoutées
		if($count_added == 0) {
			echo '<span class="glyphicon glyphicon-remove grey"></span><p> Aucune série ajoutée</p>';
		} elseif($count_added == 1) {
			echo '<span class="glyphicon glyphicon-plus green"></span><p> '.$count_added.' série ajoutée</p>';
		} else {
			echo '<span class="glyphicon glyphicon-plus green"></span><p> '.$count_added.' séries ajoutées</p>';
		}
	echo '</div>';

	echo '<div class="bilan_single_count">';
		//coup de coeur
		if($count_coeur == 0) {
			echo '<span class="glyphicon glyphicon-heart grey"></span><p> Aucun coup de coeur</p>';
		} elseif($count_coeur == 1) {
			echo '<span class="glyphicon glyphicon-heart red"></span><p> '.$count_coeur.' coup de coeur</p>';
		} else {
			echo '<span class="glyphicon glyphicon-heart red"></span><p> '.$count_coeur.' coups de coeur</p>';
		} 
	echo '</div>';
}
//----------------------------------------------------------------------------------------------------------------------
function random_form($class, $count, $img, $name) {
	echo '<div class="section_random">
		<div class="accroche_random">
			<span class="accroche_bigger kalambold">Tu n\'arrives pas à décider quelle série regarder ?</span> <br/>
					Sélectionne le nombre de choix que tu désires et clique sur le dé !
		</div>
			
		<form action="" method="">

			
		<select name="'.$class.'" class="btn btn-default '.$class.'">';
			$i=1;

			//Pour que le select n'affiche pas plus d'options que d'épisodes à voir
			if($count < 6) {
				$rand = $count;
			} else {
				$rand = 6;
			}

			for($i; $i <= $rand; $i++) {
				if($i == 1) {
					echo '<option value="'.$i.'">'.$i.' série</option>';
				} else {
					echo '<option value="'.$i.'">'.$i.' séries</option>';
				}
			}

			echo '</select>

			<input type="image" src="'.dossier_img('icons/'.$img.'.png').'" name="'.$name.'" id="'.$name .'">

			<input type="hidden" class="base_url" value="'.base_url().'">
			</form>
		</div><!--end section_random-->';
}
//-------------------------------------------------------------------------
function stats_status_users($stats, $type) {

	if(!empty($stats)) {
		//statut
		if($type == 'statut') { ?>
		<div class="row_stats">
			<?php
			foreach($stats as $stat) {
				if(array_key_exists('statut', $stat)) { ?>
				<div class="stats_">
					<div class="stats_icon">
						<img src="<?= img_url($stat['icon']); ?>" alt="icon_statut" data-toggle="tooltip" title="<?= $stat['statut']; ?>">
					</div>
					<div class="stats_count">
						<?= $stat['count']; ?>
					</div>
				</div>
			<?php
			}
		} ?>
		</div> <?php

		//note
		} else { ?>
		<div class="row_stats">
			<?php
			foreach($stats as $stat) {
				if(array_key_exists('note', $stat)) {

					if($stat['id'] != '1' && $stat['id'] != '2') { ?>
					<div class="stats_">
						<div class="stats_icon">
							<img src="<?= img_url($stat['icon']); ?>" alt="icon_note"  data-toggle="tooltip" title="<?= $stat['note']; ?>">
						</div><!--end stats_icon-->
						<div class="stats_count">
							<?= $stat['count']; ?>
						</div><!--end stats_count-->
				</div><!--end stats_icon-->
					<?php
					} 
			}
		} ?>
		</div><!--end row_stats-->
	<?php
		}
	}
}