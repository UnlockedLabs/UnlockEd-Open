/* 
TODO: refactor this file. It would be wise to make a
js object out of this file and call the objects
methods after its instantiated. The encapsulation
will help us avoid variable conflicts as well.
*/

document.addEventListener('DOMContentLoaded', function () {
    /* 
        TODO: new_badge is being called when index.php loads.
        index.php does not have the new_badge display
        which means are are making an unnecessary server hit.
    */ 
    new_badge();
});

// UPDATES NAVBAR AND EMAIL SIDEBAR WHEN NEW MESSAGES ARRIVE
function new_badge() {
    
    sendAjaxRequest('./lc_num_new.php', '', 'new_badge');

    setTimeout(function () {
        new_badge();
    }, 10000);
    
    /*  
        TODO: how often should we hit the server to count unread emails? 
        I am leaving this at 10 sec for testing purposes.
    */

}

// SELECT/MARK BUTTONS FUNCTIONALITY
function selectMessage(messageId) {
    $("#checkbox" + messageId).toggleClass('icon-checkbox-checked icon-checkbox-unchecked');
}

function selectAll() {
    $('.icon-checkbox-unchecked').removeClass('icon-checkbox-unchecked').addClass('icon-checkbox-checked');
}

function selectUnread() {
    $('.unread').find('i.icon-checkbox-unchecked').toggleClass('icon-checkbox-unchecked icon-checkbox-checked');
}

function selectRead() {
    $('.read').find('i.icon-checkbox-unchecked').toggleClass('icon-checkbox-unchecked icon-checkbox-checked');
}

function clearAll() {
    $('.icon-checkbox-checked').removeClass('icon-checkbox-checked').addClass('icon-checkbox-unchecked');
}

// MARK EMAIL AS READ
function markRead() {
  
    $('.icon-checkbox-checked').parents('tr').removeClass('unread').addClass('read');
    var numMsgs = $('.icon-checkbox-checked').parents('tr').length;
    for (m = 0; m < numMsgs; m++) {
        var id = $('.icon-checkbox-checked').parents('tr')[m];
        id = id.attributes.id.nodeValue;
        sendAjaxRequest('lc_markread.php', {id:id});
    }

}

// MARK EMAIL AS UNREAD
function markUnread() {

    $('.icon-checkbox-checked').parents('tr').removeClass('read').addClass('unread');
    var numMsgs = $('.icon-checkbox-checked').parents('tr').length;
    for (m = 0; m < numMsgs; m++) {
        var id = $('.icon-checkbox-checked').parents('tr')[m];
        id = id.attributes.id.nodeValue;
        sendAjaxRequest('lc_markunread.php', {id:id});
    }

}

// SEND TO TRASH BUTTON
function trashMessage() {

    $('.icon-checkbox-checked').parents('tr').hide();
    var numMsgs = $('.icon-checkbox-checked').parents('tr').length;
    for (m = 0; m < numMsgs; m++) {
        var id = $('.icon-checkbox-checked').parents('tr')[m];
        id = id.attributes.id.nodeValue;
        console.log(id);
        sendAjaxRequest('lc_trash_message.php', {id:id});
    }
}

function ajaxTrash(id) {
    sendAjaxRequest('lc_trash_message.php', {id:id});
}

function deleteMessage() {
    
    $('.icon-checkbox-checked').parents('tr').hide();
    var numMsgs = $('.icon-checkbox-checked').parents('tr').length;
    for (m = 0; m < numMsgs; m++) {
        var id = $('.icon-checkbox-checked').parents('tr')[m];
        var sender_ids = id.attributes.value.nodeValue; 
        id = id.attributes.id.nodeValue;
        sendAjaxRequest('lc_delete_message.php',{id:id,sender_ids:sender_ids});
    }
}

//SEND TRASHED MESSAGE BACK TO INBOX
function sendToInbox() {

    $('.icon-checkbox-checked').parents('tr').hide();
    var numMsgs = $('.icon-checkbox-checked').parents('tr').length;
    for (m = 0; m < numMsgs; m++) {
        var id = $('.icon-checkbox-checked').parents('tr')[m];
        id = id.attributes.id.nodeValue;
        console.log(id);
        /* 
            TODO: this method gets called one time per email.
            It would be better to send an array of email ids
            and do one db transaction.
        */
        sendAjaxRequest('lc_send_inbox.php', {id:id})
    }
}

// SEND AN EMAIL
function sendEmail(messageId){

    var userId = $('#user_id').val();
    var userName = $('#user_name').val();
    var subject = $('#subject').val();
    var message = CKEDITOR.instances['editor-full'].getData();
    var recipientIdsString = $('#recipient').val().toString();
    var recipientNameArray = [];
    
    $('.select2-selection__rendered').children('li').each(function() {
        if (this.title.trim()) {
            recipientNameArray.push(this.title);
        }
    });
    
    var recipientNameString = recipientNameArray.toString();

    //validation data
    if (!message.trim()) {
        //TODO add a better alert, NOTY or something
        alert('must supply a message');
        return false;
    } else if (!recipientNameString.length) {
        alert('must select a recipient');
        return false;
    }

    var data = {userId:userId, userName:userName, recipientIdsString:recipientIdsString,
                recipientNameString:recipientNameString, messageId:messageId,
                subject:subject, message:message};

    sendAjaxRequest('lc_send_mail.php', data);

}

// SAVE AN EMAIL AS DRAFT
function saveEmail(messageId) {

    var userId = $('#user_id').val();
    var userName = $('#user_name').val();
    var subject = $('#subject').val();
    var message = CKEDITOR.instances['editor-full'].getData();
    var recipientIdsString = $('#recipient').val().toString();
    var recipientNameArray = [];
    
    $('.select2-selection__rendered').children('li').each(function() {
        if (this.title.trim()) {
            recipientNameArray.push(this.title);
        }
    });
    
    var recipientNameString = recipientNameArray.toString();

    //validation data
    if (!message.trim()) {
        //TODO add a better alert, NOTY or something
        alert('must supply a message');
        return false;
    } else if (!recipientNameString.length) {
        alert('must select a recipient');
        return false;
    }

    var data = {userId:userId, userName:userName, recipientIdsString:recipientIdsString,
                recipientNameString:recipientNameString, messageId:messageId,
                subject:subject, message:message};

    sendAjaxRequest('lc_save_mail.php', data);

}

function sendAjaxRequest(url, data, type) {

   $.ajax({
        type: 'GET',
        url: url,
        data: data,
        timeout: 30000,
        beforeSend: function() {
            //console.log('Sending request ...');
        },
        success: function(data) {
            //console.log(data);
            //console.log(type);
            if (type == 'new_badge') {
                if (data >0) {
                    $('.num_new').html(`<span class="badge badge-pill bg-warning-400 ml-auto ml-md-0">${data}</span>`);
                } else {
                    $('.num_new').html('');
                }
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

}