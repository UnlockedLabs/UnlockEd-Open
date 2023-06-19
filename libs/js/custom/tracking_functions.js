function updateMediaProgress(dataObj) {

    
    /* 
    FIXME: if the teacher changes the media to required while a student is listening to/watching the media,
    then the update that was made in db.progress_media (when the teacher clicked the required button)
    will be chaged back to what is was before due to the dataObj having the previous required value.

    If we are going to use the required status in our course progress tracking then we need to ensure
    the db.media_progress.required does not get set back to the old value in dataObj. Again, this will
    only happen when a student is enaging media and the teacher changes its status while the student is active
    with the media.

    Bill suggests checking db.media.requried when the video completes. This will work.
    */

    $.ajax({
        type: 'GET',
        url: 'track_progress/update_media_progress.php',
        data: dataObj,
        timeout: 30000,
        success: function(data) {
            console.log('Media updated.');
        },
        error: function(data) {
            console.log(data.statusText);
        },
        fail: function(data) {
            console.log(data.statusText);
        }
    });

}


function updateQuizProgress(course_id, lesson_id, student_id, quiz_id, quiz_name) {

    var dataObj = {
        course_id: course_id,
        lesson_id: lesson_id,
        student_id: student_id,
        media_id: quiz_id,    // TEAM: can we use a field name more inclusive than "media_id"?
        file_location: 'n/a',
        file_type: 'n/a',
        file_name: quiz_name,
        duration: 0,
        current_pos: 0,
        completed: 1,
        reflection: '',
        deleted: 0,
        required: 0,
    }
    
    $.ajax({
        type: 'GET',
        url: 'track_progress/update_media_progress.php',
        data: dataObj,
        timeout: 30000,
        error: function(data) {
            console.log(data.statusText);
        },
        fail: function(data) {
            console.log(data.statusText);
        }
    }).then(function() {                
        // show the completion on the lesson's page
        $('#quiz-completed-'+ quiz_id).html('<i class="icon-checkmark4 ml-2 text-success"> Passed!</i>');
    });

}

function pauseRunningMedia(playerId) {

    $('audio, video').each(function(){
        if (playerId != this.id && this.id != "confetti") {
            this.pause();
        }
    });

}

