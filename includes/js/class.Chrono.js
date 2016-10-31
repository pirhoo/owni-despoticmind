

function Chrono(fnc) {

    // public
    // ----------------

    this.time = 0;
    // 0 pour pause, 1 pour play
    this.statut = 0;
    
     // une fonction a déclencher à chaque seconde
    if(fnc != undefined)
        this.fnc = fnc;
    else 
        this.fnc = null;

}


Chrono.prototype.loop = function (that) {

    ++that.time;
    
    if(that.statut == 1) {

        setTimeout( function () { that.loop(that) }, 1000);
        if(that.fnc != null)
            that.fnc();
        
    }
};

Chrono.prototype.play = function () {
    this.statut = 1;
    var that = this; // dans le setTimeout, le this ne réfère plus à l'objet courant mais à la fenêtre'
    setTimeout( function () { that.loop(that) }, 1000);
};

Chrono.prototype.pause = function () {
    this.statut = 0;
};

Chrono.prototype.stop  = function () {
    this.statut = 0;
    this.time   = 0;
};


