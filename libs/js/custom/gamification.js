

// POINT VALUES TO BE SET BY ADMIN
var itemPoints = 120;
var lessonPoints = 525;
var coursePoints = 1500;
var new_user_points = 500;
var loginRewardOne = 500;
var loginRewardTwo = 500;
var loginRewardThree = 500;


// STATUS AND POINT THRESHOLD VARIABLES TO BE SET BY ADMIN
var level_one = "NEW USER";

var level_two = "LEVEL TWO";
var level_two_threshold = 5000;
var level_two_prize = "LEVEL TWO PRIZE"; //currently unused

var level_three = "LEVEL THREE";
var level_three_threshold = 15000;
var level_three_prize = "LEVEL THREE PRIZE"; //currently unused

var level_four = "LEVEL FOUR";
var level_four_threshold = 25000;
var level_four_prize = "LEVEL FOUR PRIZE"; //currently unused

var level_five = "LEVEL FIVE";
var level_five_threshold = 50000; 
var level_five_prize = "LEVEL FIVE PRIZE"; //currently unused

var level_six = "LEVEL SIX";
var level_six_threshold = 75000;
var level_six_prize = "LEVEL SIX PRIZE"; //currently unused

var level_seven = "LEVEL SEVEN";
var level_seven_threshold = 100000;
var level_seven_prize = "LEVEL SEVEN PRIZE"; //currently unused


//STATUS/LEVEL COLORS - UNTOUCHED BY ADMIN (CLASSES MATCH getLevelColor() IN gamification.php)
var level_one_color = "green-300";
var level_two_color = "danger-300";
var level_three_color = "indigo-300";
var level_four_color = "info-300";
var level_five_color = "orange-800";
var level_six_color = "grey-300";
var level_seven_color = "orange-300";

// FUNCTION TO CONVERT NUMBERS TO NUMBERS WITH COMMA DELIMETER (ie. 5000 to 5,000)
function numberCommaDelimeter(number){

    var numString = String(number);
    var numLength = numString.length;

    if (numLength > 3) {
        var numSplit = numLength - 3;
        var baseNumber = numString.slice(0,numSplit);
        var thousand = numString.slice(numSplit,numLength);
        var readableNum = baseNumber + "," + thousand;
    
        return readableNum;
    } else {
        return numString;
    }

}

// ADDS COINS AND THEN FIRES updateStatus() TO UPDATE USER
function addCoins(add_coins, count_by, pointsFor, courseId, courseName) {

    coinsCurrent = $(".coin_count").html();
    coinsCurrent = coinsCurrent.replace(",", "");
    coinsCurrent = Number(coinsCurrent);

    var new_total = coinsCurrent + add_coins;
    // sends new coin total before visual coin counting in navbar 
    // (in case user clicks a link before coins are fully tablulated)
    updateStatus("calculating", new_total); 

    var interval = setInterval(function () {
        coinsCurrent += count_by;
        if (coinsCurrent < new_total + 1) {
            $(".coin_count").html(numberCommaDelimeter(coinsCurrent));
        } else {
            window.clearInterval(interval);
            updateStatus("update", coinsCurrent - count_by);
            if (pointsFor == "itemAndLesson") {
                completeSection("lesson", courseId, courseName);
            }
        }
    }, 10);
}


function completeSection(section, courseId, courseName) {
    if (section == "item") {
        addCoins(itemPoints, 2, "item");
    } else if (section == "itemAndLesson") {
        addCoins(itemPoints, 2, "itemAndLesson", courseId, courseName);
    } else if (section == "lesson") {
        addCoins(lessonPoints, 5, "lesson");
        new Noty({
            theme: 'alert alert-success alert-styled-left p-0',
            text: 'You Earned ' + lessonPoints + ' coins for completing this lesson!',
            type: 'success',
            layout: 'bottomRight',
            progressBar: false,
            timeout: 5000,
            closeWith: ['button']
        }).show();    
        ajaxCheckCourseCompletion(courseId, courseName);
    } else if (section == "course") {
        addCoins(coursePoints, 5);    
    }
}

// CHECKS COMPLETION OF VARIOUS CONTENT
function checkCompletionGamification(percent, courseId, courseName) {
    if (percent >= 100.0) {
        completeSection("itemAndLesson", courseId, courseName);
    } else if (percent < 100.0) {
        completeSection("item");
    }
}