function playVideoWithTracking(course_id, lesson_id, media_id, src_path, student_id, display_name, required) {

    var src_path = decodeURIComponent((src_path+'').replace(/\+/g, '%20'));
    var display_name = decodeURIComponent((display_name+'').replace(/\+/g, '%20'));
    var student_id = decodeURIComponent((student_id+'').replace(/\+/g, '%20'));

    //update navigation header information
    $elem = $('#lc-navigation-header');
    $elem.find('.media-name').html('<i class="icon-play"></i> '+display_name).slideDown();

    /* 
        TODO: we need to find out if the previously clicked video
        continues to download when the user clicks a new video.
        If it does then how much unnecessary demand will this
        put on our server?

        see http://localhost:8081/stackoverflow/4071872
     */
    
    //get video tracking data
    $.ajax({
        type: 'GET',
        url: 'track_progress/get_media_data.php',
        data: {media_id:media_id},
        timeout: 30000,
        success: function(data) {

            var last_update_pos = 0;

            //send an update to the server every updateSecs
            var updateSecs = 60.0;

            var dataObj = {
                course_id: course_id,
                lesson_id: lesson_id,
                student_id: student_id,
                media_id: media_id,
                file_location: src_path,
                file_type: 'video',
                file_name: display_name,
                duration: 0,
                current_pos: 0,
                completed: 0,
                reflection: '',
                //TODO: when the media file gets deleted we need to set this value to 1, (when entire lesson is deleted as well)
                deleted: 0,
                required: required,
            }
            dataParsed = JSON.parse(data);

            //a media_progress record exists at this point, set needed properties
            if(dataParsed.isData != 'false') {
                dataObj.duration = dataParsed.duration;
                dataObj.current_pos = dataParsed.current_pos;
                dataObj.completed = dataParsed.completed;
                dataObj.reflection = dataParsed.reflection;
                dataObj.deleted = dataParsed.deleted;
                dataObj.required = dataParsed.required;
                last_update_pos = dataParsed.current_pos;
            }

            //get video player and set its src path
            var videoPlayer = document.getElementById('videoPlayer-'+lesson_id);
            videoPlayer.style.display='';
            videoPlayer.setAttribute('src', src_path);
 
            videoPlayer.ontimeupdate=function(){

                /* 
                    only update the db if
                        the video has progressed updateSecs
                        is not completed
                        is not the result user seeking
                 */

                if(videoPlayer.currentTime-last_update_pos > updateSecs && dataObj.completed == 0 && !videoPlayer.seeking) {
                    //current_pos and duration come in as floats. Round down to ints for the db
                    dataObj.duration=Math.round(videoPlayer.duration);
                    dataObj.current_pos=Math.round(videoPlayer.currentTime);
                    updateMediaProgress(dataObj);
                    last_update_pos = dataObj.current_pos;
                }

            }

            videoPlayer.onseeking=function(){


                /*
                    If the video is required the user cannot seek forward until they complete the video.
                */

                if (videoPlayer.currentTime > last_update_pos && dataObj.required == 1 && dataObj.completed == 0) {
                    console.log("Seeking is disabled.");
                    videoPlayer.currentTime = last_update_pos;
                  }
              
            }

            //called when the video ends
            videoPlayer.onended=function(){

                //skip completion if user is seeking
                if(!videoPlayer.seeking && dataObj.completed == 0) {
                    dataObj.duration=Math.round(videoPlayer.duration);
                    dataObj.current_pos=Math.round(videoPlayer.currentTime);
                    dataObj.completed=1;
                    //send update to db
                    updateMediaProgress(dataObj);

                    //notify user of video completion
                    $.jGrowl('Finished! The Video has finished.', {
                        position: 'top-right',
                        theme: 'bg-success',
                        life: 3000,
                    });

                    //show the completion on the lesson's page
                    $('#video-completed-'+media_id).html('<i class="icon-checkmark4 ml-2 text-success"> Finished!</i>');

                    //move the progress bar
                    progressLessonBar(lesson_id, 1, dataObj.course_id);

                }

            }

            videoPlayer.onplay=function(){
                //pause all other running media
                pauseRunningMedia('videoPlayer-'+lesson_id);
            }


            videoPlayer.onloadedmetadata=function(){

                //pause all other running media
                pauseRunningMedia('videoPlayer-'+lesson_id);

                //if current_pos is not 0 or completed, set the point to where the user left off
                if(dataObj.duration-dataObj.current_pos > 0) {
                    videoPlayer.currentTime = dataObj.current_pos;
                }

                //play video after 2 secs (gives time to stop other media files and show unlocked image before playing the video)
                setTimeout(function(){
                    videoPlayer.play();
                }, 2000);

            }

            videoPlayer.onerror=function(){
                //notify user video cannot be played
                $.jGrowl('Sorry! Unable to play video.', {
                    position: 'top-right',
                    theme: 'bg-warning',
                    life: 3000,
                });
            }

        },
        error: function(data) {
            console.log(data.statusText);
            ul.errorSwalAlert("Info Warning!", data.statusText);
        },
        fail: function(data) {
            console.log(data.statusText);
            ul.errorSwalAlert("Info Warning!", data.statusText);
        }
    });
}

