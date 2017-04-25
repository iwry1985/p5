<nav class="navbar navbar-default navbar-fixed-side">
	<div class="container">
	<div class="navbar-header">
		<button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
	    <a class="navbar-brand" href="<?php site_url(); ?>"><img src="<?php site_url(); ?>web/img/logo/logo.png"></a>
	</div>

	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Profil <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Watchlist</a></li>
					<li><a href="#">Waitinglist</a></li>
                  	<li class="divider"></li>
                  	<li class="dropdown-header">Mes séries</li>
                  	<li><a href="#">Sub-page 3</a></li>
				</ul>
			</li>
		</ul>
		<form class="navbar-form navbar-left">
            <div class="form-group">
                <input class="form-control" placeholder="Search">
            </div>
            <button class="btn btn-default">Search</button>
        </form>
	</div>
	</div>
</nav>