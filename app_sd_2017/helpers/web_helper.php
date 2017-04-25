<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#### A MODIFIER SI CHANGEMENT D'ADRESSE ##############################
//retourner le dossier IMG avec $_SERVER
if(! function_exists('url_server')) {
	function url_server() {
		return $_SERVER['DOCUMENT_ROOT'].'/seriesdom/web';
	}
}
####################################################################
//----------------------------------------------------------------------------
//url d'une img dont la valeur ne contient pas l'url
if ( ! function_exists('dossier_img')) {
	function dossier_img($url) {
		return base_url('/web/img/'.$url);
	}
}

//url d'une img dont la valeur contient l'url
if ( ! function_exists('img_url')) {
	function img_url($nom) {
		return base_url('/web/'.$nom);
	}
}

//url d'une img dont la valeur contient l'url
if ( ! function_exists('img_users_url')) {
	function img_users_url($dossier, $folder, $num) {
		return base_url('/web/img/users/'.$dossier.'/'.$folder.'/'.$num);
	}
}


//url avec folder (banner, poster,...)
if ( ! function_exists('folder_img_url')) {
	function banner_user($type, $folder, $id_series, $num) {
		return base_url('/web/img/users/'.$type.'/'.$folder.'/'.$id_series.'/'.$num.'.jpg');
	}
}


//url avec folder (banner, poster,...)
if ( ! function_exists('folder_img_url')) {
	function folder_img_url($type, $folder, $num) {
		return base_url('/web/img/show/'.$type.'/'.$folder.'/'.$num.'.jpg');
	}
}

//url img characters
if ( ! function_exists('characters_img_url')) {
	function characters_img_url($folder, $show_id, $num) {
		return base_url('/web/img/show/characters/'.$folder.'/'.$show_id.'/'.$num.'.jpg');
	}
}

//url img sans folder
if ( ! function_exists('img_show_url')) {
	function img_show_url($type, $num) {
		return base_url('/web/img/show/'.$type.'/'.$num.'.jpg');
	}
}

//css
if ( ! function_exists('css_url')) {
	function css_url($nom) {
		return base_url('web/css/'.$nom.'.css');
	}
}

//js
if ( ! function_exists('js_url')) {
	function js_url($nom) {
		return base_url('web/js/'.$nom.'.js');
	}
}