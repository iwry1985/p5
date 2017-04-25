$(document).ready(function() {
    //update/ajout de personnages
	$('.single_characters').on('click', '.char_submit', function() {

		var id_tv_maze = $(this).parents('.add_char_form').find('.id_tv_maze').val();
		var name = $(this).parents('.add_char_form').find('.char_name').val();
		var actor_name = $(this).parents('.add_char_form').find('.actor_name').val();
        var id = $(this).parents('.add_char_form').find('.id_character').val();
		var img = $(this).parents('.add_char_form').find('.num_img').val();
		var id_series = $('.id_show').val();
		var base_url = $('.base_url').val();
		var form = $(this).parents('.add_char_form');
        var new_img = parseInt(img) + 1;
        var single_char = $(this).parents('.single_characters');
        var button = $(this);
        console.log(name);

		$.ajax({
            url: base_url + 'Admin/executeCharacters',
            type: "post",
            data : 'id_tv_maze='+ id_tv_maze + '&name=' + name + '&actor_name=' + actor_name + '&img=' + img + '&id_series=' + id_series + '&id=' + id,

            success: function(response) {
                if(response ==='update_ok') {
                	var text = "Modifications enregistrées.";
                	add_msg_flash(form, text, 'success', '82.5%');

                } else if(response === 'character_added') {
                    $('.num_img').val(new_img);
                    var text = "Personnage ajouté";
                    add_msg_flash(form, text, 'success', '82.5%');
                    $(single_char).addClass('char_added');
                    $(button).fadeOut();
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        })
        return false;
	});
//----------------------------------------------------------------------
    //supprimer personnage de la bdd
    $('.delete_char_btn').on('click', function() {
        if(confirm('Supprimer le personnage ?')) {

            var id = $(this).parents('.add_char_form').find('.id_character').val();
            var base_url = $('.base_url').val();
            var single_char = $(this).parents('.single_characters');
            var form = $(this).parents('.add_char_form');
           
           $.ajax({
                url: base_url + 'Admin/executeDeleteCharacters',
                type: "post",
                data : 'id='+ id,

                success: function(response) {
                    if(response === 'deleted') {
                        var text = "Personnage supprimé";
                            add_msg_flash(form, text, 'danger', '82.5%');
                            setTimeout(function(){       
                                $(single_char).fadeOut("slow")
                            }, 1000);  
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                   console.log(textStatus, errorThrown);
                }
            })
            return false;
        }
    });
//------------------------------------------------------------------------------------------
   //update networks
    $('.single_network').on('click', '.network_submit', function() {


        var form = $(this).parents('.network_form');
 

         var text = "Modifications enregistrées.";
         var flash = add_msg_flash(form, text, 'success', '85%');
         $(flash).css('width', '90%');



        /*$.ajax({
            url: base_url + 'Admin/executeCharacters',
            type: "post",
            data : 'id_tv_maze='+ id_tv_maze + '&name=' + name + '&actor_name=' + actor_name + '&img=' + img + '&id_series=' + id_series + '&id=' + id,

            success: function(response) {
                if(response ==='update_ok') {
                    var text = "Modifications enregistrées.";
                    add_msg_flash(form, text, 'success');

                } else if(response === 'character_added') {
                    $('.id_new_char').val(new_id);
                    $('.id_character').val(new_img);
                    var text = "Personnage ajouté";
                    add_msg_flash(form, text, 'success');
                    $(single_char).addClass('char_added');
                    $(button).fadeOut();
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        })*/
        return false;
    });
//------------------------------------------------------------------------------------------
//CLICK sur bouton éditer épisode
//NAME
$('.episodes_box').on('click', '.btn_edit_episode', function(e) {
    e.preventDefault();

    var all_form = $('.admin_ep');
    var all_name = $('.episode_name');
    var all_edit = $('.btn_edit_episode');
    var form = $(this).parents('.th_ep_name').find('.admin_ep');
    var name = $(this).parents('.th_ep_name').find('.episode_name');
    var edit = $(this);

    edit_episode_hide(form, name, edit, all_form, all_name, all_edit);
});

//CLICK SUR BOUTON RETURN
//NAME
$('.episodes_box').on('click', '.return_ep', function(e) {
    e.preventDefault();
   var form = $(this).parents('.th_ep_name').find('.admin_ep');
   var name = $(this).parents('.th_ep_name').find('.episode_name');
   var edit = $(this).parents('.th_ep_name').find('.btn_edit_episode');

   edit_episode_show(form, name, edit);
});

//CLICK SUR BTN VALIDATE (pour modifier le nom de l'épisode)
$('.episodes_box').on('click', '.validate_ep', function() {

    var id_ep = $(this).parents('.th_ep_name').find('.id_ep').val();
    var form = $(this).parents('.th_ep_name').find('.admin_ep');
    var name = $(this).parents('.th_ep_name').find('.episode_name');
    var edit = $(this).parents('.th_ep_name').find('.btn_edit_episode');
    var base_url = $('.base_url').val();
    var new_name = $(this).parents('.th_ep_name').find('.ep_name_change').val();


    $.ajax({
        url: base_url + 'Admin/executeUpdateEpisode',
        type: "post",
        data : {id_ep: id_ep, new_name: new_name},

        success: function(response) {
            if(response) {
               $(name).html(response);
               edit_episode_show(form, name, edit);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    })
    return false;
});

//-------------------------------------------------------

//CLICK sur bouton éditer épisode
//AIRDATE
$('.episodes_box').on('click', '.btn_edit_ep_airdate', function(e) {
    e.preventDefault();
    var all_form = $('.admin_ep_airdate');
    var all_date = $('.episode_airdate');
    var all_edit = $('.btn_edit_ep_airdate');
    var form = $(this).parents('.th_ep_date').find('.admin_ep_airdate');
    var date = $(this).parents('.th_ep_date').find('.episode_airdate');
    var edit = $(this);

   edit_episode_hide(form, date, edit, all_form, all_date, all_edit);
});

//CLICK SUR BOUTON RETURN
//DATE
$('.episodes_box').on('click', '.return_ep_date', function(e) {
    e.preventDefault();
   var form = $(this).parents('.th_ep_date').find('.admin_ep_airdate');
   var name = $(this).parents('.th_ep_date').find('.episode_airdate');
   var edit = $(this).parents('.th_ep_date').find('.btn_edit_ep_airdate');

   edit_episode_show(form, name, edit);
  
   return false;
});

//CLICK SUR BTN VALIDATE (pour modifier le nom de l'épisode)
$('.episodes_box').on('click', '.validate_ep_date', function() {

    var id_ep = $(this).parents('.th_ep_date').find('.id_ep').val();
    var form = $(this).parents('.th_ep_date').find('.admin_ep_airdate');
    var date = $(this).parents('.th_ep_date').find('.episode_airdate');
    var edit = $(this).parents('.th_ep_date').find('.btn_edit_ep_airdate');
    var base_url = $('.base_url').val();
    var new_date = $(this).parents('.th_ep_date').find('.ep_date_change').val();

    $.ajax({
        url: base_url + 'Admin/executeUpdateEpisode',
        type: "post",
        data : {id_ep: id_ep, new_date: new_date},

        success: function(response) {
            if(response) {
               $(date).html(response);
               edit_episode_show(form, date, edit);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
    return false;
});
//-------------------------------------------
    //supprimer épisode de la bdd
    $('.episodes_box').on('click', '.btn_delete_ep', function() {
        if(confirm('Supprimer épisode ?')) {
            var id_ep = $(this).parents('.tr_tableau_ep').find('.id_ep').val();
            var base_url = $('.base_url').val();
            var tr = $(this).parents('.tr_tableau_ep');

        $.ajax({
        url: base_url + 'Admin/executeDeleteEpisode',
        type: "post",
        data : 'id_ep=' + id_ep,

        success: function(response) {
            if(response === 'deleted') {
               $(tr).fadeOut();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });

        }
        return false;
    })



//------------------------------------------------------------------------------------------
 //SUPPRESSION DE SERIE
$('#delete_show').on('click', function() {
    msg = "delete_show";
    id_series = $('.id_series').val();
    base_url = $('.base_url').val();

    if(confirm('Supprimer la série ? Cette action est irréversible.')) {
        $.ajax({
            url: base_url + 'Admin/delete_show_from_site',
            type: "post",
            data: 'msg=' + msg + '&id_series=' + id_series,

            success: function(response) {
               if(response == 'deleted') {
                     document.location.href = base_url + 'admin';
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                }
        })
    }
})


})