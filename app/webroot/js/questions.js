$(document).ready(function() { 
    var currentId = $(".box-pnl a.active").attr('id');
    if(currentId != undefined){
        var getEId = $('#'+currentId).attr('rel');
        var allElement = getEId.split('_');
        $('#next').show();
        $('#previous').show();
        $('#addMarksBlock').hide();
        if((typeof(totalAddedQuestions) != 'undefined' && typeof(totalAddedQuestions) != 'undefined') && totalAddedQuestions==totalQuestions){
            $('#addMarksBlock').show();
        }
        if(allElement[0]==1){
            $('#previous').hide();
        } else if(allElement[0]==allElement[1]) {
            var remaning_ques = $('#QuestionRemainingQuestion').val();
            if(remaning_ques==1 || remaning_ques==0){
                //$('#publishBlock').show();
                //$('#addMarksBlock').show();
            }
            $('#next').hide();
        }
    }
});
function getQuestionTemplate(typId,questionId,rId){
    if(typId!=''){
        $('#addMarksBlock').hide();
	$('#question_template').html('');
        var templateType = 'add';
        if(questionId !=0){
            templateType = 'edit';
        }
        var is_editor = 0;
        if($('#changeEditor').is(':checked')){
            is_editor = 1;
            if(typId==5){
                $('#changeEditor').prop('checked',false);
                is_editor = 0;
            }
        }
	$.ajax({
	    url: webURL + 'Questions/questionTemplates/'+typId+'/'+templateType+'/0/'+is_editor,
	    type: 'POST',
	    async:false,
	    success: function(data, textStatus, jqXHR){
                $('#question_type_id').val(typId);
                if(rId != 'question_type_id'){
                    $('.box-number').removeClass('active');
                    $('#'+rId).addClass('active');
                    var getEId = $('#'+rId).attr('rel');
                    var allElement = getEId.split('_');
                    $('#next').show();
                    $('#previous').show();
                    if(allElement[0]==1){
                        $('#previous').hide();
                    } else if(allElement[0]==allElement[1]) {
                        var remaning_ques = $('#QuestionRemainingQuestion').val();
                        if(remaning_ques==1 || remaning_ques==0){
                            //$('#publishBlock').show();
                            //$('#addMarksBlock').show();
                        }
                        $('#next').hide();
                    }
                }
                $("#question_type_id").prop('disabled', false);
		$('#question_template').html(data);
                if((typeof(totalAddedQuestions) != 'undefined' && typeof(totalAddedQuestions) != 'undefined') && totalAddedQuestions==totalQuestions){
                    $('#addMarksBlock').show();
                }
	    },
	   error: function(data) {
		alert('Template Error...');
		return false;
	    }
	});
    } else {
	$('#question_template').html('');
    }
}
/*Remove Textarea [START]*/
function removeTexarea(idVal,opId){
    if(opId>0){
        var cfm = confirm("Want to delete this option?") 
        if(cfm){
            $.ajax({
                url: webURL + 'Questions/removeAnswer/'+opId,
                type: 'POST',
                async:false,
                dataType:'json',
                success: function(data, textStatus, jqXHR){
                    alert(data.message);
                    if(data.status==1){
                        $( "#div_"+idVal).remove();
                    }
                },
               error: function(data) {
                    alert('Template Error...');
                    return false;
                }
            });
        }
    } else {
        $( "#div_"+idVal).remove();
    }
    
}
/*Remove Textarea [END]*/
$("#next").on("click", function(){
    var currentId = $(".box-pnl a.active").next().attr('id');
    var getEId = $('#'+currentId).attr('rel');
    var allElement = getEId.split('_');
    $('#previous').show();
    $('#next').show();
    $('#addMarksBlock').hide();
    if(totalAddedQuestions==totalQuestions){
        $('#addMarksBlock').show();
    }
    if(allElement[0]==1){
        $('#previous').hide();
    }
    if(allElement[0]==allElement[1]){
        var remaning_ques = $('#QuestionRemainingQuestion').val();
        $('#next').hide();
    }
    $('#'+currentId).click();
    $('.box-number').removeClass('active');
    $('#'+currentId).addClass('active');
});

$("#previous").on("click", function(){
    var currentId = $(".box-pnl a.active").prev().attr('id');
    var getEId = $('#'+currentId).attr('rel');
    var allElement = getEId.split('_');
    $('#next').show();
    $('#previous').show();
    $('#addMarksBlock').hide();
    if(totalAddedQuestions==totalQuestions){
        $('#addMarksBlock').show();
    }
    if(allElement[0]==1){
        $('#previous').hide();
    }
    if(allElement[0]==allElement[1]){
        var remaning_ques = $('#QuestionRemainingQuestion').val();
        if(remaning_ques==1 || remaning_ques==0){
            //$('#publishBlock').show();
            //$('#addMarksBlock').show();
        }
    }
    $('#'+currentId).click();
    $('.box-number').removeClass('active');
    $('#'+currentId).addClass('active');

});

