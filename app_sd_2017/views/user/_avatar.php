<div class="user_presentation_home_avatar">
	<?php
	$folder = find_folder($user->id());
	$url_avatar = 'web/img/users/avatar/'.$folder.'/'.$user->avatar();

	if(file_exists($url_avatar) && !empty($user->avatar())) {
		echo '<img src="'.img_users_url('avatar', $folder, $user->avatar()).'?'.time().'" alt="avatar">';
	} else {
		echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
	} ?>
	
	<div class="menu_avatar">				
		<div class="lien_profil">
			<a href="<?php echo base_url('profil') ?>" class="username kalambold">Profil</a>
		</div>
		<div class="lien_profil">
			<a href="<?php echo base_url('profil/watchlist') ?>" class="username kalambold">Watchlist</a>
		</div>
	</div>
</div><!--end user_presentation_home_avatar-->