// CHECKS AND SETS STATUS AFTER COINS ADDED
function updateStatus(origin, coins) {

    var currentStatus = $("#status").html();

    if (coins >= level_two_threshold && coins < level_three_threshold) {
        unlockLevel(2, level_two, level_two_color, level_three, level_three_threshold);
        ajaxUpdateUser(coins, level_two, 2);
        if (currentStatus != level_two && origin == "update") {
            alertStatusChange(level_two.toLowerCase(), level_three.toLowerCase(), numberCommaDelimeter(level_three_threshold));
        }
    } else if (coins >= level_three_threshold && coins < level_four_threshold) {
        unlockLevel(3, level_three, level_three_color, level_four, level_four_threshold);
        ajaxUpdateUser(coins, level_three, 3);
        if (currentStatus != level_three && origin == "update") {
            alertStatusChange(level_three.toLowerCase(), level_four.toLowerCase(), numberCommaDelimeter(level_four_threshold));
        }
    } else if (coins >= level_four_threshold && coins < level_five_threshold) {
        unlockLevel(4, level_four, level_four_color, level_five, level_five_threshold);
        ajaxUpdateUser(coins, level_four, 4);
        if (currentStatus != level_four && origin == "update") {
            alertStatusChange(level_four.toLowerCase(), level_five.toLowerCase(), numberCommaDelimeter(level_five_threshold));
        }
    } else if (coins >= level_five_threshold && coins < level_six_threshold) {
        unlockLevel(5, level_five, level_five_color, level_six, level_six_threshold);
        ajaxUpdateUser(coins, level_five, 5);
        if (currentStatus != level_five && origin == "update") {
            alertStatusChange(level_five.toLowerCase(), level_six.toLowerCase(), numberCommaDelimeter(level_six_threshold));
        }
    } else if (coins >= level_six_threshold && coins < level_seven_threshold) {
        unlockLevel(6, level_six, level_six_color, level_seven, level_seven_threshold);
        ajaxUpdateUser(coins, level_six, 6);
        if (currentStatus != level_six && origin == "update") {
            alertStatusChange(level_six.toLowerCase(), level_seven.toLowerCase(), numberCommaDelimeter(level_seven_threshold));
        }
    } else if (coins >= level_seven_threshold) {
        unlockLevel(7, level_seven, level_seven_color);
        ajaxUpdateUser(coins, level_seven, 7);
        if (currentStatus != level_seven && origin == "update") {
            alertStatusChange(level_seven.toLowerCase());
        }
    } else {
        changeStatusNavbar(level_one, level_one_color, level_two, numberCommaDelimeter(level_two_threshold));
        ajaxUpdateUser(coins, level_one.toLowerCase(), 1);
    }

}


// UPDATES USER'S COINS, STATUS, AND LEVEL. ON SUCCESS, CHECKS IF COURSE WAS COMPLETED
function ajaxUpdateUser(coins, user_status, user_level, courseId) {
    var courseId = courseId;

    $.ajax({
        type: 'POST',
        url: 'gamification/update_coins.php',
        data: { coins: coins, user_status: user_status, user_level: user_level },
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
            if (courseId) {
                ajaxCheckCourseCompletion(courseId);
            }
        },
        error: function () {
        },
        fail: function () {
        }
    });
}

function ajaxCheckCourseCompletion(courseId, courseName) {

    /*
        THIS GETS CALLED BY ajaxUpdateUser()
        ON LOGIN (WHERE courseId = NULL) AND ALSO
        completeSection() WHEN LESSON COMPLETED.
    */

    if(!courseId){
        return false;
    }

    var url = `gamification/check_course_completion.php?courseId=${courseId}`;

    $.ajax({
        type: 'GET',
        url: url,
        timeout: 30000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function (data) {

            // SHOW CELEBRATION FOR FULLY COMPLETING A COURSE
            if(data >= 100) {
                gamificationModal("course", courseName);
            }
        },
        error: function (data) {
            console.log(data.statusText);
        },
        fail: function () {
            console.log(data.statusText);
        }
    });
}

// CHANGES STATUS INFO
function changeStatusNavbar(status, color, nextStatus, coins_needed) {
    var oldColor = $("#status").attr("class");
    oldColor = oldColor.replace("text-", "");
    
    $("#status,#panel_status").html(status).removeClass("d-none text-" + oldColor).addClass("text-" + color);
    $("#status_card").removeClass("border-" + oldColor).addClass("border-" + color);

    if (status == level_seven) {
        $("#next_level_info").html("You made it to " + status + "! <br>Keep learning and earning...");

    } else {
        $("#next_level").html(nextStatus);
        $("#next_level_coins").html(coins_needed);

    }

}




