//http://limitless.org/Limitless_2_2/Bootstrap%204/Template/layout_1/LTR/default/full/extra_idle_timeout.html
document.addEventListener('DOMContentLoaded', function() {

    $.sessionTimeout({
        heading: 'h5',
        title: 'Session expiration',
        message: 'Your session is about to expire. Do you want to stay connected and extend your session?',
        keepAliveUrl: 'analytics/keep_session_alive.php',
        keepAlive: true,
        //keepAliveInterval: 1000, //send ajax post every 1 second
        keepAliveInterval: 600000, //send ajax post every 10 minutes  
        redirUrl: 'index.php?logout=1',
        logoutUrl: 'index.php?logout=1',
        //warnAfter: 5000, //5 seconds
        warnAfter: 1500000, //25 minutes
        //redirAfter: 10000, //10 seconds
        redirAfter: 1560000, //26 minutes
        keepBtnClass: 'btn btn-success',
        keepBtnText: 'Extend session',
        logoutBtnClass: 'btn btn-light',
        logoutBtnText: 'Log me out',
        ignoreUserActivity: false,
    });

});