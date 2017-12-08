<!-- compose -->
<div class="compose col-md-5 col-xs-12">
  <div class="compose-header">
	New Message
	<button type="button" class="close compose-close">
	  <span>×</span>
	</button>
  </div>
<?php echo $this->Form->create('Message',array('url' =>array('controller'=>'Messages','action'=>'inbox'),'type' => 'file','class' => 'form-horizontal form-label-left', 'inputDefaults' => array(
						'label' => false,
						'required'=>false,
						'error' => array('attributes' => array('wrap' => 'div', 'class' => 'help-inline redDiv'))
						))); ?>
  <div class="compose-body" style="display:block;">          
	  <div class="lgn-frm">
		
			<div class="form-group" id="batchs">  
				
				 <?php //$students=array('Option1','Option2','Option3','Option4','Option5');?>
				 <?php echo $this->Form->input('batches', array('options' => $AllBatches,'empty' => '--Select batch--','class'=>"minimal form-control" ,'div'=>false));?>                 
				
				
				<div class="caretselect"><b class="caret"></b></div>
			</div>
			<div class="form-group"> 
			  <div class="multsl" id="to_id">
				<?php //$students=array('Option1','Option2','Option3','Option4','Option5');?>
				<?php echo $this->Form->hidden('attach',array('value'=>''));?>
				<?php echo $this->Form->input('to_id', array('multiple'=>"multiple",'id'=>'AddStudent','options' => $AllUser));?>
				  
				<div class="caretselect"><b class="caret"></b></div>                 
				
			</div>
			</div>
			<div class="form-group">    
				<?php echo $this->Form->input('subject',array('id'=>'subject','class'=>'form-control','placeholder'=>'Subject'));?>
			</div>
			<div class="form-group">
				<?php echo $this->Form->input('message',array('id'=>'message',"onkeyup"=>"textAreaAdjust(this)",'style'=>'overflow:hidden','Placeholder'=>'Message text here..','rows'=>"5"));?>
				
			</div>
			<div class="attached-files posabsolute" id="attachlist">
				
			</div>
			
		
	</div>
	  
  </div>

  <div class="compose-footer">
	
	<span class="attach-file">
		
	   <button type="button" class="btn btn-default"><i class="fa fa-paperclip" aria-hidden="true"></i> Attachment</button>
	  <?php echo $this->Form->file('file.', array('required'=>false,'class'=>'btn btn-default','type'=>'file','multiple'=>'multiple'));?>
	  
	</span> 
	<span class="button-checkbox raiseDoubtBlock" style="display:none;">
		<button type="button" class="btn gry mgrn-right" data-color="primary">Raise a doubt</button>
		
		<?php echo $this->Form->input('is_doubt',array('div'=>false,'label'=>false,'class'=>"hidden"));?>
	</span>
	<button id="send" class="btn btn-sm btn-primary validateFormOuterCompose pull-right" type="submit">Send</button>
  </div>
  </form>
</div>

<script>
	var formNameNew = "MessageInboxForm";
	var validatedFiles = [];
	var totalfile = 0;
	$("#MessageFile").on("change", function (event) {
		//$('#attachlist').html("");		
		$('#attachlistError').remove();		
		
		var files = event.originalEvent.target.files;		
		if(files.length > <?php echo Configure::Read('GlobalSettings.MAX_NUMBER_OF_FILE_UPLOAD');?>){
			//event.preventDefault();
			//alert('Can not attach more than 3 files.');
			$('#attachlist').append('<div id="attachlistError" style="color: red;text-align: right;"> Can not attach more than 3 files.</div>');
		}else{
		$.each(files,function(idx,elm){
			if(totalfile<3){
				var sizeerror='';
				if(elm.size > <?php echo (Configure::Read('GlobalSettings.FILE_UPLOAD_SIZE_MB')*1024*1024);?>){	
						
					$('#attachlist').append('<div id="attachlistError" style="color: red;text-align: right;"> File size exceeds 10MB.</div>');		
				}else{
					if (!elm.name.toLowerCase().match(/\.(ppt|pptx|xlsx|xls|doc|docx|pdf|gif|jpeg|jpg|png)$/)){
						$('#attachlist').append('<div id="attachlistError" style="color: red;text-align: right;">File type invalid. </div>');
					}else{
						var fl= document.getElementById('MessageFile');
						sendRequest(idx,fl,totalfile);
						validatedFiles[idx]=elm;
						console.log(idx);
					   
						$('#attachlist').append('<div class="attach-file-show" id="attachment' +totalfile+ '"><div class="file-name"><a class="flname" href="#">' + elm.name + '</a> <span>' + Math.round(elm.size/1024)+ 'KB</span><span class="pull-right">' + sizeerror + '<a href="javascript:void(0);" onClick="removeit(' +totalfile+ ');"><i class="fa fa-times" aria-hidden="true"></i></a></span> <div class="progress"><div class="progress-bar" id="fileprogress' +totalfile + '" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div></div></div></div>');
						totalfile++;
					
					}
				}
				
			}else{
				$('#attachlist').append('<div id="attachlistError" style="color: red;text-align: right;"> Can not attach more than 3 files.</div>');
			}
        });
	 }
	});
	// Send request to server