// UNLOCKS NEXT LEVEL AND CHANGES STYLES AND COIN PICTURE
function unlockLevel(number, level, color, nextLevel, nextThreshold) {
    changeStatusNavbar(level, color, nextLevel, numberCommaDelimeter(nextThreshold));
    $("#nav_coin_img").attr("src", "./images/gamification/coins/gold_" + number + ".png");
    $("#info_coin_img").attr("src", "./images/gamification/coins/gold_" + number + ".png");

    if (number > 5) {
        $("#info_coin_img").attr("width", "40%");
        $("#nav_coin_img").attr("width", "30");
    }
}


// NOTY ALERTING USER OF LEVEL UP
function alertStatusChange(status, next_status, coins_needed) {
    if (status == level_seven.toLowerCase()) {
        new Noty({
            theme: 'alert alert-success alert-styled-left p-0',
            text: 'You just earned ' + status + ' status! Keep it up!',
            layout: 'bottomRight',
            progressBar: false,
            timeout: 5000,
            closeWith: ['button']
        }).show();
    } else {
        new Noty({
            theme: 'alert alert-success alert-styled-left p-0',
            text: 'You just earned ' + status + ' status! Collect ' + coins_needed + ' coins to achieve ' + next_status + ' status...',
            layout: 'bottomRight',
            progressBar: false,
            timeout: 5000,
            closeWith: ['button']
        }).show();
    }
}


