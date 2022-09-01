//var approot="http://swimafrica.net/property/logout.php";
if($_SERVER['REMOTE_ADDR']=="127.0.0.1"){
var approot ="http://localhost/property-rivercourt/logout.php";
}
else{
    var approot ="https://rivercourt.techsavanna.technology/logout.php";
}
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


