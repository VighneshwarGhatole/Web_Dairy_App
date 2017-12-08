$(document).ready(function() { 
    var currentId = $(".sec a.active").attr('id');
    var getEId = $('#'+currentId).attr('rel');
    var allElement = getEId.split('_');
    if(parseInt(allElement[0]) == 1){
        $('#previous').hide();
    } else if(allElement[0] == allElement[1]) {
        $('#next').hide();
    }
    
    setInterval(function(){
        millis -= 1000;
        displaytimer();
    }, 1000);
    
});
function displaytimer(){
    var hours = Math.floor(millis / 36e5),
    mins = Math.floor((millis % 36e5) / 6e4),
    secs = Math.floor((millis % 6e4) / 1000);
    //Here, the DOM that the timer will appear using jQuery
    if(hours==0 && mins==0 && secs==0){
        saveAnswer('TimeUp');
        $('#counter').html('00:00:00');
    } else {
        if(hours<10){
            hours = '0'+hours;
        }
        if(mins<10){
            mins = '0'+mins;
        }
        if(secs<10){
            secs = '0'+secs;
        }
        $('#counter').html(hours+':'+mins+':'+secs); 
    }
}
function getQuestion(rId,qId,tCount){
    if(qId==''){
        alert('something went wrong.');
        return false;
    }
    $.ajax({
        url: webURL + 'Questions/getQuestion/'+qId+'/'+cId+'/'+uId,
        type: 'POST',
        async:false,
        success: function(data, textStatus, jqXHR){
            $('.box-number').removeClass('active');
            $('#question_no_'+rId).addClass('active');
            $('#next').show();
            $('#previous').show();
            if(rId==1){
                $('#previous').hide();
            } else if(rId==tCount) {
                $('#next').hide();
            }
            $('#question_template').html(data);
            $('#currNo').html(rId+'/'+totalQues);
            $('#Qno').html('Q'+rId+'.');
            MathJax.Hub.Queue(["Typeset",MathJax.Hub,'ques_dashboard']);
        },
       error: function(data) {
            alert('Somethig went wrong...');
            return false;
        }
    });
}

function getNextPrevQuestion(typ,frmName){
    var currentId = $(".sec a.active").attr('id');
    var getEId = $('#'+currentId).attr('rel');
    var allElement = getEId.split('_');
    if((totalQues != parseInt(allElement[0])) || (typ !='saved')){
        var newId;
        if(typ=='next'){
            newId = parseInt(allElement[0])+1;
            if(frmName !='saved'){
                saveAnswer(typ,frmName);
            }
        } else {
            newId = parseInt(allElement[0])-1;
        }
        var getNewEId = $('#question_no_'+newId).attr('rel');
        var allElementNew = getNewEId.split('_');
        var qId = (parseInt(allElementNew[2]));
        var getRow = (parseInt(allElementNew[0]));
        getQuestion(getRow,qId,parseInt(allElementNew[1]));
    } else if(typ=='saved') {
        alert('You have traversed all the questions, Please click complete test to save assesment.');
    }
}

function saveAnswer(sts,formName){
    if(formName =='' || formName==undefined){
        formName = $('#next').attr('my-data');
    }
    if(sts=='CompleteTest'){
        if(confirm('Are you sure you want to submit the assessment.')){
            $.ajax({
                url: webURL + 'Questions/saveAnswers/',
                type: 'POST',
                data : $('#'+formName).serialize(),
                async:false,
                dataType:'json',
                success: function(data, textStatus, jqXHR){
                    if(data.status==1){
                        $('#AssignmentAttemptQuestionId').remove();
                        var html = '<input name="data[StudentTestQuestion][id]" value="'+data.lastId+'" id="AssignmentAttemptQuestionId" type="hidden">';
                        $(html).insertBefore('#qDiv');
                    }
                },
                complete : function(){
                    $.ajax({
                        url : webURL+'Users/contentAccessLog/',
                        type: 'POST',
                        data: {content_id:cId},
                        dataType:'json',
                        success: function(res){  
                            var jsonVal = res;//JSON.parse(res)
                            if(jsonVal.status == 1){
                                if(sts=='CompleteTest'){
                                    $('#counter').html('00:00:00');
                                    window.location.href = webURL+"Tests/completedTest/batchId:"+batchId+"/cId:"+cId;
                                } else if(sts=='TimeUp') {
                                    $('#counter').html('00:00:00');
                                    alert('Time UP');
                                    window.location.href = webURL+"Tests/completedTest/batchId:"+batchId+"/cId:"+cId;
                                } else {    
                                    if(sts !=='next' && sts !='previous'){
                                        getNextPrevQuestion('next','saved');
                                    }
                                }
                            }
                        }
                    });
                },
               error: function(data) {
                    alert('Somethig went wrong...');
                    return false;
                }
            });
        }
    } else {
        $.ajax({
            url: webURL + 'Questions/saveAnswers/',
            type: 'POST',
            data : $('#'+formName).serialize(),
            async:false,
            dataType:'json',
            success: function(data, textStatus, jqXHR){
                if(data.status==1){
                    $('#AssignmentAttemptQuestionId').remove();
                    var html = '<input name="data[StudentTestQuestion][id]" value="'+data.lastId+'" id="AssignmentAttemptQuestionId" type="hidden">';
                    $(html).insertBefore('#qDiv');
                }
            },
            complete : function(){
                $.ajax({
                        url : webURL+'Users/contentAccessLog/',
                        type: 'POST',
                        data: {content_id:cId},
                        dataType:'json',
                        success: function(res){  
                           var jsonVal = res;//JSON.parse(res)
                           if(jsonVal.status == 1){
                                if(sts=='CompleteTest'){
                                    $('#counter').html('00:00:00');
                                    window.location.href = webURL+"Tests/completedTest/batchId:"+batchId+"/cId:"+cId;
                                } else if(sts=='TimeUp') {
                                    $('#counter').html('00:00:00');
                                    alert('Time UP');
                                    window.location.href = webURL+"Tests/completedTest/batchId:"+batchId+"/cId:"+cId;
                                } else {    
                                    if(sts !=='next' && sts !='previous'){
                                        getNextPrevQuestion('next','saved');
                                    }
                                }
                           }
                        }
                     });
            },
           error: function(data) {
                alert('Somethig went wrong...');
                return false;
            }
        });
    }
}
