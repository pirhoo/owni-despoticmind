var stepGame = 9;
var current_num_color;
var chrono = new Chrono( drawChron );

$(function () {

    // -----------
    // Lance la partie
    // ------------------------------------
    $(".jouer").click(function () {
        goTo(2);
    });

    
    // -----------
    // Disparition de la pop
    // ------------------------------------
    /*
    $(".bg-shadow").click(function () {
        $(".popup").stop().hide("bounce", 300);
        $(".mask").stop().fadeOut(700);
    });*/

    centerAPP();
});

$(window).resize(function () { centerAPP(); });

function centerAPP()  {
    // -----------
    // Centre l'APP'
    // ------------------------------------
    var x = ( $(window).width() > 800 )  ? $(window).width() / 2 - 400 : 0;
    var y = ( $(window).height() > 600 ) ? $(window).height() / 2 - 300: 0;

    $("#app").css({
        left:x,
        top:y
    });
}

function drawChron() {
    var second = chrono.time;
    var minute = Math.floor( second / 60 );
    second -=  60 * minute ;
    second = (second < 10) ? "0" + second: second;
    minute = (minute < 10) ? "0" + minute: minute;
    
    $(".montre").html(minute + ":" + second);
   
}

function goTo(ecran) {
    document.location = "index.php?e="+ecran;
}

function showPopup() {
    $(".mask").stop().fadeIn(700);
    $(".popup").stop().show("bounce", 300);
}

function startGame() {


    // -----------
    // Initialise la grille
    // ------------------------------------
    initGame();
    doGameEvents();

    // -----------
    // Ajoute des infobulles jolies
    // ------------------------------------
    addTitle($(".blocnote .addTitle"));
    addTitle($(".addTitle", ".flags"), false, "hiddenFlag");


    // bouton pour jouer
    $("td.next-step-btn").click(function () {
        if( $(this).parent().hasClass("current") && $(this).parent().hasClass("done"))
            nextStep();
    });


    // -----------
    // Lance le chrono
    // ------------------------------------
    chrono.play();

}



function initGame() {

    // -----------
    // affiche ou non le bouton pour passer à l'étape suivante
    // ------------------------------------

    var current = $(".couleur","tr.current");

    if( current.length == 4)
        $("tr.current").addClass("done");
    else
        $("tr.current").removeClass("done");

    // -----------
    // initialise les lignes
    // ------------------------------------

    $("tr", "#grille").each(function (index) {
        if( index < stepGame ) {
            $(this).removeClass("actif");
        } else {
            $(this).addClass("actif");
            if( index == stepGame)
                $(this).addClass("current");
            else
                $(this).removeClass("current");
        }
    });
    
}

function doGameEvents() {


    // -----------
    // Dépot de couleur sur la grille
    // ------------------------------------

    // on enlève ts les évènements déjà existants
    $("td:not(.number):not(.check):not(.next-step-btn)", "tr").unbind();

    $("li", ".blocnote").draggable({
        cursor: 'move',
        helper: "clone",
        opacity: 0.8,
        revert: 'invalid',
        cursorAt: {top: 15, left: 90},
        start: function () {
            current_num_color = $(this).index() + 1;
        }
    })

    // évènement quand on lache l'élément'
    $("td:not(.number):not(.check):not(.next-step-btn)", "tr.current").droppable({
            hoverClass: 'hover',
            drop: function(event, ui) {
                    $(this).html( "<span class='couleur couleur-"+current_num_color+"'>"+current_num_color+"</span>" );
                    initGame();
            }
    // ou quand on clique
    }).click(function () {

        if( $(this).text() == "" )
            $(this).html( "<span class='couleur couleur-1'>1</span>" );
        else if( $(this).text() < 6)
            $(this).html( "<span class='couleur couleur-"+ ( $(this).text()/1 + 1 ) +"'>"+ ( $(this).text()/1 + 1 ) +"</span>" );
        else
            $(this).html( "" );

        initGame();
    });
    
}

function nextStep() {

    var nbp = coulPlaces();
    
    if(nbp.length == 4) {

        // gagné !
        chrono.pause();

        $(".win").show();
        showPopup();

        // enregistre les scores
        $.ajax({ url: 'xhr/switch-actions.php?action=sauv_score&score='+chrono.time+'&step='+(10-stepGame) });

    } else if(stepGame == 0) {

        // perdu !
        chrono.pause();

        $(".loose").show();
        showPopup();

    } else {

        var cE = coulExist().length;
        var cP = coulPlaces().length;
        
        // met à jour les indices (spots)
        var i = 0;
        for(var j=0; j < cE;  j++)
            $(".spot:eq("+(i++)+")", "tr.current").addClass("exist")
        for(var j=0; j < cP; j++)
            $(".spot:eq("+(i++)+")", "tr.current").addClass("ok")

        // ajoute des infobulles
        var title = "";

        if(cE == 1)
            title += cE + " mal placé ";
        else if(cE > 1)
            title += cE + " mal placés ";

        if(cP == 1)
            title += cP + " bien placé ";
        else if(cP > 1)
            title += cP + " bien placés ";

        var superflus = 4 - (cP + cE);
        
        if(superflus > 0)
            title += ( (cP > 0 || cE > 0) ? " et " : "") + superflus + " superflus ";
        
        
        $(".check", "tr.current").attr("title", title);
        addTitle($(".check", "tr.current"), false, "hiddenCheck");

        
    }

    stepGame--;
    initGame();
    doGameEvents();

}


function coulPlaces() {

    var couleurs = new Array();
    var current = $("tr.current");
    
    $(".couleur", current).each(function (index) {
       if( $(this).text() == answer[index])
           couleurs.push(answer[index]);
    });

    return couleurs;
}

function coulExist() {
    
    var couleurs = new Array();
    var current = $("tr.current");

    $(".couleur", current).each(function (index) {
        
        var coul = $(this).text();

        if( ! inArray(couleurs, coul) // si on l'a pas déjà compté
         &&   inArray(answer,   coul) // si c'est une couleur de la réponse
         &&   (coul != answer[index]) // si la couleur n'est pas bien placée
         ) {

                 couleurs.push(coul); // alors on l'ajoute au tableau
         }
    });

    return couleurs;
}

function inArray(arr, val) {
    for(var i = 0; arr[i] != val && i < arr.length; i++) {}
    return (i < arr.length);
}