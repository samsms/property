//var approot="http://swimafrica.net/property/logout.php";

function root() {
    var scripts = document.getElementsByTagName( 'script' ),
        script = scripts[scripts.length - 1],
        path = script.getAttribute( 'src' ).split( '/' ),
        pathname = location.pathname.split( '/' ),
        notSame = false,
        same = 0;

    for ( var i in path ) {
        if ( !notSame ) {
            if ( path[i] == pathname[i] ) {
                same++;
            } else {
                notSame = true;
            }
        }
    }
    return location.origin + pathname.slice( 0, same ).join( '/' );
}


    var approot =root()+"/logout.php";

idleTime = 0;
$(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement,60000); // 1 minute

    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime >10) { // 10 minutes delay
         window.location.href =approot;
    }
}


