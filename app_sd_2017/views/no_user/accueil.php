<div class="container-fluid">
	<div class="welcome_banner">
		<?php
		if(!empty($_SESSION['flash'])) {
			foreach($_SESSION['flash'] as $type => $message) {
				echo '<div class="alert alert-'.$type.'">'.$message.'</div>';
			}
			unset($_SESSION['flash']); 
		} ?>
		<div class="welcome_insc">
			<h1>
				<div class="welcome">
					Bienvenue sur<strong><span class="welcome_seriesdom"> Series<span class="DOM">DOM</span></span></strong><br/>
				</div>
				
				<div class="cx_titre">
					<span class="ligne_deco"></span>
					<span class="white pacificoregular">Le site des sérievores indécis !</span>
					<span class="ligne_deco"></span>
				</div>
			</h1>

					

			<div class="welcome_button">
				<a href="<?php site_url(); ?>inscription" class="btn btn-warning"><span class="font_awesome">&#xf044;</span> S'inscrire</a>
				<a href="<?php site_url(); ?>connexion" class="btn btn-warning"><span class="font_awesome">&#xf090;</span> Se connecter</a>
			</div>
		</div><!--end welcome_insc-->

			<img src="<?= img_show_url('cx_banner', '01'); ?>" alt="banner">
	</div><!--end welcome_banner-->

	<div class="bloc_dark">
		<div class="welcome_pitch">
			<p>Tu as trop de séries à voir et tu n'arrives pas à décider laquelle regarder ?<br/>
			<strong><span class="seriesdom"><span class="Alex_bold">SeriesDOM</span></span></strong> règle le problème pour toi en choisissant une série aléatoirement !</p>

			<p>Inscris-toi pour un choix aléatoire en fonction de <span class="tes">tes</span> séries ! 
			<br/ >Et profites-en pour allonger ta liste et en découvrir de nouvelles !</p>
			<a class="welcome_logo" href="<?php site_url(); ?>"><img src="<?php base_url(); ?>web/img/logo/logo.png"></a>
		</div><!--end welcome_pitch-->
	</div><!--end bloc_dark-->

	<div class="bloc_light">
		<div class="welcome_explications">
			<h2 class="pacificoregular">Comment ça marche ?</h2>
			<p>Le DOM de SeriesDOM est l'abréviation du mot anglais 'random' qui signifie 'aléatoire'. 
			Ce site va donc te permettre de découvrir de façon aléatoire de nouvelles séries ainsi que t'aider à choisir quelle série commencer ou rattraper, tout ça en fonction de tes séries ! </p>
			<br/>
			<p>Cette nouvelle version du site est toujours en construction et ne contient pas encore toutes les fonctionnalités de l'ancienne. Elles arriveront au fur et à mesure...</p>
			<p>Enfin, comme le site a été intégralement refait, il se peut que d'éventuels bugs ou problèmes surviennent.Vous allez donc être un peu comme les "beta-testeurs" de cette nouvelle version ^^ <br/><br/>
			N'hésitez donc surtout pas à me faire parvenir tous les problèmes que vous pourriez rencontrer (via facebbok, twitter (@seriesdom) ou mail (seriesdom@gmail.com)<br/>
Je compte sur vous ! Et surtout, j'espère que la nouvelle version vous plaira ! <br/>
		</div><!--end welcome_explications-->
	</div><!--end bloc_light-->
</div><!-- end container-fluid-->