function playAudioWithTracking(course_id, lesson_id, media_id, src_path, student_id, display_name, required) {

    var src_path = decodeURIComponent((src_path+'').replace(/\+/g, '%20'));
    var display_name = decodeURIComponent((display_name+'').replace(/\+/g, '%20'));
    var student_id = decodeURIComponent((student_id+'').replace(/\+/g, '%20'));

    //update navigation header information
    $elem = $('#lc-navigation-header');
    $elem.find('.media-name').html('<i class="icon-headphones"></i> '+display_name).slideDown();

    /* 
        TODO: we need to find out if the previously clicked audio
        continues to download when the user clicks a new audio.
        If it does then how much unnecessary demand will this
        put on our server?

        see http://localhost:8081/stackoverflow/4071872
     */

    //get audio tracking data
    $.ajax({
        type: 'GET',
        url: 'track_progress/get_media_data.php',
        data: {media_id:media_id},
        timeout: 30000,
        success: function(data) {

            var last_update_pos = 0;

            //send an update to the server every updateSecs
            var updateSecs = 60.0;

            var dataObj = {
                course_id: course_id,
                lesson_id: lesson_id,
                student_id: student_id,
                media_id: media_id,
                file_location: src_path,
                file_type: 'audio',
                file_name: display_name,
                duration: 0,
                current_pos: 0,
                completed: 0,
                reflection: '',
                //TODO: set to 1 when the media file is delted (when lesson is deleted as well) 
                deleted: 0,
                required: required,
            }

            dataParsed = JSON.parse(data);
            
            //a media_progress record exists at this point, set needed properties
            if(dataParsed.isData != 'false') {
                dataObj.duration = dataParsed.duration;
                dataObj.current_pos = dataParsed.current_pos;
                dataObj.completed = dataParsed.completed;
                dataObj.reflection = dataParsed.reflection;
                dataObj.deleted = dataParsed.deleted;
                dataObj.required = dataParsed.required;
                last_update_pos = dataParsed.current_pos;
            }

            //get audio player and set its src path
            var audioPlayer = document.getElementById('audioPlayer-'+lesson_id);
            audioPlayer.style.display='';
            audioPlayer.setAttribute('src', src_path);
 
            audioPlayer.ontimeupdate=function(){

                /* 
                    only update the db if
                        the audio has progressed updateSecs
                        is not completed
                        is not the result user seeking
                 */

                if(audioPlayer.currentTime-last_update_pos > updateSecs && dataObj.completed == 0 && !audioPlayer.seeking) {
                    //current_pos and duration come in as floats. Round down to ints for the db
                    dataObj.duration=Math.round(audioPlayer.duration);
                    dataObj.current_pos=Math.round(audioPlayer.currentTime);
                    updateMediaProgress(dataObj);
                    last_update_pos = dataObj.current_pos;
                }

            }

            audioPlayer.onseeking=function(){

                /*
                    If the audio is required then the user cannot seek forward until they complete the audio.
                */

                if (audioPlayer.currentTime > last_update_pos && dataObj.required == 1 && dataObj.completed == 0) {
                    console.log("Seeking is disabled.");
                    audioPlayer.currentTime = last_update_pos;
                  }
              
            }

            //called when the audio ends
            audioPlayer.onended=function(){

                //skip completion if user is seeking
                if(!audioPlayer.seeking && dataObj.completed == 0) {
                    dataObj.duration=Math.round(audioPlayer.duration);
                    dataObj.current_pos=Math.round(audioPlayer.currentTime);
                    dataObj.completed=1;
                    updateMediaProgress(dataObj);

                    //notify user of audio completion
                    $.jGrowl('Finished! The Audio has finished.', {
                        position: 'top-right',
                        theme: 'bg-success',
                        life: 3000,
                    });

                    //show the completion on the lesson's page
                    $('#audio-completed-'+media_id).html('<i class="icon-checkmark4 ml-2 text-success"> Finished!</i>');
                    
                    //move the progress bar
                    progressLessonBar(lesson_id, 1, dataObj.course_id);
                    
                }

            }

            audioPlayer.onplay=function(){
                //pause all other running media
                pauseRunningMedia('audioPlayer-'+lesson_id);
            }

            audioPlayer.onloadedmetadata=function(){

                //if current_pos is not 0 or completed, set the point to where the user left off
                if(dataObj.duration-dataObj.current_pos > 0) {
                    audioPlayer.currentTime = dataObj.current_pos;
                }

                //play audio after 2 secs
                setTimeout(function(){
                    audioPlayer.play();
                }, 2000);

            }

            audioPlayer.onerror=function(){
                //notify user audio cannot be played
                $.jGrowl('Sorry! Unable to play audio.', {
                    position: 'top-right',
                    theme: 'bg-warning',
                    life: 3000,
                });

            }

        },
        error: function(data) {
            console.log(data.statusText);
            ul.errorSwalAlert("Info Warning!", data.statusText);
        },
        fail: function(data) {
            console.log(data.statusText);
            ul.errorSwalAlert("Info Warning!", data.statusText);
        }
    });
}



