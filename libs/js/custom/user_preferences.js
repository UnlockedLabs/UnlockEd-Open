

//----------------DASHBOARD-----------------

$("body").removeClass("overflow-hidden");

// SETS AND LIVE UPDATES DAY/TIME FOR USER GREETING
var h = new Date().getHours();
var m = new Date().getMinutes();
var d = new Date().getDay();
var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

$("#day" + d).toggleClass("opacity-50 opacity-100");
$("#day" + d).addClass("border-bottom-1")

if (h < 12) {
    $("#timeOfDay").html(" morning, ");
} else if (h >= 12 && h < 17) {
    $("#timeOfDay").html(" afternoon, ");
} else {
    $("#timeOfDay").html(" evening, ");
}

function padMins(val) {
    return (val < 10) ? '0' + val : val;
}

function convert24(val) {
    if (val == 00) {
        return 12;
    } else if (val > 12) {
        var time12 = val - 12;
        return (time12 < 10) ? '0' + time12 : time12;
    } else {
        return (val < 10) ? '0' + val : val;
    }
}

$("#dash_hour").html(convert24(h));
$("#dash_min").html(padMins(m));
setInterval(function () {
    $("#dash_hour").html(convert24(new Date().getHours()));
    $("#dash_min").html(padMins(new Date().getMinutes()));
}, 60000);




var PnotifyColors = function () { 
    var _componentPnotify = function () {
        if (typeof PNotify == 'undefined') {
            console.warn('Warning - pnotify.min.js is not loaded.');
            return;
        }
        
        $('#show_color_pallet').on('click', function () {
            var notice = new PNotify({
                text: $('#color_pallet').html(),
                width: '200px',
                hide: false,
                addclass: 'bg-slate rounded border-dark mt-5',
                buttons: {
                    closer: false,
                    sticker: false
                },
                insert_brs: false
            });

            notice.get().find('a[id=close]').on('click', function () {
                notice.remove();
            })
            
        });
        
        $('#show_image_select').on('click', function () {
            var notice = new PNotify({
                text: $('#image_select').html(),
                width: '1000px',
                hide: false,
                addclass: 'bg-slate rounded border-dark mt-5',
                buttons: {
                    closer: false,
                    sticker: false
                },
                insert_brs: false
            });
            
            notice.get().find('a[id=close]').on('click', function () {
                notice.remove();
            })
            
        });
        
        
    };
    
    return {
        init: function () {
            _componentPnotify();
        }
    }
}();



        
// CHECK TASK AS COMPLETED
function complete(taskId) {
    $("#desc" + taskId).toggleClass('text-muted');
    
    $.ajax({
        type: 'POST',
        url: 'user_preferences/complete_user_task.php',
        data: { taskId: taskId },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
        },
        error: function () {
        },
        fail: function () {
        }
    });

}

// ADD TASK TO TASK LIST
function addTask() {
    var newTask = $("#newTask").val();
    if (newTask != "") {
        $("#newTask").val("");
        var newTaskNum = Math.floor(Math.random() * 9999) + 1000;
        $("#taskList").append('<div class="custom-control custom-checkbox custom-control-inline mb-1" id="' + newTaskNum + '">' +
        '<input class="custom-control-input" id="task' + newTaskNum + '" type="checkbox" onclick="complete(' + newTaskNum + ');">' +
        '<label class="custom-control-label" for="task' + newTaskNum + '" id="desc' + newTaskNum + '"></label>' +
        '<span class="font-size-xs text-danger cursor-pointer" onclick="removeTask(' + newTaskNum + ');"><i class="icon icon-cross3"></i></span>' +
        '</div>');
        $("#desc" + newTaskNum).text(newTask);
        $.ajax({
            type: 'POST',
            url: 'user_preferences/add_user_task.php',
            data: { newTask: newTask, newTaskNum: newTaskNum },
            timeout: 30000,
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function () {
            },
            error: function () {
            },
            fail: function () {
            }
        });
    }
}

// REMOVE TASK FROM TASK LIST
function removeTask(taskId) {

    $("#" + taskId).fadeOut();
    
    $.ajax({
        type: 'POST',
        url: 'user_preferences/remove_user_task.php',
        data: { taskId: taskId },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
        },
        error: function () {
        },
        fail: function () {
        }
    });
    
}


// DASHBOARD ANIMATIONS

setTimeout( function() {
    $("#dashboardGreeting").addClass("animated fadeIn").removeClass("invisible");
}, 200)

setTimeout( function() {
    $("#myTasks").addClass("animated fadeIn").removeClass("invisible");
}, 400)

setTimeout( function() {
    $("#quoteGenerator").addClass("animated fadeIn").removeClass("invisible");
}, 600)



//  ------------  USER PREFERENCES ----------------- //

// CHANGES COLOR WHEN USER PICKS FROM PNOTIFY COLOR PICKER
function changeColor(dashboardColor) {
    $(".ue_color_theme").css("background-color", dashboardColor);

    
    $.ajax({
        type: 'POST',
        url: 'user_preferences/update_user_preferences.php',
        data: { dashboardColor: dashboardColor },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
        },
        error: function () {
        },
        fail: function () {
        }
    });
    
}


// CHANGES PICTURE WHEN USER PICKS FROM PNOTIFY PICTURE PICKER
function selectBanner(bannerNum) {
    $(".profile-cover-img").attr("style","background-image: url(./user_preferences/images/banner" + bannerNum + ".jpg)");
    
    $.ajax({
        type: 'POST',
        url: 'user_preferences/update_user_preferences.php',
        data: { bannerNum: bannerNum },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
        },
        error: function () {
        },
        fail: function () {
        }
    });
    
}

// TOGGLES BETWEEN DAY MODE AND NIGHT MODE
function toggleNightMode(nightMode) {
    
    $(".content-wrapper").toggleClass("bg-dark bg-light");

    if (nightMode == "light")
    {
        nightMode = "dark"; // switch to dark and update
        $("#toggleNightMode").attr("onclick", "toggleNightMode('dark');");
    } else {
        nightMode = "light"; // switch to light and update
        $("#toggleNightMode").attr("onclick", "toggleNightMode('light')");
    }
    
    $.ajax({
        type: 'POST',
        url: 'user_preferences/update_user_preferences.php',
        data: { nightMode: nightMode },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
        },
        error: function () {
        },
        fail: function () {
        }
    });
    
}

// SIDEBAR TOGGLE
$("#collapseSidebarBtn").click(function(){
    
    var sidebarToggle;
    if ($('body').attr("class").indexOf('sidebar-xs') > 0) {
        sidebarToggle = 1;
        $(this).removeClass("rotate-180");
    } else {
        sidebarToggle = 2;
        $(this).addClass("rotate-180");
    }   

    $.ajax({
        type: 'POST',
        url: 'user_preferences/update_user_preferences.php',
        data: { sidebarToggle: sidebarToggle },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
        },
        error: function () {
        },
        fail: function () {
        }
    });

});

// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    PnotifyColors.init();
});

