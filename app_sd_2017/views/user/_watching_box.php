<div class="all_friends_watching">

	<?php foreach($user->visionnages() as $friend) { ?>
			<div class="single_friend">
					<div class="header_user_home">
						<div class="user_avatar_home">
							<?php
							$folder = find_folder($friend['id_users']);
							$url_avatar = 'web/img/users/avatar/'.$folder.'/'.$friend['avatar'];

							if($friend['id_users'] == $user->id()) {
								echo '<a href="'.base_url('profil').'">';
							} else {
								echo '<a href="'.base_url('profil/feed/'.$friend['username']).'">';
							}
							
							if(file_exists($url_avatar) && !empty($friend['avatar'])) {
								echo '<img src="'.img_users_url('avatar', $folder, $friend['avatar']).'" alt="avatar">';
							} else {
								echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
							}
						echo '</div>'; //END__ USER AVATAR_HOME

						echo '<div class="user_banner_home">';
						$folder = find_folder($friend['ban_id_series']);
						
						$url_banner = 'web/img/users/banner_min/'.$folder.'/'.$friend['ban_id_series'].'/'.$friend['banner'].'.jpg';

						if(file_exists($url_banner) && !empty($friend['banner'])) {
							echo '<img src="'.banner_user('banner_min', $folder, $friend['ban_id_series'], $friend['banner']).'" alt="banner">';
						} else {
							echo '<img src="'.base_url('web/img/users/banner_min/00.jpg').'" alt="no_banner">';
						} 
						echo '</a>'; //fin du lien vers le profil de $friend ?>


						<div class="watching_date_home">
							Le <?= $friend['watchdate_fr']; ?>
						</div>
						</div><!--end user_banner_home-->
					</div><!--end header_user_home-->

					<div class="user_name_home">
						<span class="playtime username_home"><?= $friend['username']; ?> </span> a regardé :
					</div>

					<div class="infos_watch_home">
						<div class="poster_home">
							<?php
							$folder = find_folder($friend['series_poster']);

							$url_poster = 'web/img/show/poster_min/'.$folder.'/'.$friend['series_poster'].'.jpg';

							echo '<a href="'.base_url('series/show/'.$friend['id_series']).'">';
							if(file_exists($url_poster)) {
									echo '<img src="'.folder_img_url('poster_min', $folder, $friend['series_poster']).'" alt="poster">';
							} else {
									echo '<img src="'.dossier_img('/show/poster_min/00.jpg').'" alt="poster">';
							} 
							echo '</a>'; //fin du lien vers la fiche de la série?>
						</div><!--end poster_home-->


						<div class="home_count_ep">
							<?php
							if($friend['count_ep'] > 1) {
								echo $friend['count_ep'].' épisodes vus';
							} ?>
						</div>
						<div class="infos_ep_home">
							<?php
							$season = double_unit($friend['episode_season']);
							$episode = double_unit($friend['episode_number']); ?>
							<p class="episode_number_home">S<span class="number"><?= $season ?></span>E<span class="number"><?= $episode ?></span></p>
							<p class="font_awesome chevron"> &#xf138; </p>
							<p class="episode_name_home playtime"><?= $friend['episode_name']; ?></p>
						</div><!--end infos_ep_home-->

					</div><!--end infos_watch_home-->

			</div><!--end single_friend--><?php
	} ?>
</div><!--END all_friends_watching-->