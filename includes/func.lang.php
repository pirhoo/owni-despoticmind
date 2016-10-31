<?php
    
    function __($index, $display = true) {

        // variable static pour ne pas charger le fichier 2 fois
        static $file;

        // on a pas encore chargé le fichier
        if( empty($file) ) {
            
            $file = Array();
            // ouvertue du fichier
            $lines = file(LANG_DIR.LANG.'.lang');

            // lecture du fichier ligne par ligne
            foreach ($lines as $lineNumber => $lineContent)
                    $file[] = $lineContent;
        }

        if($display) echo $file[$index];
        
        return $file[$index];
            
    }


    function defineLang($lang = null) {

        // langue disponible
        $lang_dispo = Array("FR_fr");

        
        if($lang == null) {

            if(isset($_SESSION['lang']) && @in_array($lang_dispo, $_SESSION['lang']))
                define("LANG", $_SESSION['lang']);
            else
                define("LANG", "FR_fr");

        } else {

            if( ! @in_array($lang_dispo, $lang) )
                $lang = "FR_fr";

            define("LANG", $lang);
            $_SESSION['lang'] = $lang;
            
        }
    }

?>
