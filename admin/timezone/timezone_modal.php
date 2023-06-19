<div id="timezone_setting" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Site Timezone</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <p>The timezone setting controls what timezone date/times are relative to throughout the site.</p>
                <hr>
                <form action="#" id="timezone-enabled-form">
                    <div class="form-group">
                        <select id="timezone_setting_select">
                            <?php
                            $timezone_identifiers = \DateTimeZone::listIdentifiers();
                            foreach ($timezone_identifiers as $key => $value) {
                                if($_SESSION['current_site_settings']['timezone_setting'] == $value){
                                    echo "<option value='$value' selected>$value</option>";
                                }
                                else
                                {
                                    echo "<option value='$value'>$value</option>";
                                }
                            }
                            ?>

                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn bg-primary" id="timezone-save-button">Save changes</button>
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

 
$('#timezone-save-button').on('click', function(e) {

    var timezoneSetting = $('#timezone_setting_select').val();
    if(!timezoneSetting) {
        ul.errorSwalAlert("Info Warning!", 'Must select an option');
        return false;
    }
   
    $.ajax({
        type: 'POST',
        url: 'timezone/update_timezone.php',
        data: {timezoneSetting:timezoneSetting},
        timeout: 30000,
        success: function(data) {
            if(data == 1) {
                $('#timezone_setting').modal('hide');
                $('#timezone_setting-value').text(timezoneSetting);
            } else {
                $('#timezone_setting').find('.modal-body').text('Error');
            }
        },
        error: function(data) {
            $('#timezone_setting').find('.modal-body').html(data);
        },
        fail : function() {
            $('#timezone_setting').find('.modal-body').html(data);
        }
    });

});
}) ();
</script>