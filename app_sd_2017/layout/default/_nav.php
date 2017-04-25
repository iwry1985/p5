<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand logo" href="<?php echo base_url(); ?>"><img src="<?= base_url("web/img/logo/Logo_facebook.png"); ?>"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

      <?php
      if($this->router->fetch_class() == 'home') {
        echo '<li class="active">';
      } else {
        echo '<li>';
      } ?>
            <a href="<?= base_url(''); ?>">
            <span class="nav_icon"><img src="<?= dossier_img('icons/home.png') ?>" alt="icon_home"></span> Accueil</a></li>

      <?php
      if(!empty($user)) {
        if($this->router->fetch_class() == 'profil') {
          echo '<li class="dropdown active">';
        } else {
          echo '<li class="dropdown">';
        } ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">

          <span class="nav_icon"><img src="<?= dossier_img('icons/user.png') ?>" alt="icon_user"></span>
       Profil <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?= base_url('profil') ?>">Feed</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="<?= base_url('profil/watchlist') ?>">Watchlist</a></li>
            <li><a href="<?= base_url('profil/waitinglist') ?>">Séries à rattraper</a></li>
            <li><a href="<?= base_url('profil/beginlist') ?>">Séries à commencer</a></li>
            <li role="separator" class="divider"></li>
           <li><a href="<?= base_url('profil/bilan') ?>">Bilan du mois</a></li>
          </ul>
        </li> <?php
      } 
        if($this->router->fetch_class() == 'series') {
          echo '<li class="dropdown active">';
        } else {
          echo '<li class="dropdown">';
        } ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
          <span class="nav_icon"><img src="<?= dossier_img('icons/tv.png') ?>" alt="icon_series"></span>
          Séries <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?= base_url('series/listing'); ?>">Toutes les séries</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="<?= base_url('series/derniers_ajouts'); ?>">Derniers ajouts</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li>
        <form class="navbar-form navbar-left search_input" action="<?= base_url('series/search'); ?>" method="post">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Recherche" name="search">
          </div>
        </form>
      </li>
        <?php
        if(!empty($user)) {
          if($user->admin() == 'admin') { 
            if($this->router->fetch_class() == 'admin') {
              echo '<li class="dropdown active drop_admin">';
            } else {
              echo '<li class="dropdown drop_admin">';
            } ?>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="nav_icon nav_icon_admin"><img src="<?= dossier_img('icons/tools.png') ?>" alt="icon_admin"></span> Admin <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="<?= base_url('admin') ?>">Administration</a></li>
                <li><a href="<?= base_url('admin/show') ?>">Ajouter une série</a></li>
              </ul>
            </li> <?php
            }
          } 
          
        if(!empty($user)) { ?>
          <li><a href="<?= base_url('deconnexion'); ?>">
          <span class="nav_icon"><img src="<?= dossier_img('icons/power.png') ?>" alt="icon_deconnexion"></span> Déconnexion</a></li>
        <?php
        } else { ?>
          <li><a href="<?= base_url('inscription'); ?>">
          <span class="nav_icon"><img src="<?= dossier_img('icons/inscription.png') ?>" alt="icon_inscription"></span> Inscription</a></li>
           <li><a href="<?= base_url('connexion'); ?>">
          <span class="nav_icon"><img src="<?= dossier_img('icons/compose.png') ?>" alt="icon_connexion"></span> Connexion</a></li>
        <?php
        } ?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>