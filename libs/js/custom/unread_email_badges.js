$(document).ready(function(){
    $.ajax({
        type: 'GET',
        url: 'lc_email/lc_num_new.php',
        data: [],
        timeout: 30000,
        beforeSend: function() {
            //console.log('Sending request ...');
        },
        success: function(data) {
            //console.log(data);
            //console.log(type);
                if (data >0) {
                    $('.unread-messages-badge').text(data);
                } else {
                    $('.num_new').text('what');
                }
        },
        error: function(data) {
            /* 
                TODO find out why this is comes back as a error
                eventhough it is a success on the server.
                Is the full page change causing the error?
                I think it is the badge request
            
            */
            console.log('TEST sendAjaxRequest error');
            console.log(data.statusText);
            console.log(data);
        },
        fail: function(data) {
            console.log('TEST sendAjaxRequest fail');
            console.log(data.statusText);
            console.log(data);
        }
    });  
});