// MODAL ANIMATIONS
function gamificationModal(type, course_name) {

    //EXECUTES MODAL
    $("#game_modal").modal("show");

    if (type == "course") { // FIRES MODAL WITH COURSE COMPLETION CONTENT

        $("#collect_modal_header").hide();
        $("#collect_btn").attr("onclick", "addCoins(coursePoints, 5); $('#greeting').html('CLOSED');");

        var version = Math.floor(Math.random() * 3) + 1;   // RANDOM NUMBER TO SELECT CELEBRATION ANIMATION

        if (version == 1) { // SMILEYS
            image = "smiley_teeth";

            $("#img_left").attr("width", "75%");
            $("#img_right").attr("width", "75%");

            $("#img_left").attr("src", "./images/gamification/course_complete/" + image + ".png");
            $("#img_right").attr("src", "./images/gamification/course_complete/" + image + ".png");

            $("#greeting").html("<span class='mb-5 font-weight-regular' style='font-size: 50px'>GET HAPPY!</span><h4 class='mt-3'>YOU JUST COMPLETED</h4>");
            $("#course_name").html("<h1 class='mb-5 border-1 rounded border-rounded border-green'>" + course_name.toUpperCase() + "</h1>");

            var interval = setInterval(function () {
                $("#img_left").toggleClass("tada bounce");
                $("#img_right").toggleClass("tada bounce");
                
                if (image == "smiley_teeth") {
                    image = "smiley_laugh";
                } else {
                    image = "smiley_teeth";
                }

                $("#click_here_arrow").removeClass("bounce");
                setTimeout(function () { $("#click_here_arrow").addClass("bounce") }, 500);

                $("#img_left").attr("src", "./images/gamification/course_complete/" + image + ".png");
                $("#img_right").attr("src", "./images/gamification/course_complete/" + image + ".png");

                if ($("#greeting").html() == "CLOSED") {
                    window.clearInterval(interval);
                }

            }, 2000);
        } else if (version == 2) { // THUMBS UP CELEBRATION
            $("#img_left").attr("width", "125%");
            $("#img_right").attr("width", "125%");

            $("#img_left").attr("src", "./images/gamification/course_complete/left_thumb.png");
            $("#img_right").attr("src", "./images/gamification/course_complete/right_thumb.png");

            $("#greeting").html("<span class='mb-5 font-weight-regular' style='font-size: 40px'>TWO THUMBS UP!</span><h4 class='mt-3'>YOU JUST COMPLETED</h4>");
            $("#course_name").html("<h1 class='mb-5 border-1 rounded border-rounded border-green'>" + course_name.toUpperCase() + "</h1>");

            var interval = setInterval(function () {
                $("#img_left").toggleClass("tada bounce");
                $("#img_right").toggleClass("tada bounce");

                $("#click_here_arrow").removeClass("bounce");
                setTimeout(function () { $("#click_here_arrow").addClass("bounce") }, 500);

                if ($("#greeting").html() == "CLOSED") {
                    window.clearInterval(interval);
                }
            }, 2000);
        } else if (version == 3) { // COINS CELEBRATION

            $("#img_left").attr("width", "75%");
            $("#img_right").attr("width", "75%");

            $("#img_left").attr("src", "./images/gamification/coins/gold_7_midsize.png");
            $("#img_right").attr("src", "./images/gamification/coins/gold_7_midsize.png");

            $("#greeting").html("<span class='mb-5 font-weight-regular' style='font-size: 40px'>BREAK THE BANK!</span><h4 class='mt-3'>YOU JUST COMPLETED</h4>");
            $("#course_name").html("<h1 class='mb-5 border-1 rounded border-rounded border-green'>" + course_name.toUpperCase() + "</h1>");

            var interval = setInterval(function () {
                $("#img_left").toggleClass("tada bounce");
                $("#img_right").toggleClass("tada bounce");

                $("#click_here_arrow").removeClass("bounce");
                setTimeout(function () { $("#click_here_arrow").addClass("bounce") }, 500);

                if ($("#greeting").html() == "CLOSED") {
                    window.clearInterval(interval);
                }
            }, 2000);
        }
    } else if (type == "new") { // FIRES MODAL TO WELCOME NEW USER
        $("#confetti").hide();
        $("#center_block").removeClass("bg-transparent").addClass("bg-light");
        $("#greeting").html("<h1 class='font-weight-bold'>Welcome!</h1><h5>YOU'VE EARNED <span class='font-weight-bold'>" + numberCommaDelimeter(new_user_points) + " COINS</span><br>FOR SIGNING UP WITH US.</h5>");
        $("#course_name").html("<h1>GET STARTED!</h1>");
        changeStatusNavbar(level_one, level_one_color, level_two);
        $("#collect_btn").attr("onclick", "addCoins(" + new_user_points + ", 2);");
    } else if (type == "rewardOne") { // FIRES MODAL FOR FIRST LOGIN REWARD THRESHOLD (THRESHOLD VAR @ login_count.php)
        $("#confetti").hide();
        $("#center_block").removeClass("bg-transparent").addClass("bg-light");
        $("#greeting").html("<h1 class='font-weight-bold'>Welcome Back!</h1><h5>HERE'S AN EXTRA <span class='font-weight-bold'>" + numberCommaDelimeter(loginRewardOne) + " COINS</span><br>FOR STICKING WITH IT.</h5>");
        $("#course_name").html("<h1>KEEP IT UP!</h1>");
        changeStatusNavbar(level_one, level_one_color, level_two);
        $("#collect_btn").attr("onclick", "addCoins(" + loginRewardOne + ", 2);")
    } else if (type == "rewardTwo") { // FIRES MODAL FOR SECOND LOGIN REWARD THRESHOLD (THRESHOLD VAR @ login_count.php)
        $("#confetti").hide();
        $("#center_block").removeClass("bg-transparent").addClass("bg-light");
        $("#greeting").html("<h1 class='font-weight-bold'>SURPRISE!</h1><h5>HERE'S ANOTHER <span class='font-weight-bold'>" + numberCommaDelimeter(loginRewardTwo) + " COINS</span> ON US.</h5>");
        $("#course_name").html("<h1>WE'RE PROUD OF YOU!</h1>");
        $("#collect_btn").attr("onclick", "addCoins(" + loginRewardTwo + ", 2);");
    } else if (type == "rewardThree") { // FIRES MODAL FOR THIRD LOGIN REWARD THRESHOLD (THRESHOLD VAR @ login_count.php)
        $("#confetti").hide();
        $("#center_block").removeClass("bg-transparent").addClass("bg-light");
        $("#greeting").html("<h1 class='font-weight-bold'>Great Job!</h1><h5>WE CAN SEE THAT YOU'RE SERIOUS!<br>HERE'S <span class='font-weight-bold'>" + numberCommaDelimeter(loginRewardThree) + " BONUS COINS.</span></h5>");
        $("#course_name").html("<h1>KEEP GOING!</h1>");
        $("#collect_btn").attr("onclick", "addCoins(" + loginRewardThree + ", 2);");
    }
}

// COIN AND STATUS INFO PANEL AT TOP RIGHT OF SCREEN (ACCESS BY CLICKING STATUS)
var Pnotify = function () {
    var _componentPnotify = function () {
        if (typeof PNotify == 'undefined') {
            console.warn('Warning - pnotify.min.js is not loaded.');
            return;
        }

        $('#show_coin_info').on('click', function () {
            $("#show_coin_info").addClass("disabled");
            var notice = new PNotify({
                text: $('#coin_info').html(),
                width: '300px',
                hide: false,
                addclass: 'bg-slate-600 border-dark shadow',
                buttons: {
                    closer: false,
                    sticker: false
                },
                insert_brs: false
            });

            notice.get().find('a[id=close]').on('click', function () {
                $("#show_coin_info").removeClass("disabled");
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

document.addEventListener('DOMContentLoaded', function () {
    Pnotify.init();
});

