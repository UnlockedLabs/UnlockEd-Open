<div id="email_enabled" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Enabled</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <p>This setting controls whether or not there will be email capability throughout the site.</p>
                <hr>

                <form action="#" id="email-enabled-form">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="email" value="true" type="radio">
                                Turn Email ON
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="email" value="false" type="radio">
                                Turn Email OFF
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-primary" id="email-enabled-button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
/* 
Immediately invoked function (IIFE).
Executes as soon as js sees it.
Runs in it own scope.
*/

(function(){

$('#email-enabled-button').on('click', function(e) {

    var emailEnabled = $('input[name=email]:checked').val();

    if(!emailEnabled) {
        ul.errorSwalAlert("Info Warning!", 'Must select an option');
        return false;
    }
   
    $.ajax({
        type: 'POST',
        url: 'email/update_email.php',
        data: {emailEnabled:emailEnabled},
        timeout: 30000,
        success: function(data) {
            if(data == 1) {
                $('#email_enabled').modal('hide');
                $('#email_enabled-value').text(emailEnabled);
            } else {
                $('#email_enabled').find('.modal-body').text('Error');
            }
        },
        error: function(data) {
            $('#email_enabled').find('.modal-body').html(data);
        },
        fail : function() {
            $('#email_enabled').find('.modal-body').html(data);
        }
    });

});
}) ();
</script>