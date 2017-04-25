<div class="menu_profil">
			<ul>
				<?php if($this->router->fetch_method() == 'index') { ?>
					<li class="profil_active"><a href="<?= base_url('profil/feed/'.$profil->username()) ?>">Feed</a></li><?php
				} else { ?>
					<li><a href="<?= base_url('profil/feed/'.$profil->username()) ?>">Feed</a></li><?php
				} ?>


				<?php 
				if($user->shows_running() > 0 && $profil->id() == $user->id()) {
					if($this->router->fetch_method() == 'watchlist') { ?>
						<li class="profil_active"><a href="<?= base_url('profil/watchlist/') ?>">Watchlist</a></li><?php
					} else { ?>
						<li><a href="<?= base_url('profil/watchlist/') ?>">Watchlist</a></li><?php
					} 
				} ?>
				

				<?php 
				if($user->shows_toCatch() > 0 && $profil->id() == $user->id()) {
					if($this->router->fetch_method() == 'waitinglist') { ?>
						<li class="profil_active"><a href="<?= base_url('profil/waitinglist/') ?>">à rattraper</a></li><?php
					} else { ?>
						<li><a href="<?= base_url('profil/waitinglist/') ?>">à rattraper</a></li><?php
					} 
				} ?>

				<?php 
				if($user->shows_toBegin() > 0 && $profil->id() == $user->id()) {
					if($this->router->fetch_method() == 'beginlist') { ?>
						<li class="profil_active"><a href="<?= base_url('profil/beginlist/') ?>">à commencer</a></li><?php
					} else { ?>
						<li><a href="<?= base_url('profil/beginlist/') ?>">à commencer</a></li><?php
					} 
				} ?>

				<?php 
				if($user->count_seen_shows() > 0 && $profil->id() == $user->id()) {
					if($this->router->fetch_method() == 'bilan') { ?>
						<li class="profil_active"><a href="<?= base_url('profil/bilan') ?>">Bilan</a></li><?php
					} else { ?>
						<li><a href="<?= base_url('profil/bilan') ?>">Bilan</a></li><?php
					} 
				} ?>

				
				<li class="hidden"><a href="<?= base_url('profil/series/') ?>">Mes séries</a></li>
				<li class="hidden"><a href="<?= base_url('profil/favorites/') ?>">Mes coups de coeur</a></li>
				<li class="hidden"><a href="<?= base_url('profil/amis/') ?>">Amis</a></li>
				<!--<li><a href="<?= base_url('profil/stats/') ?>">Stats</a></li>-->
				<!--<li><a href="<?= base_url('profil/bilan/') ?>">Bilan</a></li>-->
			</ul>
		</div><!--end menu_profil-->



<div class="encart_request">
	<?php
	//encart si demande d'amis
	if(!empty($requests)) { 
		foreach($requests as $req) { ?>
			<div class="single_request">
				<div class="request_infos">
					<div class="infos_friend">
						<a href="<?= base_url('profil/feed/'.$req->username().''); ?>" data-toggle="tooltip" title="<?= $req->username() ?>">
							<?php
							$folder = find_folder($req->id());
							$avatar = 'web/img/users/avatar/'.$folder.'/'.$req->avatar();
									
							if(file_exists($avatar) && !empty($req->avatar())) {
								echo '<img src="'.img_users_url('avatar', $folder, $req->avatar()).'" alt="avatar">';
							} else {
								echo '<img src="'.base_url('web/img/users/avatar/00.png').'" alt="no_avatar">';
							} ?>
						</a>
					</div><!--end infos_friend-->
					<div class="info_request">
						<p>
							<span class="request_username"><a href="<?= base_url('profil/feed/'.$req->username().''); ?>" data-toggle="tooltip" title="<?= $req->username() ?>" class="req_pseudo"><?= $req->username() ?></a></span><br />
							souhaiterait t'ajouter <br/>à sa liste d'amis
						</p>
					</div>
				</div><!--end request_infos-->

				<form method="post" action="" class="form_friends_request">
					<input type="hidden" value="<?= $req->id(); ?>" class="profil_id">
					<input type="hidden" value="<?= base_url() ?>" class="base_url">
					<input type="submit" id="accept" class="btn btn-success btn_lien" name="accept" value="Accepter">
					<input type="submit" id="denied" value="Refuser" name="denied" class="btn btn-danger btn_lien">
		        </form>
			</div><!--end single_request-->
			<?php
		}
	} ?>
</div>