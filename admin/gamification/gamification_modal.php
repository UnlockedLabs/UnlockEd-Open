<div id="gamification_enabled" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gamification Enabled</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <p>Gamification is the function that provides coins, levels, and other similar motivators as a user completes lessons and courses. This setting allows you to turn this sit function on and off.
                <hr>
                <form action="#" id="gamification-enabled-form">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="gamification" value="true" type="radio">
                                Turn Gamification ON
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" name="gamification" value="false" type="radio">
                                Turn Gamification OFF
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-primary" id="gamification-enabled-button">Save changes</button>
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

 
$('#gamification-enabled-button').on('click', function(e) {

    var gamificationEnabled = $('input[name=gamification]:checked').val();

    if(!gamificationEnabled) {
        ul.errorSwalAlert("Info Warning!", 'Must select an option');
        return false;
    }
   
    $.ajax({
        type: 'POST',
        url: 'gamification/update_gamification.php',
        data: {gamificationEnabled:gamificationEnabled},
        timeout: 30000,
        success: function(data) {
            if(data == 1) {
                $('#gamification_enabled').modal('hide');
                $('#gamification_enabled-value').text(gamificationEnabled);
            } else {
                $('#gamification_enabled').find('.modal-body').text('Error');
            }
        },
        error: function(data) {
            $('#gamification_enabled').find('.modal-body').html(data);
        },
        fail : function() {
            $('#gamification_enabled').find('.modal-body').html(data);
        }
    });

});
}) ();
</script>