<?php
    define("SAFE_PLACE", "FD622N18U8h7y4Xs76cO80QX5AfOWkvg");
    define("INC_DIR", "./includes/");
    require_once(INC_DIR."init.core.php");
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="fr" lang="fr">
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            
            <meta name="description" content="<?php __(6); ?>" />

            <title><?php __(0); ?> - <?php __(1); ?></title>

            <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
            <link rel="stylesheet" href="<?php echo THEME_DIR; ?>style.css" type="text/css" media="screen" />
            <link type="text/css" href="<?php echo THEME_DIR; ?>ui-lightness/jquery-ui-1.8.custom.css" rel="Stylesheet" />
            
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-1.4.2.min.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-ui-1.8.4.custom.min.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-hidden-title.js"></script>

            <script type="text/javascript" src="<?php echo JS_DIR; ?>class.Chrono.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>game.js"></script>
    </head>
    <body>
        <div id="fb-root"></div>

        <!-- FACEBOOK API -->
        <script type="text/javascript">
          window.fbAsyncInit = function() {
            FB.init({appId: '<?php echo FACEBOOK_APP_ID; ?>',
                    status: true,
                    cookie: true,
                     xfbml: true});
          };
          (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol + '//connect.facebook.net/fr_FR/all.js';
            document.getElementById('fb-root').appendChild(e);
          }() );
        </script>

        <!-- GOOGLE ANALYTICS -->
        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-18463169-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>

        <div id="app">

            <div id="workspace">

                <?php
                if($FB->isConnected()) :

                    // les amis de l'utilisateurs
                    $friends = $FB->getFriends();

                    // les scores enregistrés
                    $query  = "SELECT DISTINCT user_ID, U.name, S.second  ";
                    $query .= "FROM dm_score S, dm_user U ";
                    $query .= "WHERE user_ID !=". $FB->getUser()->id. " AND S.user_ID = U.ID ";
                    $query .= "GROUP BY user_ID ";
                    $query .= "ORDER BY second, name ";
                    $query .= "LIMIT 10 OFFSET 0 ";

                    $database->query($query);

                   if( $database->numrows() > 0) { ?>
                   
                        <div id="score">
                            <ul class="score-list">
                                <?php
                                        
                                    while($row = $database->fetch() ) {
                                        // cherche l'utilisateur courant dans la liste d'amis
                                        for($i = 0;
                                            $friends->data[$i]->id != $row["user_ID"]
                                         && $i <  count($friends->data);
                                            $i++) {}
                                            
                                        // c'est un ami !
                                        if( $i < count($friends->data) ) {
                                            echo "<li>";
                                            
                                                $second = $row["second"];
                                                $minute = floor( $second / 60 );
                                                $second -=  60 * $minute ;
                                                $second = ($second < 10) ? "0" . $second : $second;
                                                $minute = ($minute < 10) ? "0" . $minute : $minute;

                                                // nom
                                                 $name = explode(" ", $row["name"]);
                                                // image
                                                echo "<img src='http://graph.facebook.com/".$row["user_ID"]."/picture' alt='' />";
                                                echo "<span class='pseudo'><a target='_blank' href='http://facebook.com/profile.php?id=".$row["user_ID"]."'>".$name[0]."</a><br />".$minute.":".$second."</span>";
                                                
                                            echo "</li>";
                                        }

                                        
                                    }
                                ?>
                            </ul>
                        </div>

                    <?php }
                    
                endif; ?>

                <?php if(ECRAN == 1) : ?>
                
                    <div id="intro">
                        <?php include(INC_DIR."inc.intro.php"); ?>
                    </div>

                <?php elseif(ECRAN == 2) : ?>

                    <div id="game">
                        <?php include(INC_DIR."inc.game.php"); ?>
                    </div>

                <?php endif; ?>

            </div>

            <div id="footer">
                <?php include(INC_DIR."inc.share.php");  ?>
                <a href="<?php echo rawurlencode(DOC_URL); ?>" target="_blank"><img src="<?php echo THEME_DIR."img/owni.png" ?>" /></a>
            </div>
            
            <div class="mask">
                <div class="bg-shadow"></div>
                <div class="popup">
                    <?php if(ECRAN == 2): ?>
                        <div class="win">
                            <h2><?php __(4); ?></h2>
                            <img src="<?php echo THEME_DIR."img/btn-jouer_".LANG.".png"; ?>" alt="<?php __(3); ?>" class="jouer" style="float: right;"/>
                            <?php
                                echo "<ul class='loose-answer'>";
                                    $i = 0;
                                    foreach($atteintes as $row) {
                                        if(++$i <= 4) {
                                            echo "<li class='addTitle' title='".$row["country_name"]."'><img class='flag' src='http://flagpedia.net/data/flags/mini/".strtolower( $row["country_ISO"] ).".png' alt='' />"
                                                . $row["country_name"]
                                                .":&nbsp;"
                                                .'<img src="'.THEME_DIR.'img/couleur-'.$row["couleur"].'.png" class="couleur" alt="" />'
                                                .$row["atteinte_name"];

                                                if($row["atteinte_source"] != "" && ! is_numeric( $row["atteinte_source"] ))
                                                    echo "<br /><a href='".$row["atteinte_source"]."'>Source</a>" ;
                                                else if(is_numeric( $row["atteinte_source"] ))
                                                    echo "<br /><a href='http://fidh.org/L-obstination-du-temoignage-Rapport-2010/'>L’Obstination du témoignage</a> page <strong>".$row["atteinte_source"]."</strong>" ;



                                                if($row["atteinte_comment"] != "")
                                                    echo " : <span class='comment'>&laquo;".$row["atteinte_comment"]."&raquo;</span>";

                                            echo "</li>";
                                        }
                                    }
                                echo "</ul>";
                             ?>
                        </div>

                    <div class="loose">
                            <img src="<?php echo THEME_DIR."img/btn-jouer_".LANG.".png"; ?>" alt="<?php __(3); ?>" class="jouer" style="float: right;"/>
                            <h2><?php __(5); ?></h2>
                            <?php
                                echo "<ul class='loose-answer'>";
                                    $i = 0;
                                    foreach($atteintes as $row) {
                                        if(++$i <= 4) {
                                            echo "<li class='addTitle' title='".$row["country_name"]."'><img class='flag' src='http://flagpedia.net/data/flags/mini/".strtolower( $row["country_ISO"] ).".png' alt='' />"
                                                . $row["country_name"]
                                                .":&nbsp;"
                                                .'<img src="'.THEME_DIR.'img/couleur-'.$row["couleur"].'.png" class="couleur" alt="" />'
                                                .$row["atteinte_name"];

                                                if($row["atteinte_source"] != "" && ! is_numeric( $row["atteinte_source"] ))
                                                    echo "<br /><a href='".$row["atteinte_source"]."' target='_blank'>Source</a>" ;
                                                else if(is_numeric( $row["atteinte_source"] ))
                                                    echo "<br /><a href='http://fidh.org/L-obstination-du-temoignage-Rapport-2010/' target='_blank'>L’Obstination du témoignage</a> page <strong>".$row["atteinte_source"]."</strong>" ;
                                                    
                                                

                                                if($row["atteinte_comment"] != "")
                                                    echo " : <span class='comment'>&laquo;".$row["atteinte_comment"]."&raquo;</span>";

                                            echo "</li>";
                                        }
                                    }
                                echo "</ul>";
                            ?>

                        </div>
                     <?php endif; ?>
                </div>
            </div>
            
        </div>
    </body>
</html