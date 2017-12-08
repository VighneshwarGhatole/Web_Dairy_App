$('#bulkImportsReportBatchId').on("change",function(){
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
                if(data==''){
                    var data = '<option value=""> --Select Module-- </option>';
                }
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
function getdata(pageno){
    var batchId = $("#bulkImportsReportBatchId").val();
    var moduleId = $("#module_id").val();
    var quesType = $("#quesType").val();
    var fromDate = $("#fromDate").val();
    var toDate = $("#toDate").val();    
    $.ajax({
        beforeSend: function() { $("#loaderImg").show(); },
        url: webURL + 'BulkImportsReport/getQuestionBankData/',
        type: 'POST',
        data: "batch_id="+batchId+"&module_id="+moduleId+"&quesType="+quesType+"&fromDate="+fromDate+"&toDate="+toDate+'&page='+pageno,
        async:false,
        success: function(data, textStatus, jqXHR){
            $("#loaderImg").hide();
            $('#quesTemp').html(data);
        },
       error: function(data) {
           $("#loaderImg").hide();
            alert('Error...');
            return false;
        }
    });
}

function moveQuestion(){ 
    if(confirm('Are you Sure for Question Move?')){       
        var quesIds = [];
        $("input:checkbox[name='checkIds[]']:checked").each( function () {
            quesIds.push($(this).val()); 
        });
        
        if(quesIds.length>0){
        $.ajax({
		beforeSend: function() { $("#moveids").prop('disabled',true); },
                url:webURL+"BulkImportsReport/moveQuestionOneByOne/",
                data:"quesIds="+quesIds,
                type:"POST",
		async:false,
                success : function(data, textStatus, jqXHR)       
                { 
                    alert(data);
		    $("#moveids").prop('disabled',false);
                },
                statusCode: {
                    404: function() {
                        alert('page not found');
                    }
                }
            });
        }else{
            alert('Select Atleast One Question.');
            return false;
        }
    }    
}

function deleteQuestion(){ 
    if(confirm('Are you Sure for Question Delete?')){
        var quesIds = [];
        $("input:checkbox[name='checkIds[]']:checked").each( function () {
            quesIds.push($(this).val()); 
        });
        if(quesIds.length>0){
        $.ajax({
		beforeSend: function() { $("#deleteids").prop('disabled',true); },
                url:webURL+"BulkImportsReport/deleteQuestionOneByOne/",
                data:"quesIds="+quesIds,
		async:false,
                type:"POST",
                success : function(data, textStatus, jqXHR)       
                {
		    var msg = data.split("###");
                    alert(msg[0]);
		    var del = msg[1].split(',');
		    for($i=0;$i<(del.length);$i++){
			$('#qid_'+del[$i]).remove();
		    }
		    $("#deleteids").prop('disabled',false);
                },
                statusCode: {
                    404: function() {
                        alert('page not found');
                    }
                }
            });
        }else{
            alert('Select Atleast One Question.');
            return false;
        }    
    }     
}
function viewTempQuestion(qid,qtyp){
    $("#viewQuestion").html('');
    $('#spinner').show();
    $.ajax({
        type: "GET",
        url: webURL + "BulkImportsReport/view/"+qid+'/'+qtyp,
        success: function(data) {
            $("#viewQuestion").html(data);
            $("#viewQuestion").modal('show');
            $('#spinner').hide();
            window.MathJax = {};
        },
        error: function(data) {
            alert("failure");
            $('#spinner').hide();
            return false;
        }
    });
}
function viewQuestion(qid,qtyp){
    $("#viewQuestion").html('');
    $('#spinner').show();
    $.ajax({
        type: "GET",
        url: webURL + "Questions/view/"+qid+'/'+qtyp,
	async:false,
        success: function(data) {
            $("#viewQuestion").html(data);
            $("#viewQuestion").modal('show');
            $('#spinner').hide();
            window.MathJax = {};
        },
        error: function(data) {
            alert("failure");
            $('#spinner').hide();
            return false;
        }
    });
}

$(function () {
    // Check Uncheck All
    $("#check_uncheck_all").on('click', function(){
        $('.checkitem').prop('checked',this.checked);
    });
    $(".checkitem").on('click', function(){
        if($(".checkitem").length==$(".checkitem:checked").length){
          $("#check_uncheck_all").prop("checked",true);
        } else {
          $("#check_uncheck_all").prop("checked", false);
        }
    });
    
    var uploadfromDate = {
        format: "YYYY-MM-DD",
        stepping: 5,
        maxDate:maxD,
        useCurrent: true,
        sideBySide: false,
        showTodayButton: true,
        widgetPositioning: {
            'vertical': 'bottom'
        },
        ignoreReadonly: true,
    };
    var uploadtoDate = {
        format: "YYYY-MM-DD",
        stepping: 5,
        maxDate:maxD,
        useCurrent: true,
        sideBySide: false,
        showTodayButton: true,
        widgetPositioning: {
            'vertical': 'bottom'
        },
        ignoreReadonly: true,
    };
    var movedfromDate = {
        format: "YYYY-MM-DD",
        stepping: 5,
        maxDate:maxD,
        useCurrent: true,
        sideBySide: false,
        showTodayButton: true,
        widgetPositioning: {
            'vertical': 'bottom'
        },
        ignoreReadonly: true,
    };
    var movedtoDate = {
        format: "YYYY-MM-DD",
        stepping: 5,
        maxDate:maxD,
        useCurrent: true,
        sideBySide: false,
        showTodayButton: true,
        widgetPositioning: {
            'vertical': 'bottom'
        },
        ignoreReadonly: true,
    };
    
    var fromDate = {
        format: "YYYY-MM-DD",
        stepping: 5,
        useCurrent: true,
        sideBySide: false,
        showTodayButton: true,
        widgetPositioning: {
            'vertical': 'bottom'
        },
        ignoreReadonly: true,
    };
    var toDate = {
        format: "YYYY-MM-DD",
        stepping: 5,
        useCurrent: true,
        sideBySide: false,
        showTodayButton: true,
        widgetPositioning: {
            'vertical': 'bottom'
        },
        ignoreReadonly: true,
    };
    
    $('#uploadfromDate').datetimepicker(uploadfromDate).on('dp.change', function (e) {
        $('#uploadtoDate').data("DateTimePicker").minDate(e.date);
    });
    $('#uploadtoDate').datetimepicker(uploadtoDate).on('dp.change', function (e) {
        $('#uploadfromDate').data("DateTimePicker").maxDate(e.date);
    });
    $('#movedfromDate').datetimepicker(movedfromDate).on('dp.change', function (e) {
        $('#movedtoDate').data("DateTimePicker").minDate(e.date);
    });
    $('#movedtoDate').datetimepicker(movedtoDate).on('dp.change', function (e) {
        $('#movedfromDate').data("DateTimePicker").maxDate(e.date);
    });
    $('#fromDate').datetimepicker(fromDate).on('dp.change', function (e) {
        $('#toDate').data("DateTimePicker").minDate(e.date);
    });
    $('#toDate').datetimepicker(toDate).on('dp.change', function (e) {
        $('#fromDate').data("DateTimePicker").minDate(e.date);
    });
});