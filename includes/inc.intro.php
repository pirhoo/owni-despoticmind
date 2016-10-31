<?php if(SAFE_PLACE != "FD622N18U8h7y4Xs76cO80QX5AfOWkvg") die(); ?>

<div class="carnet">
    <div class="game">
        <img src="<?php echo THEME_DIR."img/despotic-mind.png"; ?>" alt="DespoticMind" class="title" />
        <div class="regles">
            <p><?php __(6); ?></p>
            <p><?php __(7); ?></p>
            <p><?php __(8); ?></p>

            <ul class="legend">
                <li><span class="spot ok"></span><?php __(9); ?></li>
                <li><span class="spot exist"></span><?php __(10); ?></li>
                <li><span class="spot"></span><?php __(11); ?></li>
            </ul>
        </div>

        <?php if (! $FB->isConnected() ) { ?>
            <div class="facebook_connect">
                    <p><?php __(3); ?></p>
                    <fb:login-button perms="email" onlogin="goTo('1&fb')"></fb:login-button>
            </div>
        <?php } else if(isset($_GET["fb"])) {
                //<!-- Si Facebook connect vient d'être autorisé -->
                $user = $FB->getUser();

                // regarde si l'utilisateur existe déjà dans la base
                $query = "SELECT ID FROM dm_user WHERE ID='".$user->id."'";
                $database->query($query);

                // si non, on l'insère
                if(! $database->numrows() || $database->numrows() == 0) {
                        $query = "INSERT INTO dm_user (`ID`, `name`, `email`) VALUES (".$user->id.", '".$user->name."', '".$user->email."')";
                        $database->query($query);
                }

        } ?>

        <img src="<?php echo THEME_DIR."img/btn-jouer_".LANG.".png"; ?>" alt="<?php __(3); ?>" class="jouer" />
    </div>
</div>

<div class="montre">
    00:00
</div>

<div class="blocnote">
</div>

