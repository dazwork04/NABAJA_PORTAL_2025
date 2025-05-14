var nanobar = new Nanobar();
$(document).load(function(){
    //Intialize nanobar
    nanobar.go(40);
    //End Intialize nanobar
});

$(window).load(function() {

    //set nanobar to 100% when document is ready
    nanobar.go(100);
    //End set nanobar to 100% when document is ready
});