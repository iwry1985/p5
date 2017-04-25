<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function resize_copy_img($file, $percent, $miniature, $user, $id_series) {

	list($width, $height) = getimagesize($file);
	$new_width = $width * $percent;
	$new_height = $height * $percent;

	$new_img = imagecreatetruecolor($new_width, $new_height);
	$src = imagecreatefromjpeg($file);

	imagecopyresampled($new_img, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	imagejpeg($new_img, $miniature);

	$user->setFlash('L\'image et sa miniature ont bien été uploadées. <a href="'.base_url('series/show/'.$id_series.'').'" class="lien_success">Voir la fiche</a>', 'success');
}

//------------------------------------------------------
//fonction pour loader les images côté admin
function load_img($file, $name, $user, $ext, $folder) {
	if(!empty($file)) {
		if($file['error'] == 0) {
			//test extensions
			$ext_up = strtolower(substr(strrchr($file['name'], '.'), 1));
			$allow_ext = array($ext);

			$allow_type = array('image/jpeg', 'image/png');
			$type_up = $file['type'];

			//test extension
			if(in_array($ext_up, $allow_ext)) {

				//test taille
				$img = $file;
				$maxsize = 1000000; //max 1Mo

				if($file['size'] != NULL && $file['size'] < $maxsize) {
					
					//test type
					if(in_array($type_up, $allow_type)) {
						//si pas d'erreurs, on upload
						if(!isset($error)) {
							//on vérifie l'existence du dossier
							if(file_exists($folder)) {
								if(file_exists($name)) {
									//si un dossier existe déjà, on supprime le fichier précédent
									unlink($name);
								}
							} else {
								//sinon, on crée le dossier
								mkdir($folder);
							}

							
							//si tout est ok, l'img est uploadée
							move_uploaded_file($img['tmp_name'], $name);

							return $msg = 'upload_ok';
						}

					//si erreur type
					} else {
						$user->setFlash('Ce type de ficher n\'est pas autorisé', 'danger');
					}
				//si le fichier est plus gros que 1Mo	
				} else {
				$user->setFlash('Le fichier est trop volumineux (max.: 1Mo)','danger');
			}
			//extension non valide
			} else {
				$user->setFlash('L\'extension du fichier n\'est pas valide', 'danger');
			}
		//error
		} else {
			$user->setFlash('L\'image n\'a pas été uploadée', 'danger');
		}
	}
}

//-----------------------------------------------------------
//form pour upload d'image
function load_img_form($name, $id_series, $folder, $img) { ?>
	
	<form method="post" action="<?= base_url('admin/executeUpload_files/'.$name.'') ?>" enctype="multipart/form-data" class="upload_form">
		<label for="<?= $name ?>"><?= $name ?></label>
		<input type="file" class="btn btn-default" name="<?= $name ?>">
		<input type="submit" value="Envoyer" name="<?= $name ?>_submit" class="btn btn-warning">
		<input type="hidden" value="<?= $id_series ?>" name="id_series">
		<input type="hidden" value="<?= $folder ?>" name="num_folder">
		<input type="hidden" value="<?= $img ?>" name="num_img">
	</form>
<?php
}
//---------------------------------------------------------------------------------------------------
//TV_maze
function api_tv_maze($tv_maze, $section = '') {
	$tv_maze = htmlspecialchars($tv_maze);
	$section = htmlspecialchars($section);

	if(isset($tv_maze) && $tv_maze > 0) {
		//on va chercher la série correspond au num id_tv_maze
		if(!empty($section)) {
			$url_tvmaze = 'http://api.tvmaze.com/shows/'.$tv_maze.'/'.$section;
		} else {
			$url_tvmaze = 'http://api.tvmaze.com/shows/'.$tv_maze;
		}
		

		$get_content_tvMaze = file_get_contents($url_tvmaze);
		$data = json_decode($get_content_tvMaze, true);

		return $data;
	}
}
//--------------------------------------------------------------------------------------------------
//FORM pour ajouter et update les personnages
function add_update_characters_form($id = '', $id_tv_maze, $char_name, $actor_name, $img, $id_series, $submit = '') { ?>
	<form action="" method="post">
		<!--id_tv_maze-->
		<label for="id_tv_maze">id_tv_maze</label>
		<input type="number" value="<?= $id_tv_maze ?>" name="id_tv_maze" class="id_tv_maze form-control">	

		<!--nom du personnage-->
		<label for="char_name">Nom du personnage</label>
		<input type="text" value="<?= $char_name ?>" name="char_name" class="char_name form-control">	

		<!--nom de de l'acteur/actrice-->
		<label for="actor_name">Acteur/Actrice</label>
		<input type="text" value="<?= $actor_name ?>" name="actor_name" class="actor_name form-control">

		<input type="hidden" value="<?= $img ?>" class="num_img">
		<input type="hidden" value="<?= $id ?>" class="id_character">

		<?php if(isset($submit) && !empty($submit)) {
			echo '<input type="submit" value="'.$submit.'" class="char_submit btn btn-success">';
		} ?>
	</form>

<?php
}
//---------------------------------------------------------------------
//btn lien de retour sur la fiche série
function btn_lien_series($id_series, $class, $url, $texte) { ?>
	<a class="<?php echo $class ?>" href="<?= base_url($url.'/'.$id_series.'')?>"><?= $texte ?></a>
<?php
}

//--------------------------------------------------------------------------
function delete_img_admin($nom, $chemin, $user) {
	$server = url_server();
	$img = $server.'/img/show/'.$nom.'/'.$chemin;
	$thumb = $server.'/img/show/'.$nom.'_min/'.$chemin;


	if(file_exists($img)) {
		unlink($img);
		
		if(file_exists($thumb)) {
			unlink($thumb);
		}

		$user->setFlash('L\'image a bien été supprimée', 'success');
	} else {
		$user->setFlash('Aucune image', 'danger');
	}
}

//----------------------------------------------------------------
//supprimer des dossiers qui ne sont pas vides
function delete_non_empty_folder($folder) {
	if(is_dir($folder)) {
		$files = scandir($folder);

		foreach($files as $file) {
			if($file != '.' && $file != '..') {
				unlink($folder.'/'.$file);
			}
		}

		rmdir($folder);
	}
}