/* 
    The following commands are for the video element, I am thinking they will apply to the audio tag as well.

    http://localhost:8081/stackoverflow/5181865


    onabort : Script to be run on abort
    oncanplay : Script to be run when a file is ready to start playing (when it has buffered enough to begin)
    oncanplaythrough : Script to be run when a file can be played all the way to the end without pausing for buffering
    ondurationchange : Script to be run when the length of the media changes
    onemptied : Script to be run when something bad happens and the file is suddenly unavailable (like unexpectedly disconnects)
    onended : Script to be run when the media has reach the end (a useful event for messages like "thanks for listening")
    onerror : Script to be run when an error occurs when the file is being loaded
    onloadeddata : Script to be run when media data is loaded
    onloadedmetadata : Script to be run when meta data (like dimensions and duration) are loaded
    onloadstart : Script to be run just as the file begins to load before anything is actually loaded
    onpause : Script to be run when the media is paused either by the user or programmatically
    onplay : Script to be run when the media is ready to start playing
    onplaying : Script to be run when the media actually has started playing
    onprogress : Script to be run when the browser is in the process of getting the media data
    onratechange : Script to be run each time the playback rate changes (like when a user switches to a slow motion or fast forward mode)
    onreadystatechange : Script to be run each time the ready state changes (the ready state tracks the state of the media data)
    onseeked : Script to be run when the seeking attribute is set to false indicating that seeking has ended
    onseeking : Script to be run when the seeking attribute is set to true indicating that seeking is active
    onstalled : Script to be run when the browser is unable to fetch the media data for whatever reason
    onsuspend : Script to be run when fetching the media data is stopped before it is completely loaded for whatever reason
    ontimeupdate : Script to be run when the playing position has changed (like when the user fast forwards to a different point in the media)
    onvolumechange : Script to be run each time the volume is changed which (includes setting the volume to "mute")
    onwaiting : Script to be run when the media has paused but is expected to resume (like when the media pauses to buffer more data).

 */


 function require_media_file(displayName, mediaId) {

    var displayName = decodeURIComponent((displayName+'').replace(/\+/g, '%20'));
    var is_checked = $('#require-media-'+mediaId).is(":checked");
    var required = 0;
    var message = '';
    var theme = '';
   
    //ensure media id
    if (!mediaId) {
        ul.errorSwalAlert("Info Warning!", "Request failed! Please refresh and try again.");
        return false;
    }

    if (is_checked) {
        required = 1;
        message = 'The student will be unable to fast forward ' + displayName + ' until they have watched/listened to the entire file.';
        theme = 'bg-success';
    } else {
        required = 0;
        message = 'The student can now fast forward ' + displayName + '.';
        theme = 'bg-warning';
    }

    //send request to the server
    var url = 'track_progress/require_media_file.php?media_id='+mediaId+'&required='+required;
        
    $.ajax({
        type: 'GET',
        url: url,
        timeout: 30000,
        success: function(data) {
            $.jGrowl(message, {
                position: 'top-right',
                theme: theme,
                life: 30000,
            });
        },
        error: function(data) {
            ul.errorSwalAlert("Info Warning!", data.statusText);
        },
        fail : function(data) {
            ul.errorSwalAlert("Info Warning!", data.statusText);
        }
    });

    return false;

}

function progressLessonBar(lessonId, interval, courseId) {

    var $barElem = $('#lesson-progress-'+lessonId);
    var completed = $barElem.data('completed');
    var required = $barElem.data('required');

    var courseName =  $(".breadcrumb-item.course-name").html();
    
    if($barElem.data('reload') == '1') {
        progressLessonBarReload(lessonId);
        return false;
    }

    var completedUpdate = completed + interval;
    var percent = (completedUpdate/required)*100;

    // checks the completion of item/lesson/course and rewards accordingly gamification.js
    if ( typeof checkCompletionGamification == 'function' ) {
        checkCompletionGamification(percent, courseId, courseName);
    }
    
    /* 
            When we set completed with .data() it updates internally,
            so the number shown in the html
            attributes data-completed-N and data-required-N 
            will not match the internal representation.
    */

    $barElem.data('completed', completed + interval);
    $barElem.data('reload', '0');
    $barElem.css('width', percent+'%');
    $barElem.find('span').text(percent.toFixed(1)+'% Completed');
    $barElem.removeClass('bg-warning');
    $barElem.addClass('bg-success');

}

function progressLessonBarReload(lessonId) {

    var $barElem = $('#lesson-progress-'+lessonId);
    $barElem.data('reload', '1');
    $barElem.css('width', '100%');
    $barElem.find('span').text('There was a change to the lesson requirements. You will need to refresh the page to update your progress bar.');
    $barElem.removeClass('bg-warning');
    $barElem.removeClass('bg-success');
    $barElem.addClass('bg-danger');

}
