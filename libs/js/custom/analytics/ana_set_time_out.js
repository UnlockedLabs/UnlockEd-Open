
/* 
window.addEventListener("beforeunload", function (e) {
    var confirmationMessage = "\o/";
  
    (e || window.event).returnValue = confirmationMessage; //Gecko + IE
    return confirmationMessage;                            //Webkit, Safari, Chrome
  });

 */
window.addEventListener("beforeunload", function (e) {

    //e.preventDefault();


    //alert('what do you want?');
});



//ini_set('session.gc_maxlifetime', 60*60*22); //22h
//echo ini_get("session.gc_maxlifetime"); 

    //TODO ajax up and call destroy session
    //use a setTimeout, call server every 5 minutes
    //maybe

