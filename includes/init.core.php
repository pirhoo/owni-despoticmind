<?php
    session_start();
    if(SAFE_PLACE != "FD622N18U8h7y4Xs76cO80QX5AfOWkvg") die();
    
    require_once(INC_DIR."func.lang.php");
    require_once(INC_DIR."class.Mysql.php");
    require_once(INC_DIR."class.FBconnect.php");

    // si une langue est demandée, on la défini
    if(!isset($_GET['lang']))
        defineLang();
    else
        defineLang($_GET['lang']);

    // ecran
    if(!isset($_GET['e']))
        define("ECRAN", 1);
    else
        define("ECRAN", $_GET['e']);

    // emplacements
    define("LANG_DIR", INC_DIR."lang/");
    define("THEME_DIR", INC_DIR."style/");
    define("JS_DIR", INC_DIR."js/");

    //facebook
    define('FACEBOOK_APP_ID', '133546783338824');
    define('FACEBOOK_SECRET', '3260b8967d8b2e3b9b3b21b8fb66da7a');
    /* @var $FB FBconnect */
    $FB = new FBconnect(FACEBOOK_APP_ID, FACEBOOK_SECRET);
    global $FB;

    
    // Force une pseudo connexion à l'aide d'un token personalisé...
    // Pour trouver un token http://developers.facebook.com/docs/api

    $FB->startSimulation("2227470867|2.Dk59gSyjTbE9IDInfLmD2Q__.3600.1284487200-686299757|i1qNRYeQs91MNcBwj_bTEdASlUE");
    
    // MySQL
    /* @var $database dbMySQL */
    $database = new dbMySQL("DESPOTIC-MIND", "root", "rootmdp", "localhost");
    //$database = new dbMySQL("appowni", "appowni", "pheePh3Ienga", "localhost");
    global $database;
    
    $database->connection();


?>
