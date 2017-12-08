$(document).ready(function () {
    if( $('#is_negative').is(':checked')){
        $('#isNegativeDiv').show();
    }else{
      $('#isNegativeDiv').hide();
    }
    $('#is_negative').on('change', function(e) {
        $('#isNegativeDiv').toggle();
    });
});

$(function () {
        var today = new Date();     //Mon Nov 25 2013 14:13:55 GMT+0530 (IST) 
        var todayDateOnly = new Date(today.getFullYear(),today.getMonth(),today.getDate()); //This will write a Date with time set to 00:00:00 so you kind of have date only

        var sDate = new Date(startD);
        if (endD == '')
        {		
            var eDate = '';
            var end_date = '';
        }
        else
        {    
            var eDate = new Date(endD);
            var end_date = new Date(eDate.getFullYear(),eDate.getMonth(),eDate.getDate());
        }
        var start_date = new Date(sDate.getFullYear(),sDate.getMonth(),sDate.getDate());

        var todayStartDate;
        if((sDate !='0000-00-00 00:0000') && (start_date !='')){
            if(start_date > todayDateOnly){
                todayStartDate = todayDateOnly;
            } else if(start_date < todayDateOnly) {
                todayStartDate = todayDateOnly;
            } else {
                todayStartDate = start_date;
            }
        } else {
            todayStartDate = todayDateOnly;
        }
        var todayEndDate;
        if((eDate !='0000-00-00 00:0000') && (eDate !='')){
            if(end_date > todayDateOnly){
                todayEndDate = todayDateOnly;
            } else if(end_date < todayDateOnly){
                todayEndDate = todayDateOnly;
            } else {
                todayEndDate = end_date;
            }
        } else {
            todayEndDate = todayDateOnly;
        }
        
        if(pContentMin==0){
            todayStartDate = todayDateOnly;
            todayEndDate = todayDateOnly;
        } else {
            todayStartDate = new Date(pContentMinD);
            todayEndDate = new Date(pContentMinD);
        }
        
        var maxD = new Date('2050-12-31 00:00:00');
        if(pContentMax==1){
            maxD = new Date(pContentMaxD);
        }
        
        var opts = {
            format: "YYYY-MM-DD",
            stepping: 5,
            minDate: todayStartDate,
            maxDate: maxD,
            useCurrent: true,
            sideBySide: false,
            showTodayButton: true,
            widgetPositioning: {
                'vertical': 'bottom'
            },
            ignoreReadonly: true,
        };
        var opte = {
            format: "YYYY-MM-DD",
            stepping: 5,
            minDate: todayEndDate,
            maxDate: maxD,
            useCurrent: true,
            sideBySide: false,
            showTodayButton: true,
            widgetPositioning: {
                'vertical': 'bottom'
            },
            ignoreReadonly: true,
        };
        $('#start_date').datetimepicker(opts);
        $('#end_date').datetimepicker(opte);
});