function sendRequest(i,fl,p){
    
	var curFileName = fl.files.item(i).name;
	$("#fname").html(curFileName);
	$("#progBar").show();
	 var formData = new FormData();
	 var fileD = document.getElementById('MessageFile').files[i];
	 formData.append('file', fileD);
	   $.ajax({
	      url: '<?php echo $this->webroot;?>Messages/uploadAttachment',
	      type: 'POST',
	      data: formData,
	      processData: false, 
	      contentType: false,
	      dataType: 'json',
	      xhr: function() {
		            var myXhr = $.ajaxSettings.xhr();
		            if(myXhr.upload){
		                //myXhr.upload.addEventListener('progress',progress, false);
		                myXhr.upload.addEventListener('progress', function (e) {
							$("#fileprogress" + p).attr({style:"width:"+Math.ceil(e.loaded / e.total) * 100 + "%"});
							//document.getElementById("prog" + i ).value = Math.ceil(e.loaded / e.total) * 100;
						}, false);
		            }
		            return myXhr;
		        },
	      success: function(res) {
			  //console.log(res);
			  if(res.type=='success') {
				var oldValue = $("#MessageAttach").val();
				var arr = oldValue === "" ? [] : oldValue.split(',');
				arr.push(res.fileName);
				var newValue = arr.join(',');

				$("#MessageAttach").val(newValue);
				//$("#MessageAttach").push(res.fileName);
				//unset the file
				$("#MessageFile").replaceWith($("#MessageFile").val('').clone(true));
				
			}if(res.type=='error') {
				totalfile--;
				$('#attachment' +totalfile).remove();
				$('#attachlist').append('<div id="attachlistError" style="color: red;text-align: right;">'+ res.msg +'</div>');
				
				
			}
			//console.log($("#MessageAttach").val());
	      },
	      error: function(e) {
		 alert(e.status+" error occurred to upload image!");
		//window.location.href=window.location.href;
	      }    
	   }); 	  
     
}
 // Delete function
function removeit(no) {
	
    var oldValue = $("#MessageAttach").val();
	var arr = oldValue === "" ? [] : oldValue.split(',');
	//console.log($("#MessageAttach").val());
	//alert($("#attachment"+no).index());			
    $.ajax({
        url:'<?php echo $this->webroot;?>Messages/removeAttachment',
        type:'POST',
        dataType: 'json',
        data:{del:1,filePath:arr[$("#attachment"+no).index()]},
        success:function(res){
                if(res===1) {
					totalfile--;
					//delete arr[$("#attachment"+no).index()];
					arr.splice($("#attachment"+no).index(),1);
					var newValue = arr.join(',');
					
					
					$("#MessageAttach").val(newValue);
					
					$("#attachment" + no).remove();
					if(no==1 && $('#attachment2').length){
						$("#attachment2").attr("id","attachment1");
						$("#fileprogress2").attr("id","fileprogress1");
						$("#attachment1 .pull-right").html('<a href="javascript:void(0);" onclick="removeit(1);"><i class="fa fa-times" aria-hidden="true"></i></a>');
					}
					if(no==0){
						if($('#attachment1').length){
						$("#attachment1").attr("id","attachment0");
						$("#fileprogress1").attr("id","fileprogress0");
						$("#attachment0 .pull-right").html('<a href="javascript:void(0);" onclick="removeit(0);"><i class="fa fa-times" aria-hidden="true"></i></a>');
						}
						if($('#attachment2').length){
						$("#attachment2").attr("id","attachment1");
						$("#fileprogress2").attr("id","fileprogress1");
						$("#attachment1 .pull-right").html('<a href="javascript:void(0);" onclick="removeit(1);"><i class="fa fa-times" aria-hidden="true"></i></a>');
						}
					}
					
					
                  }
                  
                },
        error: function(e) {
                alert(e.status+" error occurred to delete file!");
                //window.location.href=window.location.href;
               } 
    });
}

 // Proress bar
 function progress(e){
        if(e.lengthComputable){
            $('progress').attr({value:e.loaded,max:e.total});
            var percentage = (e.loaded / e.total) * 100;
            $('#prog').html(percentage.toFixed(0)+'%');
        }
    }
    
  //Reset progress bar
    function resetProgressBar() {
        $('#prog').html('0%');
        $('progress').attr({value:0,max:100});
    }
