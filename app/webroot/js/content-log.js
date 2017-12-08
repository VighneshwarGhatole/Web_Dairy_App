function log_activity(formData) {
    console.log(formData);
    $.ajax({
        url: webURL+"Users/contentAccessLog",
        type: "post",
        data: formData ,
        dataType: 'json',
        success: function (data) {
//            var json = JSON.parse(data);
            var json = data;
            if (json.status == 1) {
                console.log(json);
            } else {
                    console.log('error-------->'+data);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    }); 
}

function logPageVisit(){
    var page = $('#supersized>li.activeslide');
    var page_id = 0;
    if(page.attr('id')>0){
        if(loggedPages.indexOf(page.attr('id'))> -1){
            console.log('Already logged: Page revisited');
        }else{
            formData.page_id = page.attr('id');
            log_activity(formData);
            if(previousVisitedPages.indexOf(parseInt(page.attr('id')))< 0){
                
                previousVisitedPages.push(parseInt(page.attr('id')));
                updateCompetionMeter();
            }
        }
    }else{
        console.log('Error: Unable to save. Invalid data');
    } 
}

function updateCompetionMeter(){
//    console.log('update meter %');
    var percentCompleted = Math.round((previousVisitedPages.length/formData.notes_count)*100);
    console.log(percentCompleted);
    var htmlstr = '<div class="c100 p'+percentCompleted+' x-small" style="margin:-14px 10px">'+
                    '<span>'+percentCompleted+'%</span>'+
                    '<div class="slice">'+
                        '<div class="bar"></div>'+
                        '<div class="fill"></div>'+
                    '</div>'+
                '</div>';
    $('#compMeter').html(htmlstr);
}