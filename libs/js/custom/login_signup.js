$('body').addClass("overflow-hidden");

// HIDES ELEMENTS AND FADES IN GREETING
$("#welcome_elems,#login_card,#registration_card").hide().removeClass("invisible");
setTimeout(function () {
    $("#welcome_elems").fadeIn();
}, 250)

// FADES OUT ALL FORMS AND FADES IN SELECTED FORM
function fadeForms(form) {
    $("#welcome_elems,#login_card,#registration_card").fadeOut();

    if (!$(".blur").length) {
        $("#welcome_img").addClass("blur");
    }

    setTimeout(function () {
        $("#" + form).fadeIn();

        if (form == "login_card"){
            $("#username").focus();
        }

    }, 500)
}

// COMPLETES USER REGISTRATION AND DROPS ALERT INTO LOGIN FORM BEFORE FADING BACK TO LOGIN FORM
// UNUSED IN VERSION AS OF COMMENT 1/21/2022
/*
function userRegistration() {
    var firstName = $("#registration_card input[name='first_name']").val();
    $.ajax({
        type: 'POST',
        url: 'create_user.php',
        data: $('#registration_card').serialize(),
        timeout: 2000,
        beforeSend: function () {
        },
        complete: function () {
        },
        success: function () {
            $("#new_user_greet").html('<div class="alert alert-primary alert-styled-left alert-dismissible mt-2"><button type="button" class="close" data-dismiss="alert"><span>×</span></button><span class="font-weight-semibold">Hello, ' + firstName + '!</span> Thanks for creating an account! You can now login using your username and password.</div>');
            fadeForms("login_card");
        },
        error: function () {
        },
        fail: function () {
        }
    });
}
*/