</script>
<script>
// Initialize multiselect plugin:
$(document).ready(function() {        
		$('#AddStudent').multiselect({
		includeSelectAllOption: true,
		numberDisplayed: 6,
		nonSelectedText: 'To',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true
	});  
	$("#MessageBatches").on("change", function (event) {
	var valuesD = {batchId:$("#MessageBatches").val()}
	$.ajax({
			url: "<?php echo $this->webroot;?>Messages/finduserbybatch",
			type: "post",
			data: valuesD ,
			success: function (response) {
				
				var newOptions = JSON.parse(response);
                                $('#MessageIsDoubt').prop('checked', false);
                                if(newOptions != '')
                                {
                                    if(newOptions.isLoggedInStudent == 1)$('.raiseDoubtBlock').show();
                                    else $('.raiseDoubtBlock').hide();

                                    var $el = $("#AddStudent");
                                    $el.empty(); // remove old options

                                    $.each(newOptions.external, function(key,value) {
                                      $el.append($("<option></option>")
                                             .attr("value", key).text(value));
                                    });

                                    if(Object.keys(newOptions.srm).length > 0){
                                    $el.append($("<optgroup label='SRM'></optgroup>"));
                                    $.each(newOptions.srm, function(key,value) {
                                      $el.append($("<option></option>")
                                             .attr("value", key).text(value));
                                    });
                                    }
                                    //$("#AddStudent").multiselect('destroy');				
                                    //$("#AddStudent").multiselect("selectAll",true);
                                    $("#AddStudent").multiselect("rebuild");

                                    //$('#AddStudent').multiselect('dataprovider', newOptions);
                                    <?php if(isset($user_id)){?>
                                            $('#AddStudent').multiselect('select', ['<?php echo $user_id;?>']);
                                                    //$('#AddStudent option[value="<?php //echo $srm_id;?>"]').attr('selected',true);
                                    <?php }?>
                                }
                                else 
                                {   
                                    $('.raiseDoubtBlock').hide();
                                    $("#AddStudent").empty();
                                }
			},
			error: function(jqXHR, textStatus, errorThrown) {
			   console.log(textStatus, errorThrown);
			}
		});
	});
	
	
	$('.compose-close').click(function(){
		$('.compose').slideToggle();
	});
	
	  $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-box')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-box');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
    
    
     $(".validateFormOuterCompose").on('click', function () {

        $.ajax({
            url: webURL + "Messages/messages_validation",
            type: 'POST',
            data: $("#" + formNameNew).serialize() + "&type=add",
            beforeSend: function(msg){
			$("#loaderImg").show();
		  },
            success: function (data) {
				$("#loaderImg").hide();
                flag = true;
                var json = JSON.parse(data);
                if (json.status == "error") {
                    $(".redDiv").remove();

                    $.each(json.errors, function (index, element) {
                        $("#" + index + "Error").remove();
                        $("#" + index).after("<div id='" + index + "Error' class='redDiv'>" + element + "</div>");
                    });
                    flag = false;
                } else if (json.status == "success") {
                    $(".redDiv").remove();
                    flag = true;
                }
                if (json.dataType != undefined && (json.dataType=='AssignmentAdd' || json.dataType=='AssignmentView') && $("input:file").val() == '') {//For Assignment
					var fileObj = $("input:file");
					var fileDivId = fileObj.attr('id');
					$("#" + fileDivId + "Error").remove();
					fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> Please select file to upload.  </div>");
					flag = false;			
				} 
				
				if (json.dataType != undefined && (json.dataType=='AssignmentAdd' || json.dataType=='AssignmentEdit' || json.dataType=='AssignmentView') ) {//For Assignment only
					if ($("input:file").length && $("input:file").val() != '' && !validateFileAssignment($("input:file"), json.dataType)) {
						flag = false;
					}
				
				} else {
					if ($("input:file").length && $("input:file").val() != '' && !validateFile($("input:file"))) {
						flag = false;
					}
				}
                if (flag) {
                    $("#" + formNameNew).submit();
                }
            }
        });
        return false;
    });
	<?php if(isset($batchForSrm)){?>
			$("#MessageBatches").val(<?php echo $batchForSrm;?>);
			$("#MessageBatches").trigger("change");
	<?php }?>
});
</script>
<!-- /compose -->
