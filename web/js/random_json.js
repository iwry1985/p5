$('#die_random_watchlist').on('click', function() {
	var number = $('select.random_watchlist').val();
	var base_url = $('.base_url').val();

	
	var random_box = document.getElementById("random_selec");
	var section_random = document.createElement("div");

	//on réinitialise la div
	random_box.innerHTML = '';


	ajaxGet(base_url + 'Frontend_ajax_ctler/executeRandom_watchlist/'+number
	, function(reponse) {
		//on récupère les données en json
		var episodes = JSON.parse(reponse);
		
		//on ajoute la section et on la fait apparaitre dans le DOM
		$(random_box).append(section_random).slideDown();


		//on crée tous les éléments pour chaque série
		episodes.forEach(function(episode) {
			//on mets tous les éléments dans des variables
			var img = episode.img;
			var id_series = episode.id_series;
			var name = episode.name;
			var season = episode.season;
			var number = episode.number

			//on va chercher le numéro de folfer (premier chiffre du num d'img)
			var folder = img.substr(0,1);

			if(number < 10) {
				number = '0' + number;
			}

			if(season < 10) {
				season = '0' + season;
			}

			//On crée la poster dans le lien
			var div_single_random = document.createElement("div");
			$(div_single_random).addClass('single_weekly');
			$(section_random).append(div_single_random);

			var poster = $('<a href="'+ base_url + 'series/show/' + id_series +'"><img src="' + base_url + 'web/img/show/poster_min/' + folder + '/' + img + '.jpg" alt="poster"></a>');
			$(div_single_random).append(poster);


			var encart = $('<div class="encart"><p>S<span class="number_random">'+season+'</span>E<span class="number_random">'+number+'</span></p></div>');
			$(encart).addClass('encart_random')
			$(poster).append(encart);

			
		})

		//on ajouter un bouton pour cacher la section random
		var btn_close = $('<button>Fermer</button>');
		$(btn_close).addClass('btn_close');
		$(random_box).append(btn_close);

		//et on remonte la section qd on clique sur 'fermer'
		$('.btn_close').on('click', function() {
			$(random_box).slideUp();
		})

	});
	return false;
});

//----------------------------------------------------------------------------------------------
$('#die_random_waitinglist').on('click', function() {
	var number = $('select.random_waitinglist').val();
	var base_url = $('.base_url').val();

	
	var random_box = document.getElementById("random_selec");
	var section_random = document.createElement("div");

	//on réinitialise la div
	random_box.innerHTML = '';


	ajaxGet(base_url + 'Frontend_ajax_ctler/executeRandom_waitingList/'+number
	, function(reponse) {
		//on récupère les données en json
		var series = JSON.parse(reponse);
		
		//on ajoute la section et on la fait apparaitre dans le DOM
		$(random_box).append(section_random).slideDown();

		//on crée tous les éléments pour chaque série
		series.forEach(function(serie) {
			//on mets tous les éléments dans des variables
			var img = serie.img;
			var id_series = serie.id;
			var count = serie.count_ep;

			//on va chercher le numéro de folfer (premier chiffre du num d'img)
			var folder = img.substr(0,1);

			//On crée la poster dans le lien
			var div_single_random = document.createElement("div");
			$(div_single_random).addClass('single_weekly');
			$(section_random).append(div_single_random);

			var poster = $('<a href="'+ base_url + 'series/show/' + id_series +'"><img src="' + base_url + 'web/img/show/poster_min/' + folder + '/' + img + '.jpg" alt="poster"></a>');
			$(div_single_random).append(poster);

			var encart = $('<div class="encart"><p>'+count+' épisodes à voir</p></div>');
			$(encart).addClass('encart_random')
			$(poster).append(encart);
			
		})

		//on ajouter un bouton pour cacher la section random
		var btn_close = $('<button>Fermer</button>');
		$(btn_close).addClass('btn_close');
		$(random_box).append(btn_close);

		//et on remonte la section qd on clique sur 'fermer'
		$('.btn_close').on('click', function() {
			$(random_box).slideUp();
		})

	});
	return false;
});

//--------------------------------------------------------------------------------------
$('#die_random_beginlist').on('click', function() {
	var number = $('select.random_beginlist').val();
	var base_url = $('.base_url').val();

	
	var random_box = document.getElementById("random_selec");
	var section_random = document.createElement("div");

	//on réinitialise la div
	random_box.innerHTML = '';


	ajaxGet(base_url + 'Frontend_ajax_ctler/executeRandom_beginList/'+number
	, function(reponse) {
		//on récupère les données en json
		var series = JSON.parse(reponse);
		
		//on ajoute la section et on la fait apparaitre dans le DOM
		$(random_box).append(section_random).slideDown();

		//on crée tous les éléments pour chaque série
		series.forEach(function(serie) {
			//on mets tous les éléments dans des variables
			var img = serie.img;
			var id_series = serie.id;
			var count = serie.count_ep

			//on va chercher le numéro de folfer (premier chiffre du num d'img)
			var folder = img.substr(0,1);

			//On crée la poster dans le lien
			var div_single_random = document.createElement("div");
			$(div_single_random).addClass('single_weekly');
			$(section_random).append(div_single_random);

			var poster = $('<a href="'+ base_url + 'series/show/' + id_series +'"><img src="' + base_url + 'web/img/show/poster_min/' + folder + '/' + img + '.jpg" alt="poster"></a>');
			$(div_single_random).append(poster);

			var encart = $('<div class="encart"><p>'+count+' épisodes à voir</p></div>');
			$(encart).addClass('encart_random')
			$(poster).append(encart);
		})

		//on ajouter un bouton pour cacher la section random
		var btn_close = $('<button>Fermer</button>');
		$(btn_close).addClass('btn_close');
		$(random_box).append(btn_close);

		//et on remonte la section qd on clique sur 'fermer'
		$('.btn_close').on('click', function() {
			$(random_box).slideUp();
		})

	});
	return false;
});