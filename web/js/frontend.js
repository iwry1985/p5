$(document).ready(function() {
    
    //Pour que les liens internes s'ouvrent dans la même fenêtre et que les liens externes s'ouvrent dans une fenêtre différente
    $('a').attr('target', function() {
        if (this.host == location.host) return '_self'
        else return '_blank'
    });

/*---------------------------------------------------------------------------------------------------------------------------------------------------------------
                CHARACTERS
-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    //afficher/fermer section personnages
    $('#hide_char').on('click', function() {
        toggle_section('#hide_char', '#characters_box', 'Afficher', 'Cacher');
    });

    //fermer section personnages
    $('#close_characters').on('click', function() {
        $('#characters_box').slideUp("slow");
        $('#hide_char').html('Afficher');
        $('html,body').animate({scrollTop: $("#section_characters").offset().top}, 'slow');
    });

/*---------------------------------------------------------------------------------------------------------------------------------------------------------------
                EPISODES DIFFUSES LA VEILLE
-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    //afficher/fermer section personnages
    $('#hide_ep').on('click', function() {
        toggle_section('#hide_ep', '#slider_box', 'Afficher', 'Cacher');
    });



/*----------------------------------------------------------------------------------------------------------------------------------------------------------------
                EPISODES
-------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
    //hover sur bouton 'vu'
   /* $('.eye_button').hover(function() {
        console.log('hover');
        var button = this;
        var value = $(this).val();
        var url = $('.url_icon').val();
        input_hover_in(button, value, url);
    }, function() {
        var button = this;
        var value = $(this).val();
        var url = $('.url_icon').val();
        change_img(button, value, url);
    });*/

    //mouseenter sur bouton 'vu'
    $('.episodes_box').on('mouseenter', '.eye_button', function(){
        var button = this;
        var value = $(this).val();
        var url = $('.url_icon').val();
        input_hover_in(button, value, url);
    });

    //mouseleave sur bouton 'vu'
    $('.episodes_box').on('mouseleave', '.eye_button', function(){
        var button = this;
        var value = $(this).val();
        var url = $('.url_icon').val();
        change_img(button, value, url);
    });


/*----------------------------------------------------------------------------------------------------------------------------------------------------------------
    AJOUT SHOW USER
-------------------------------------------------------------------------------------------------------------------------------------------------------------------*  
Ajouter la série /*/
    $('#serie_addShow').on('click', function() {
        var id_series = $('.id_show').val();
        var base_url = $('.base_url').val();
        var img = base_url + 'web/img/icons/eye.png';

        $.ajax({
            url: base_url + 'Frontend_ajax_ctler/user_add_show',
            type: "post",
            data : 'id_series='+ id_series,

            success: function(response) {
                if(response === 'added') {
                    var to_hide = ['.form_add_show', '.add_show_hidden'];
                    var to_show = ['.status_hidden', '.watch_status', '#carre'];
                    $('.watch_status option[value='+1+']').prop('selected', true);
                    $('.watch_note option[value='+1+']').prop('selected', true);
                    $('#carre').removeClass().addClass('bck_commencer');
                    $('.status_icon img').attr('src', img);
                    change_img('.watch_note_emo img', 1, base_url + 'web/img/icons/emoticon/');

                    show_array(to_show);
                    hide_array(to_hide);
                } 
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        })
        return false;
    });



/*-------------------------------------------------------------------------------------------------------------------------------------------------------------
    SELECT SHOW STATUS
-----------------------------------------------------------------------------------------------------------------------------------------------------------------
*  Sélectionner le statut de la série (à commencer, en cours,.../*/
    //on retient quelle option est sélectionnée
    var status_prec = $('.watch_status option:selected').val();

    $('.frontend_content').on('change', '.watch_status', function(){
        var id_series = $('.id_show').val();
        var statut = $('select.watch_status').val();
        var base_url = $('.base_url').val();
        var url_img = base_url + 'web/img/icons/';
        var show_status = $('.etat_show').val();

        if(statut === '1') {
            //un message s'affiche avertissant que les données concernant cette série seront effacées
            if(confirm('En mettant cette série \'à commencer\', toutes les données éventuelles seront effacées.')) {
                changed_status()

            } else {
                //on remet l'option sélectionnée auparavant
                $('.watch_status option[value='+status_prec+']').prop('selected', true);
            }
        } else {
            changed_status();
        }

        //fonction ajax pour le select
        function changed_status() {
            $.ajax({
                url: base_url + 'Frontend_ajax_ctler/user_select_status',
                type: "post",
                data : 'id_series='+ id_series +'&statut=' + statut,

                success: function(response) {
                    if(response === 'status_changed') {
                        switch_status(statut, url_img);
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
        }
        return false;  
    });



/*-------------------------------------------------------------------------------------------------------------------------------------------------------------
    SELECT NOTES
-----------------------------------------------------------------------------------------------------------------------------------------------------------------*
    Changer note_user /*/
    $('.select_user_note').on('change', function() {
        var id_series = $('.id_show').val();
        var note = $('select.select_user_note').val();
        var base_url = $('.base_url').val();
        var url_img = base_url + 'web/img/icons/emoticon/';

            $.ajax({
                url: base_url + 'Frontend_ajax_ctler/user_change_note',
                type: "post",
                data : 'id_series='+ id_series + '&note=' + note,

                success: function(response) {
                    if(response === 'note_changed') {
                        change_img('.watch_note_emo img', note, url_img)
                    } 
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
        return false;
    });
//-----------------------------------------------------------------------



/*-------------------------------------------------------------------------------------------------------------------------------------------------------------
    DELETE SHOW (for user)
-----------------------------------------------------------------------------------------------------------------------------------------------------------------*
    supprimer la série /*/
    $('#serie_delShow_id').on('click', function() {
        var id_series = $('.id_show').val();
        var base_url = $('.base_url').val();
        var img = base_url + 'web/img/icons/eye_coche.png';

        if(confirm('Veux-tu vraiment supprimer cette série de ta liste (les informations seront perdues) ?')) {

            $.ajax({
                url: base_url + 'Frontend_ajax_ctler/user_delete_show',
                type: "post",
                data : 'id_series='+ id_series,

                success: function(response) {
                    if(response === 'deleted') {
                        var to_show = ['.add_show_hidden', '.form_add_show'];
                        var to_hide = ['.watch_status', '.watch_note', '.hidden_note', '.status_hidden', '#carre', '.hidden_eye', '.ep_button', '.infos_progress', '.infos_progress_hidden', '.btn_tout_cocher'];
                        $('.tableau_ep tbody tr').removeClass('tr_ep_seen');
                        $('.eye_button').attr('src', img);
                        $('.eye_button').attr('value', 'eye_coche');

                        show_array(to_show);
                        hide_array(to_hide);
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
            return false;
        }
    });


 /*-------------------------------------------------------------------------------------------------------------------------------------------------------------
    AJOUTER/SUPPRIMER EPISODE
-----------------------------------------------------------------------------------------------------------------------------------------------------------------*
    Ajouter un épisode /*/
    $('.episodes_box').on('click', '.ep_button', function() {
        var id_series = $('.id_show').val();
        var id_ep = $(this).parents('.coche_decoche').find('.id_ep').val();
        var base_url = $('.base_url').val();
        var color = $(this).parents('tr');
        var url_img = base_url + 'web/img/icons/';
        var eye_coche = 'eye_coche';
        var eye_decoche = 'eye_decoche';
        var img = $(this).find('.eye_button');
        var restants = $('.nb_vus').html();


           $.ajax({
                url: base_url + 'Frontend_ajax_ctler/user_episode',
                type: "post",
                data : 'id_series='+ id_series + '&id_ep=' + id_ep,

                success: function(response) {
                    if(response === 'ep_added') {
                       color.addClass('tr_ep_seen');
                       $(img).attr('src', url_img + eye_decoche + '.png').attr('value', eye_decoche);
                       restants = Number(restants) - 1;
                       change_progress(restants);

                       
                    } else {
                        if(response === 'ep_deleted') {
                            color.removeClass('tr_ep_seen');
                            $(img).attr('src', url_img + eye_coche + '.png').attr('value', eye_coche);
                            restants = Number(restants) + 1;
                            change_progress(restants);
                        } 
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
            return false;
    });


    //AJOUTER/SUPPRIMER TOUS LES EP DE LA SAISON
    $('.episodes_box').on('click', '.btn_tout_cocher', function() {
        var id_series = $('.id_show').val();
        var base_url = $('.base_url').val();
        var season = $('.coche_season').val();
        season = parseInt(season);
        var type = $('.coche_type').val();
        var page = $('.page').val();
        var limit = $(this).parents('.episodes_box').find('.nb_ep').html();

        var tr = $(this).parents('tr');
        var color = $('tr').not(tr);
        var url_img = base_url + 'web/img/icons/';
        var eye_coche = 'eye_coche';
        var eye_decoche = 'eye_decoche';
        var img = $('.eye_button').not(this);
        var restants = $('.nb_vus').html();
        var nb_ep = $('.nb_ep').val();
        nb_ep = parseInt(nb_ep);
        var btn = $(this);

           $.ajax({
                url: base_url + 'Frontend_ajax_ctler/coche_all_season_ep',
                type: "post",
                data : 'id_series='+ id_series + '&season=' + season + '&type=' + type + '&page=' + page,

                success: function(response) {
                    if(response === 'eps_added') {
                       $(img).attr('src', url_img + eye_decoche + '.png').attr('value', eye_decoche);
                       restants = Number(restants) - nb_ep;
                       change_progress(restants);
                       $('.coche_type').val('delete');
                       $('.eye_button').attr('title', 'Tout décocher');
                       color.addClass('tr_ep_seen');
                       $(btn).hide();

                       //si on est sur la page série et que la saison fait plus de 10 épisode, on déroule le reste de la saison
                       if(page === 'watchlist' && limit > 10) {
                            var etat = 'seen';
                            show_all_ep_from_season_watchlist(limit, etat);
                       }
                       
                    } else {
                        if(response === 'eps_deleted') {
                            color.removeClass('tr_ep_seen');
                            $(btn).hide();
                            restants = Number(restants) + nb_ep;
                            change_progress(restants);
                             $('.coche_type').val('add');
                             $('.eye_button').attr('title', 'Tout cocher');
                             $(img).attr('src', url_img + eye_coche + '.png').attr('value', eye_coche);
                        } 
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
            return false;
    });


//---------------------------------------------------------------------------------------------------------------------------------------
//HOVER AFFICHE SYNOPSIS SUR POSTER SERIES
if(window.matchMedia("(min-width: 500px").matches) {
    $('.single_sugg').hover(function() {
        var synopsis = $(this).find('.synopsis_sugg');
        var lien = $(this).find('.lien_serie_sugg');
         $(synopsis).toggle();
         $(lien).toggle();
    });
}

//------------------------------------------------------------------------------------

//---------------------------------------
    //AJOUTER DES AMIS
$('#add_friend').on('click', function() {
    var profil_id = $(this).parents('.btn_friend').find('.profil_id').val();
    var base_url = $(this).parents('.btn_friend').find('.base_url').val();
    var pseudo = $(this).parents('.btn_friend').find('.user_pseudo').val();
    var encart_avatar =  $(this).parents('.encart_avatar');

    $.ajax({
        url: base_url + 'Frontend_ajax_ctler/add_friend',
        type: "post",
        data : 'profil_id='+ profil_id,

        success: function(response) {
            if(response == 'add') {
                $(encart_avatar).fadeOut();
                $('.encart_attente_demande').removeClass('hide');
                var encart = $('<div class="alert alert-success"> Une demande a été envoyée à ' + pseudo +'</div>');
                $('.menu_profil').after(encart);
                $(encart).fadeIn().delay(2000).fadeOut();
            } 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
        })
    return false;
});
//----------------------------------------------------------------
    //SUPPRIMER DES AMIS
$('#delete_friend').on('click', function() {
    var profil_id = $(this).parents('.btn_friend').find('.profil_id').val();
    var base_url = $(this).parents('.btn_friend').find('.base_url').val();
    var pseudo = $(this).parents('.btn_friend').find('.user_pseudo').val();
    var encart_avatar =  $(this).parents('.encart_avatar');

    if(confirm('Veux-tu vraiment supprimer ' + pseudo + ' de tes amis')) {

    $.ajax({
        url: base_url + 'Frontend_ajax_ctler/delete_friend',
        type: "post",
        data : 'profil_id='+ profil_id,

        success: function(response) {
            if(response == 'delete') {
                $(encart_avatar).fadeOut();
                var encart = $('<div class="alert alert-success">'+ pseudo+' ne fait plus partie de tes amis</div>');
                $('.menu_profil').after(encart);
                $(encart).fadeIn().delay(2000).fadeOut();
            } 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
        })
    return false;
    }
});
//----------------------------------------------------------------
    //ACCEPTER DEMANDE D'AMI
$('#accept').on('click', function() {
    var profil_id = $(this).parents('.form_friends_request').find('.profil_id').val();
    var base_url = $(this).parents('.form_friends_request').find('.base_url').val();
    var pseudo = $(this).parents('.single_request').find('.req_pseudo').html();
    var form = $(this).parents('.single_request');

    $.ajax({
        url: base_url + 'Frontend_ajax_ctler/accept_friend_request',
        type: "post",
        data : 'profil_id='+ profil_id,

        success: function(response) {
            if(response == 'accept') {
                form.hide();
                var encart = $('<div class="alert alert-success"><span class="bold">'+pseudo + '</span> fait à présent partie de tes amis !</div>');
                $('.menu_profil').after(encart);
                $(encart).fadeIn().delay(2000).fadeOut();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            }
        })
    return false;
})
//--------------------------------------------------------------------
    //REFUSER DEMANDE D'AMI
$('#denied').on('click', function() {
    var profil_id = $(this).parents('.form_friends_request').find('.profil_id').val();
    var base_url = $(this).parents('.form_friends_request').find('.base_url').val();
    var pseudo = $(this).parents('.single_request').find('.req_pseudo').html();
    var form = $(this).parents('.single_request');

    $.ajax({
        url: base_url + 'Frontend_ajax_ctler/denied_friend_request',
        type: "post",
        data : 'profil_id='+ profil_id,

        success: function(response) {
            if(response == 'denied') {
                form.fadeOut("slow");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            }
        })
    return false;
})

    //SUPPRESSION DU COMPTE
$('.delete_account').on('click', function() {
    msg = "goodbye";
    base_url = $('.base_url').val();

    if(confirm('Tu es sur le point de supprimer ton compte SeriesDOM.\nToutes tes données seront effacées. Cette action est irréversible.')) {
        $.ajax({
            url: base_url + 'Frontend_ajax_ctler/delete_account_user',
            type: "post",
            data: 'msg=' + msg,

            success: function(response) {
                if(response == 'deleted') {
                     document.location.href=base_url + 'deleteAccount';
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                }
        })
    }
})

//---------------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------

//CHANGER SECTION EPISODE/SEASON SANS RECHARGER LA PAGE
 $('.season_button').on('click', function() {
        var id_series = $('.id_show').val();
        var season = $(this).parents('.season_').find('.changeto_season').html();
        var base_url = $('.base_url').val();

            $.ajax({
                url: base_url + 'Frontend_ajax_ctler/change_season',
                type: "post",
                data : 'id_series='+ id_series + '&season=' + season,

                success: function(response) {
                   $('.tableau_ep').replaceWith(response);
              
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
            
        $('.season_button').removeClass('season_active');
        $(this).addClass('season_active');

        return false;
    });
//------------------------------------------------------------------------------- 
//CHANGER LA SERIE A AFFICHER WATCHLIST 
$('.btn_running_show').on('click', function(e) {
    e.preventDefault();
    var id_series = $(this).parents('.single_name').find('.id_series').html();
    var seasons = $(this).parents('.single_name').find('.show_seasons').html();
    var base_url = $('.base_url').val();

    
        $.ajax({
            url: base_url + 'Frontend_ajax_ctler/change_show_watchlist',
            type: "post",
            data : 'id_series='+ id_series + '&seasons=' + seasons,

            success: function(response) {
                 /*$('.single_listing').replaceWith(response);*/
                 $('.watching_show_box').fadeOut(function() {
                    var div = $("<div class='watching_show_box'>"+response+"</div>").hide();
                    $(this).replaceWith(div);
                    $('.watching_show_box').fadeIn();
                 })
              
            },
                error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
           }
        })
})

//-----------------------------------------------------------------------------------------------
//afficher tous les épisodes de la saison
$('.episodes_box').on('click', '.plus_ep', function() {
    var limit = $(this).parents('.episodes_box').find('.nb_ep').html();
    var etat = 'unseen';
    show_all_ep_from_season_watchlist(limit, etat);

    return false;
})
//---------------------------------------------------------------------------------------------
//change la saison (watchlist)
$('.episodes_box').on('click', '.seasons_watchlist', function() {
    var id_series = $('.id_show').val();
    var base_url = $('.base_url').val();
    var season = $(this).find('.seasons_to_watch').html();
    season = season.substring(1,season.length);
    var tbody = $('.tbody');
    var type = "change_season";
    $('.plus_ep').addClass('hide');

    $(this).addClass('season_active');
    $('.seasons_watchlist').not(this).removeClass('season_active');
    $('.plus_ep').fadeIn();
    
    $.ajax({
       url: base_url + 'Frontend_ajax_ctler/show_all_season',
       type: "post",
       data : 'id_series='+ id_series + '&season=' + season + '&type=' + type,

        success: function(response) {
            tbody.remove();
            $('.tableau_ep').empty();
            $('.tableau_ep').replaceWith(response);
            $('.season').html(season);

            //ajuste automatique la taille de la fenêtre
            var el = $('.single_listing');
            curHeight = el.height();
            autoHeight = el.css('height', 'auto').height();
        },
            error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
       }

    })
    return false;
})

//----------------------------------------------------------------------------------------------------
    //changer bannière profil
    $('.this_ban').on('click', function() {
        var id_series = $('.id_show').val();
        var base_url = $('.base_url').val();
        var banner = $('.num_banner:checked').val();
        var single_ban = $(this).parents('.single_ban').find('.flash');

        $.ajax({
            url: base_url + 'Frontend_ajax_ctler/change_banner_users',
       type: "post",
       data : 'id_series='+ id_series + '&banner=' + banner,

       
         success: function(response) {
            if(response === 'banner_up') {
               flash = create_flash('success', 'La bannière a bien été modifiée.');
               $(flash).css({'position': 'absolute', 'border-radius': '0px', 'bottom': '0', 'font-weight':'bold', 'text-align':'center', 'z-index':'10', 'display':'block', 'width' : '98%', 'margin-left': '4px'});
               $(flash).insertAfter(single_ban).fadeIn('slow').delay(2000).fadeOut();
            }
        },
            error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    })
})
//------------------------------------------------------------------

//------------------------------------------------------------

//------------------------------------------------------------
})
