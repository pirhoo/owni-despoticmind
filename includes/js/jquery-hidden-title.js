var readyToHide = true;

function addTitle(target, sharing, addClass) {

    if(target == undefined) target = $(".addTitle");
    if(sharing == undefined) sharing = false;
    if(addClass == undefined) addClass = "";

    
    $(target).each( function (index) {

        var title = $(this).attr("title");
        var href  = $(this).attr("href");

        if( title != "" && ! $(this).hasClass("titleCreated") ) {

            $(this).addClass("titleCreated");
            var count = $(".hiddenTitle").length;
            var content;

            $(this).attr("title", "");
            if(!sharing)
                content = '<span class="hiddenTitle '+addClass+'">' + title + '</span>';
            else {
                content  = '<span class="hiddenTitle sharing '+addClass+'">';

                    content += '<a href="">';
                        content += '<img alt="Sur Twitter"  src="http://owni.fr/wp-content/themes/ownibeta/images/owni/mini-twitter.png" />';
                    content += '</a>';

                    content += '<a href="http://www.facebook.com/sharer.php?t=@OWNI:%20&u='+href+'" target="_blank">';
                        content += '<img alt="Sur Facebook" src="http://owni.fr/wp-content/themes/ownibeta/images/owni/mini-fb.png" />';
                    content += '</a>';

                    content += '<a href="http://www.addtoany.com/email?linkurl='+href+'&linkname=From%20@OWNI" target="_blank">';
                        content += '<img alt="Par Email" src="http://owni.fr/wp-content/themes/ownibeta/images/owni/mini-newsletter.png" />';
                    content += '</a>';

                content += '</span>';
            }

            $(document.body).append( content);
            showTitle(count, this);
        }

    });

    $(".hiddenTitle").mouseenter(function () {
        readyToHide = false;
    });

}

function showTitle(index, trigger) {

    var title = $(".hiddenTitle:eq("+index+")");

    $(trigger).mouseenter(function () {

        $("img", title).trigger("appear");

        // positione
        var thatTop  = $(trigger).offset().top - $(title).outerHeight() - 20;
        var thatLeft = $(trigger).offset().left + 30;
        $(title).css({position: "absolute", top: thatTop, left: thatLeft});

        // affiche
        $(".hiddenTitle").stop().hide();
        $(title).stop().show();
    });

    $(trigger).mouseleave(function () {

        readyToHide = true;
        setTimeout(function () {
            if(readyToHide)
                $(title).stop().hide();
        }, 500);
    });


    $(title).mouseleave(function () {
        $(title).stop().hide();
    });


}

/* -----------------------------

.hiddenTitle {
    position:absolute;
    top:0px;
    z-index:4000;

    font-size:0.8em;

    background:#404040;
    display:block;
    color:#f2f2f2;
    padding:3px;

    box-shadow: gray 0px 3px 15px;
    -moz-box-shadow: gray 0px 3px 15px;
    -webkit-box-shadow: gray 0px 3px 15px;
    -khtml-box-shadow: gray 0px 3px 15px;

    border-radius:5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;

    display:none;

}

.hiddenTitle:before {
    content:url("./images/bottomArrow3.png");
    width:0px;
    height:0px;
    display:block;
    position:relative;
    top:-15px;
    left:5px;
}


 */