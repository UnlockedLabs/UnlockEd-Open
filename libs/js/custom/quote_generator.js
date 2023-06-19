/*
var date = new Date().getDate();

$.ajax({
    url: 'user_preferences/quotes copy.json',
    dataType: 'json',
    success: function(quotes) { 
        $("#quote").html('"' + quotes[10].quote + '"');
        $("#quoteAuthor").html(quotes[10].author);
    }
})
*/


function getRandomQuote(){

    randomNumber = Math.floor(Math.random() * 619) + 1;
    $.ajax({
        url: 'user_preferences/quotesFortuneMod.json',
        dataType: 'json',
        success: function(quotes) { 
            $("#quote").html('"' + quotes[randomNumber].quote.trim() + '"');
            $("#quoteAuthor").html(quotes[randomNumber].author);
        }
    })
}

$("#refreshQuote").click( function(){
    refreshRandomQuote();
})

function refreshRandomQuote() {
    $("#blockquote").animate({
        opacity: '0',
    });

    setTimeout( function() {    
        getRandomQuote();
    }, 500)

    setTimeout( function() {
        $("#blockquote").animate({
            opacity: '1',   
        });
    }, 1000)

}

document.addEventListener('DOMContentLoaded', function () {
    getRandomQuote();
});

