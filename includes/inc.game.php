<?php if(SAFE_PLACE != "FD622N18U8h7y4Xs76cO80QX5AfOWkvg") die();

    function troncate($str, $limit) {

        while($limit > 0 && $str[$limit-1] == " ") $limit--;

        if( strlen($str) > $limit && $limit > 3)
            $str = substr($str, 0, $limit - 3)."...";

        return $str;
    }
?>
<script type="text/javascript">
    $(function () {
        startGame();
    });
    
</script>
<div class="back-home"><a href="index.php"><img src="<?php echo THEME_DIR.'img/back-home.png'; ?>" alt="" /></a></div>

<div class="carnet">
     
    <div class="game">
        <?php
            // 4 atteinte différentes au hazard et pour des pays différents
            $query =   "SELECT  DISTINCT ( A.ID ) AS AID ,
                                C.country_name_".LANG." AS country_name ,
                                A.atteinte_name_".LANG." AS atteinte_name,
                                C.country_ISO,
                                C.ID AS CID,
                                R.atteinte_source,
                                R.atteinte_comment_".LANG."
                                    
                        FROM dm_atteinte_relationships R, dm_country C, dm_atteinte A
                        WHERE R.country_ID = C.ID
                        AND R.atteinte_ID = A.ID
                        GROUP BY AID
                        ORDER BY RAND()
                        LIMIT 6 OFFSET 0";

            $database->query($query);
            $i = 0;

            while($row = $database->fetch() ) {
                $atteintes[]= Array("country_ID"        => $row["CID"],
                                    "country_name"      => $row["country_name"],
                                    "atteinte_ID"       => $row["AID"],
                                    "atteinte_name"     => $row["atteinte_name"],
                                    "country_ISO"       => $row["country_ISO"],
                                    "atteinte_source"   => $row["atteinte_source"],
                                    "atteinte_comment"  => $row["atteinte_comment_".LANG],
                                    "couleur"           => ++$i);
            }

            $atteintes_user = $atteintes;
            shuffle($atteintes); // mélange les atteintes
            $i = 0;

            echo "<ul class='flags'>";
                foreach($atteintes as $row) {
                    if(++$i <= 4)
                        echo "<li class='addTitle' title='".$row["country_name"]."'><img src='http://flagpedia.net/data/flags/mini/".strtolower( $row["country_ISO"] ).".png' alt='' /></li>";
                }
            echo "</ul>";


            ?>

            <script type="text/javascript"><?php
                    $i = 0;
                    echo "var answer = new Array(";
                        foreach($atteintes as $row)
                            if($i < 4)
                                echo ($i++ > 0) ? ",".$row["couleur"] : $row["couleur"];
                    echo ");"
                ?>
            </script>

            <table id="grille">

               <?php for($step = 10; $step >= 1 ; $step-- ): ?>
                    <tr>
                        <td class="number"><?php echo $step; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="check">
                            <span class="spot"></span>
                            <span class="spot"></span><br />
                            <span class="spot"></span>
                            <span class="spot"></span>
                        </td>
                        <td class="next-step-btn">ok</td>
                    </tr>
                <?php endfor; ?>
            </table>
            <br />
            <img src="<?php echo THEME_DIR."img/despotic-mind.png"; ?>" alt="DespoticMind" class="title" />
    </div>
</div>

<div class="montre">
    00:00
</div>

<div class="blocnote">
    <ul>
        <?php
            foreach($atteintes_user as $row) {
                echo '<li title="'.$row["atteinte_name"].'" class="addTitle">
                        <img src="'.THEME_DIR.'img/couleur-'.$row["couleur"].'.png" alt="" />
                        <span class="marquee">'.
                            troncate($row["atteinte_name"], 18).
                        '</span>
                      </li>';
            }
        ?>
    </ul>
</div>