function getQuestionDetail(typId,quesId,lvlId,mode){
    $('.box-number').removeClass('active');
    $('#ques_'+quesId).addClass('active');
    $('#is_practice').val(mode);
    $('#difficulty_level_id_'+lvlId).prop('checked',true);
    $('#is_practiceError').hide();
    if(typId!=''){
        var getEId = $('#ques_'+quesId).attr('rel');
        var allElement = getEId.split('_');
        $('#next').show();
        $('#previous').show();
        $('#addMarksBlock').hide();
        if(allElement[0]==1){
            $('#previous').hide();
        } else if(allElement[0]==allElement[1]) {
            $('#next').hide();
            var remaning_ques = $('#QuestionRemainingQuestion').val();
            if(remaning_ques==1 || remaning_ques==0){
                //$('#publishBlock').show();
                //$('#addMarksBlock').show();
            }
        }
        $('#question_type_id').val(typId);
        $.ajax({
	    url: webURL + 'Questions/questionTemplates/'+typId+'/edit/'+quesId,
	    type: 'POST',
	    async:false,
	    success: function(data, textStatus, jqXHR){
                $("#question_type_id").prop('disabled', true);
                $("#changeEditor").prop('disabled', true);
		$('#question_template').html(data);
                if(totalAddedQuestions==totalQuestions){
                    $('#addMarksBlock').show();
                }
	    },
	   error: function(data) {
		alert('Template Error...');
		return false;
	    }
	});
    } else {
	$('#question_template').html('');
    }
}

$('#batch_id').on("change",function(){
    var batchId = $(this).val();
    if(batchId>0){
        $('#searchQues').removeAttr('disabled');
        $.ajax({
            beforeSend: function() { $("#loaderImg").show(); },
	    url: webURL + 'Questions/getModuleList/'+batchId,
	    type: 'POST',
	    async:false,
	    success: function(data, textStatus, jqXHR){
                $("#loaderImg").hide();
		$('#module_id').html(data);
	    },
	   error: function(data) {
               $("#loaderImg").hide();
		alert('Error...');
		return false;
	    }
	});
    } else {
        $('#searchQues').attr('disabled','disabled');
        var html = '<option value="">Select Module</option>';
        $('#module_id').html(html);
    }
});

$('#searchQues').on("click",function(){
    var batchId = $("#batch_id").val();
    var moduleId = $("#module_id").val();
    var contentId = $("#TestQuestionContentId").val();
    if(batchId>0 && contentId>0){
        $('#saveq').removeAttr('disabled');
        $.ajax({
            beforeSend: function() { $("#loaderImg").show(); },
	    url: webURL + 'Questions/getQuestionBank/',
	    type: 'POST',
            data: "batchId="+batchId+"&moduleId="+moduleId+"&contentId="+contentId+'&page=1',
	    async:false,
	    success: function(data, textStatus, jqXHR){
                $("#loaderImg").hide();
		$('#question_bank_list').html(data);
                MathJax.Hub.Queue(["Typeset",MathJax.Hub,'ques_dashboard']);
	    },
	   error: function(data) {
               $("#loaderImg").hide();
		alert('Error...');
		return false;
	    }
	});
    } else {
        $('#saveq').attr('disabled','disabled');
        alert('Please select batch id first');
    }
});

function getdata(pageno){
    var batchId = $("#batch_id").val();
    var moduleId = $("#module_id").val();
    var contentId = $("#TestQuestionContentId").val();
    if(batchId>0 && contentId>0){
        $('#saveq').removeAttr('disabled');
        $.ajax({
            beforeSend: function() { $("#loaderImg").show(); },
	    url: webURL + 'Questions/getQuestionBank/',
	    type: 'POST',
            data: "batchId="+batchId+"&moduleId="+moduleId+"&contentId="+contentId+'&page='+pageno,
	    async:false,
	    success: function(data, textStatus, jqXHR){
                $("#loaderImg").hide();
		$('#question_bank_list').html(data);
	    },
	   error: function(data) {
               $("#loaderImg").hide();
		alert('Error...');
		return false;
	    }
	});
    } else {
        $('#saveq').attr('disabled','disabled');
        alert('Please select batch id first');
    }
}

function saveTestQ(totalQ,totalAddedQ,cId){
    var remaningQ = totalQ - totalAddedQ;
    var fields 	= $("input[name='data[TestQuestion][question_id][]']:checked").length; 
    if (fields == 0) { 
	alert('nothing selected');
	return false;
    } else if(fields>remaningQ) {
        alert('You can select only '+remaningQ+' question');
	return false;
    } else { 
	$.ajax({
	    url : webURL+'Questions/mapTestQuestions/',
	    data: $("#TestQuestionManageQuestionsForm").serialize(),
	    type: 'POST',
            dataType: 'JSON',
	    success : function(data, textStatus, jqXHR) {
                alert(data.message);
		if(data.status==1){
		    window.location.assign(webURL+"Questions/manageQuestions/cId:"+cId);
		}
	    },
	    statusCode: {
		404: function() {
		    alert('page not found');
		}
	    }
	});
	return false;
    }
}

function removeQ(cId){
   var currentId = $(".box-pnl a.active").attr('rel1');
    if(currentId>0){
        if(confirm('Are you sure, Want to unmap this question?')){
           $.ajax({
                url : webURL+'Questions/unmapQuestion/',
                data: 'id='+currentId,
                type: 'POST',
                dataType: 'JSON',
                success : function(data, textStatus, jqXHR) {
                    alert(data.message);
                    if(data.status==1){
                        window.location.assign(webURL+"Questions/manageQuestions/cId:"+cId);
                    }
                },
                statusCode: {
                    404: function() {
                        alert('page not found');
                    }
                }
            });
            return false;
        }
    }
}

function viewBank(id){
    $('#vbank_'+id).toggle();
}

function changeEdi(){
    var selBoxVal = $('#question_type_id').val();
    getQuestionTemplate(selBoxVal,0,'question_type_id');
}

/*Load Editor File*/
function loadEditor(eid){
    CKEDITOR.replace(eid, {
    extraPlugins:'justify',
    allowedContent: true,
    filebrowserBrowseUrl: FILE_PATH,
    filebrowserWindowWidth : '1000',
    filebrowserWindowHeight : '700',
    height: "80px"
    });
    CKEDITOR.add
}
