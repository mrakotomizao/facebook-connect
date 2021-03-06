<?php
/**
 * Created by PhpStorm.
 * User: aelpeacha
 * Date: 08/04/15
 * Time: 22:24
 */

namespace App\Facebook;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class FacebookConnect {
    private $session;
    private $helper;

    function __construct($appid, $appsecret){
        FacebookSession::setDefaultApplication($appid, $appsecret);
        $this->session = "";
    }

    public  function connect($redirectUrl){

        $this->helper = new FacebookRedirectLoginHelper($redirectUrl);
        //si la var session existe et que l'on un un fb token en session
        if(isset($_SESSION) && isset($_SESSION['fb_token'])){
            //on récupère la session active
            echo "j'ai une session";
            $this->session = new FacebookSession($_SESSION['fb_token']);

        }else{

            //on récupère le token de connexion
            $this->session = $this->helper->getSessionFromRedirect();

        }

    }
public function getResponse(){

    //si on a une session
    if($this->session){
        echo "got session";
        try{
            //génération du token
            echo "\n trying too connect";
            $_SESSION['fb_token'] = $this->session->getToken();
            var_dump($this->session);

            //si on a bien notre token de connexion on peut commencer à faire des requetes avec la classe facebookrequest
            $request = new FacebookRequest($this->session, 'GET', '/me');
            //on recupère un objet graph user
            $response = $request->execute()->getGraphObject('Facebook\GraphUser');
            //var_dump($response);

            //facebook id
            $facebookId = $response->getId();
            echo "connection done";
            //image profil du user
            /*$imgProfile = '<img src="//graph.facebook.com/'.$facebookId.'/picture">';
            echo $imgProfile;*/


            //si le user a refuser la permission de recupération du mail
            if($response->getEmail() === null){
                throw new Exception('l\'email n\'est pas disponible');
            }

            return $response;

        }catch (Exception $e){
            echo "problem with the token";
            unset($_SESSION['fb_token']);

            return $this->helper->getReRequestUrl(['email']);

        }


        //facebook id
        //$facebookId = $response->getId();

        //image profil du user
        /*$imgProfile = '<img src="//graph.facebook.com/'.$facebookId.'/picture">';
        echo $imgProfile;*/

        //requete sql
        //si l'id est en bdd : SELECT * FROM users WHERE fb_id = $facebookId
        //sinon : INSERT INTO users SET fb_id = $facebookId, fb_firstname = $response->getFirstName()


    }else{
        echo "nothing";
        return $this->helper->getReRequestUrl(['email']);

    }
}
}