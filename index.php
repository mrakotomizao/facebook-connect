<?php
session_start();
use App\Facebook\FacebookConnect;
require 'app/Facebook/constants.php';
require 'vendor/autoload.php';

$connect = new FacebookConnect(APP_ID, APP_SECRET);

$user = $connect->connect(USER_HOST);
echo "here";
if(is_string($user)){

    echo '<a href="'.$user.'">Se connecter avec facebook</a>';

}else{
    echo "hello there ";
    var_dump($user);

}


    //on envoi un lien de connexion
    //l'url qui permet de se connecter avec facebook (et je veux en plus récupérer l'email)
    //les permissions sont a mettre dans un tableau puis à mettre en paramètre de getLoginUrl();
    //$loginUrl = $helper->getLoginUrl(['email']);
    //echo  '<a href="'.$loginUrl.'">Connexion avec facebook</a>';
