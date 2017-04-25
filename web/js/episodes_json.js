

/* $('.season_button').on('click', function() {
var id_series = $(this).parents('.season_').find('#id_show_forSeasons').val();
var season = $(this).parents('.season_').find('.changeto_season').val();
 var base_url = $(this).parents('.season_').find('.base_url').val();

	ajaxGet(base_url + "Frontend_ajax_ctler/change_season/"+id_series+'/'+season , function(reponse) {
	    var episodes = JSON.parse(reponse);

	    episodes.forEach(function(episode) {
	    	var season = double_unit(episode.season);

	    	var number = double_unit(episode.number);
	    	console.log(number);

	    	$('.ep_season_').html(season);
	    	$('.ep_number_').html(number);
	    });
	  
	});
	return false;
});

 function double_unit(number) {
 	if(number < 10) {
 		number = '0' + number;
 	}
 	return number;
 }*/