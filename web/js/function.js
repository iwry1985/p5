//Faire apparaitre/disparaitre une section
function toggle_section(button, section, text, new_text) {
	$(section).slideToggle("slow");

	if($(button).html() == text) {
        $(button).html(new_text);
    } else {
        $(button).html(text);
    }
};
//--------------------------------------------------------------------------------------------

//color img hover_in sur input
function input_hover_in(button, value, url) {
		var hover = value + '_hover.png';
		var img = url + hover;
		$(button).attr('src', img);
};


//----------------------------------------------------------------------------------------------------

//CHANGER IMG
//utilisée pour hover_out sur 'eye_button' ép 
//AJAX change_note
function change_img(select, value, url) {
	var img = url + value + '.png';
	$(select).attr('src', img);
};
//-----------------------------------------------------------------------------------------------------



//#########################################
//########## FONCTIONS POUR AJAX ##########
//#########################################
//function pour les différents changements vis à vis du statut
function switch_status(statut, url_img) {
    switch(statut) {
        case '1':
      	    var img = url_img + 'eye.png';
            var color = 'bck_commencer';
            change_img('.watch_note_emo img', 1, url_img + 'emoticon/');
            var fadeOut = ['.watch_note', '.hidden_note','.ep_button', '.infos_progress', '.infos_progress_hidden', '.btn_tout_cocher', '.hidden_eye'];
            var fadeIn = [];
            $('.tableau_ep tbody tr').removeClass('tr_ep_seen');
            $('.watch_note option[value='+1+']').prop('selected', true);
            break;

        case '2':
            var img = url_img + 'clock.png';
            var color = 'bck_rattraper';
            var fadeOut = ['.ep_button', '.hidden_eye', '.btn_tout_cocher'];
            var fadeIn = ['.hidden_note', '.watch_note'];
            break;

        case '3':
            var img = url_img + 'play.png';
            var color = 'bck_enCours';
            var fadeOut = [];
            var fadeIn = ['.hidden_eye', '.btn_tout_cocher', '.ep_button', '.hidden_note', '.watch_note'];
            break;

        case '4':
            var img = url_img + 'check.png';
            var color = 'bck_terminer';
            var fadeOut = ['.hidden_eye', '.btn_tout_cocher', '.ep_button'];
            var fadeIn = ['.hidden_note', '.watch_note'];
            $('.tableau_ep tbody tr').addClass('tr_ep_seen');
            break;

        case '5':
            var img = url_img + 'x.png';
            var color = 'bck_abandonner';
        	var fadeOut = ['.hidden_eye', '.btn_tout_cocher', '.ep_button', '.infos_progress', '.infos_progress_hidden'];
            var fadeIn = ['.hidden_note', '.watch_note'];
            break;
	}
    changed_status(img, color, fadeOut, fadeIn);
};

//liée à switch, pour le changement de statut
function changed_status(img, color, fadeOut, fadeIn) {
    $('.status_icon img').attr('src', img);
    $('#carre').removeClass().addClass(color);
    decode_array(fadeOut, 'fadeOut');
    decode_array(fadeIn, 'fadeIn');
};


//------------------------------------------------------------------------------------------------------------------------------
//fonctions fade_in, fade_out
//Prend en paramètre un tableau et le nom de la fonction
function decode_array(array, type) {
    if(type === 'fadeOut') {
       for(var i=0; i < array.length; i++) {
        $(array[i]).fadeOut("slow");
   		}
    } else {
        for(var i=0; i < array.length; i++) {
        $(array[i]).fadeIn("slow");
        }
    }
};

//------------------------------------------------------------------------------------------------------------------------------
//Functions show(), prend en paramètre un array
function show_array(array) {
    for(var i=0; i < array.length; i++) {
        $(array[i]).show();
        }
};

//--------------------------------------------------------------------------------------------------------------------------------------
//Functions hide(), prend en paramètre un array
function hide_array(array) {
    for(var i=0; i < array.length; i++) {
        $(array[i]).hide();
    }
};

//--------------------------------------------------------------------------
function change_progress(restants) {
	if(restants == 0) {
		$('.progress_txt').html('A jour !');
	} else {
		$('.nb_vus').html(restants);
	}
};

//---------------------------------------------------------------------------
function add_msg_flash(element, text, type, width) {
    var flash = create_flash(type, text);

    $(flash).css({'position': 'absolute', 'border-radius': '0px', 'font-weight':'bold', 'text-align':'center', 'z-index':'10', 'margin-top': '-50px', 'display':'none', 'width' : width});
    $(flash).insertBefore(element).slideToggle().delay(1000).slideToggle();
}

//-----------------------------------------------------------------------------	
//faire apparaitre des messages
function create_flash(type, text) {
    var flash = document.createElement("div");
    $(flash).addClass('alert alert-'+type);
    $(flash).html(text);
    return flash;
}
//------------------------------------------------------------------------------------------
//FONCTIONS pour BTN ADMIN pour modifier épisodes
function edit_episode_show(form, name, edit) {
    $(name).show();
    $(form).addClass('hidden');
    $(edit).css('display', 'inline').show();
}

function edit_episode_hide(form, name, edit, all_form, all_name, all_edit) {
   $(name).hide();
   $(form).removeClass('hidden');
   $(edit).hide();

   $(all_form).not(form).addClass('hidden');
   $(all_name).not(name).show();
   $(all_edit).not(edit).css('display', 'inline').show();
}

//------------------------------------------------------------------------------------------
//AFFICHER TOUS LES EP DE LA WATCHLIST
function show_all_ep_from_season_watchlist(limit, etat) {
    var id_series = $('.id_show').val();
    var season = $('.season').html();
    var base_url = $('.base_url').val();
    var type = "all_ep";
    $('.plus_ep').hide();

    $.ajax({
       url: base_url + 'Frontend_ajax_ctler/show_all_season',
       type: "post",
       data : 'id_series='+ id_series + '&season=' + season + '&limit=' + limit + '&type=' + type + '&etat=' + etat,

        success: function(response) {
            var tbody = $('.tableau_watchlist tbody');
            $(tbody).append(response).slideDown();
            
            //ajuste automatique la taille de la fenêtre
            var el = $('.single_listing');
            curHeight = el.height();
            autoHeight = el.css('height', 'auto').height();
      
        },
            error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
       }
    })
}
//-------------------------------------------------------------------
//extraires les paramètres de l'URL
function extractUrlParams(url) {
    var nb = url.substring(1).split('/');
    var f = [];
    
    //on extrait uniquement les deux derniers paramètres
    var count = nb.length;
    var ext = count - 2;

    var p = 0;
    for (var i=ext; i<count; i++){
        var x = nb[i];
        f[p] = x;
        p += 1;
    }
    return f;
}
 