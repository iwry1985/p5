<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


 //création de TOKEN
function create_confirmation_token() {
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, 60)), 0, 60);
}

//---------------------------------------------------------------------------

function mail_inscription($user, $token) {
    return $mail = '
    <html>
        <head>
            <title>SeriesDOM - Confirmation inscription</title>
            <meta charset="utf-8"/>
        </head>

        <body>
            <font-color="000000">
                <div align="center">
                    <table width="600px">
                        <tr>
                            <td background="http://seriesdom.com/web/img/logo/logo_mail.jpg"></td>
                        </tr>
                            <td><br/>
                                <div align="center"> Salut <b>'.$user['username'].'</b>, </div><br/>
                                Merci d\'avoir rejoint SeriesDOM !<br/><br/>
                                Afin de confirmer ton inscription, clique sur le lien suivant :<br/>
                                <a href="'.base_url('inscription/confirm/'.$user['id'].'/'.$token).'">seriesdom/inscription/confirm/'.$user['id'].'/'.$token.'</a>
                                <br/><br/><br/>

                                A bientôt sur <a href="'.base_url().'">SeriesDOM.com</a> ! <br />
                            </td>
                        </tr>
                        <tr>
                            <font-size="2">
                                Ceci est un email automatique, merci de ne pas y répondre.
                            </font>
                        </tr>
                    </table>
                </div>
            </font>
        </body>
    </html>';
}

//--------------------------------------------------------
function header_inscription_mail() {
    $header = "MIME-Version: 1.0\r\n";
    $header.='From:"SeriesDom.com"<seriesdom@gmail.com>'."\n";
    $header.='Content-Type:text/html; charset="utf-8"'."\n";
    $header.='Content-Transfert-Encoding: 8bit';

    return $header;
}

//---------------------------------------------

function mail_recup_mdp($user, $token) {
    return $mail = '
    <html>
        <head>
            <title>SeriesDOM - Réinitialisation du mot de passe</title>
            <meta charset="utf-8"/>
        </head>

        <body>
            <font-color="000000">
                <div align="center">
                    <table width="600px">
                        <tr>
                            <td background="http://seriesdom.com/web/img/logo/logo_mail.jpg"></td>
                        </tr>
                            <td><br/>
                                <div align="center"> Bonjour <b>'.$user['username'].'</b>, </div><br/>
                                Une demande de réinitialisation du mot de passe a été effectuée pour cette adresse mail.<br/>
                                Si tu n\'est pas à l\'origine de cette demande, ignore l\'étape suivante.<br/><br/>
                                <br/>
                                Afin de réinitialiser ton mot de passe sur SeriesDOM, clique sur ce lien:<br/>
                                <a href="'.base_url('recupmdp/reinitialisation_mdp/'.$user['id'].'/'.$token).'">seriesdom/recupmdp/reinitialisation_mdp/'.$user['id'].'/'.$token.'</a>
                                <br/><br/><br/>

                                A bientot sur <a href="'.base_url().'">SeriesDOM.com</a> ! <br />
                            </td>
                        </tr>
                        <tr>
                            <font-size="2">
                                Ceci est un email automatique, merci de ne pas y repondre.
                            </font>
                        </tr>
                    </table>
                </div>
            </font>
        </body>
    </html>';
}