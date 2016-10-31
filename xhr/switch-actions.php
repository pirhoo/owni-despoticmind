<?php
    header('Content-Type: text/html; charset=UTF-8');
    chdir("../");

    define("SAFE_PLACE", "FD622N18U8h7y4Xs76cO80QX5AfOWkvg");
    define("INC_DIR", "./includes/");
    require_once(INC_DIR."init.core.php");

    switch ($_GET['action']):

        case "sauv_score":
                // on enregistre le score du joueur (qui vient de terminer sa partie)
                if( $FB->isConnected() ) {
                    
                    $user  = $FB->getUser();
                    $score = $_GET["score"];
                    $step = $_GET["step"];

                    echo $query = "INSERT INTO dm_score VALUES (NULL, ".$user->id.", ".$score.", ".$step.") ";
                    $database->query($query);
                    
                }
            break;

    endswitch;
?>
