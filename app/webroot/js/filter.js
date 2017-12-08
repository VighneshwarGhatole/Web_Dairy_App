$(document).ready(function(){
        $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        $('.filter-button').removeClass('btn-primary').not($(this)).addClass('btn-default');
        $(this).removeClass('btn-default');
        $(this).addClass('btn-primary');
        $('.no_record_div').remove();
        if(value == "all"){
            $('.filter').show('1000');
        }
        else{
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            if ($('.filter').length>0){
//                console.log('div.'+value);
                if($('li.'+value).length>0){
                    console.log('record found in li');
                }else if($('div.'+value).length<1){
                    var no_record_div = '<div class="panel openpanel md-planer bdr no_record_div"><div class="row"><div class="col-xs-12 col-md-12"><div class="sec-activity"><div class="sec-panel"><div class="col-xs-2 col-md-12">No Records found</div></div></div></div></div></div>';
//                    $('#Tab_LiveClass').append(no_record_div);
                    $('.tab-pane.active').append(no_record_div);
                    console.log('no data');
                }
            }
        }
    });
});