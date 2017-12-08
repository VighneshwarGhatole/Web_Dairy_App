var player = {};
player.id = '';
//    player.init = INIT();
player.iframe = '';
player.next = nextPage();
player.previous = previousPage();
player.goTo = jumpToPage();
player.toggleFullscreen = toggleFullScreen();
player.close = close();

$(document).ready(function () {

    // var theme1 = theme;
    // var pdfiframe = $('#pdfiframe')[0]; //document.getElementById("pdfiframe").
    /*
     var win = document.getElementById("pdfiframe").contentWindow
     
     win.postMessage('Hello child Frame!', '*');
     
     $('#pdfiframe').on('load', function () {
     var iframeWindow = document.getElementById("pdfiframe").contentWindow
     var sampleObj = {
     slides: slides,
     page: 4
     
     
     }
     iframeWindow.postMessage(sampleObj, '*');
     
     // 	console.log('===========loaded>>>>>>>>>>>>>>>>>>');
     
     });
     */
    /****************Code to initialize & test object**********************/

//    player.init;
//    player.id = 'pdfiframe';
    INIT('pdfiframe');
    player.toggleFullscreen ;
    

    /**************************************/


});


function INIT(id) {
    console.log('=========here=======');
    console.log(player.id);
    player.id = id;
    if (player.id.length > 0) {
        console.log(player.id);
        loadframe();
//        loadPlayer();

    }

}

function loadPlayer() {
    var sampleObj = {
        action:'load',
        slides: slides,
        page: 4
    }
    player.iframe.postMessage(sampleObj, '*');
    player.iframe.postMessage('Hello child Frame!', '*');

    console.log('===========message sent>>>>>>>>>>>>>>>>>>');

}

function loadframe() {
    $('#' + player.id).on('load', function () {
        player.iframe = document.getElementById(player.id).contentWindow;
        console.log('===========loaded>>>>>>>>>>>>>>>>>>');
        loadPlayer();
    });
}

function nextPage() {
    $("#next").on('click',function(){
        var sampleObj = {
            action: 'next'
        }
    player.iframe.postMessage(sampleObj, '*');
    });

}

function previousPage() {
     $("#previous").on('click',function(){
        var sampleObj = {
            action: 'previous'
    }
    player.iframe.postMessage(sampleObj, '*');
  });

}

function jumpToPage() {
    $("#goto").on('click',function(){
        var sampleObj = {
            action: 'goto',
            page: 4
    }
    player.iframe.postMessage(sampleObj, '*');
  });
}

function toggleFullScreen() {
    $("#fullscreen").on('click',function(){
        var sampleObj = {
            action: 'fullscreen'
        }
    player.iframe.postMessage(sampleObj, '*');
    });

}

function close() {

}


