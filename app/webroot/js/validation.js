$(document).ready(function () {
    $(".validateForm").on('click', function () { 
        if (modulePlannerContent) {
            //Enable dropdown of student list of  module planner contents
            $("#AddLearner").multiselect('enable');
            $("#student_id").multiselect('enable');
        }
        if($('#changeEditor').is(':checked')){
            for(instance in CKEDITOR.instances){
                CKEDITOR.instances[instance].updateElement();
            }
        }
        $.ajax({
            url: webURL + controllerName + "/" + controllerName.toLowerCase() + "_validation",
            type: 'POST',
            data: $("#" + formNameNew).serialize() + "&type=add",
            beforeSend: function (msg) {
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
                if (json.dataType != undefined && (json.dataType == 'AssignmentAdd' || json.dataType == 'AssignmentView') && $("input:file").val() == '') {//For Assignment
                    var fileObj = $("input:file");
                    var fileDivId = fileObj.attr('id');
                    $("#" + fileDivId + "Error").remove();
                    fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> Please select file to upload.  </div>");
                    flag = false;
                }

                if (json.dataType != undefined && (json.dataType == 'AssignmentAdd' || json.dataType == 'AssignmentEdit' || json.dataType == 'AssignmentView')) {//For Assignment only
                    if ($("input:file").length && $("input:file").val() != '' && !validateFileAssignment($("input:file"), json.dataType)) {
                        flag = false;
                    }

                } else if (formNameNew=='LiveClassContentAddForm') {//For LiveClassContentAddForm
                    if ($("input:file").val() == ''){
                        flag = false;
                        $('.filename').after("<div id='" + fileDivId + "Error' class='redDiv'> Please select file to upload.  </div>");
                    } else if(fileTypeFilter == 1 ){ 
                        if(!validateFileAssignment($("input:file") )){
                            flag = false;
                        }
                    }else if(fileTypeFilter == 2 ){
                        if(!validateVideoFiles($("input:file"))){
                            flag = false;
                        }
                    }
                } else {
                    if ($("input:file").length && $("input:file").val() != '' && !validateFile($("input:file"))) {
                        flag = false;
                    }
                }
                if (flag) {
                    if (formNameNew=='TestSettingsForm'){
                        var pub_t = $('#pub_t').val();
                        if(pub_t==3 || pub_t==4){
                            if(confirm('This is Dynamic Assessment, Are you sure you want to publish it?')){
                                $("#" + formNameNew).submit();
                            }
                        } else {
                            $("#" + formNameNew).submit();
                        }
                    } else {
                        $("#" + formNameNew).submit();
                    }
                } else {
                    if (modulePlannerContent) {
                        $("#AddLearner").multiselect('disable');
                        $("#student_id").multiselect('disable');
                    }
                }
            }
        });
        return false;
    });


    /******fetch state based on selected Country ****/
    var image = webURL + "<?php echo 'img/ajax-loader-small.gif';?>";
    var defaultoption = '<option value="">Select</option>';
    $("#country_id").on('change', function (e) {
        $('#state_id').html(defaultoption);
        $('#city_id').html(defaultoption);
        var id = this.value;
        if (id == '') {
            return false;
        }
        $.get(webURL + "States/fetch/" + id, function (data) {
            if (data) {
                $('#state_id').html(data);
            } else {
                console.log("Error...");
            }
        });
    });

    /********** End **********/

    /******fetch City based on selected State ****/

    $("#state_id").on('change', function (e) {
        $('#city_id').html(defaultoption);
        var id = this.value;
        if (id == '') {
            return false;
        }
        $.get(webURL + "Cities/fetch/" + id, function (data) {
            if (data) {
                $('#city_id').html(data);
            } else {
                console.log("Error...");
            }
        });
    });
    /****** End  *****/
    /***** validate file uploaded extension *****/
    function validateFile(fileObj) {
        var file = fileObj.val();
        var ext = file.split(".");
        ext = ext[ext.length - 1].toLowerCase();
        var arrayExtensions = ["jpg", "jpeg", "png", "bmp", "gif"];
        console.log(ext);
        if (fileObj[0].files[0].size > globalSize) {
            var fileDivId = fileObj.attr('id');
            $("#" + fileDivId + "Error").remove();
            fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> File size exceeds 10 MB  </div>");

            return false;
        }
        if (formNameNew != "MessageInboxForm" && arrayExtensions.lastIndexOf(ext) == -1) {
            var fileDivId = fileObj.attr('id');
            $("#" + fileDivId + "Error").remove();
            fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> Acceptable file types: gif, jpg, png, jpeg.  </div>");
            return false;
        }
        return true;
    }


    function validateFileAssignment(fileObj, fileData) {
        var file = fileObj.val();
        var ext = file.split(".");
        ext = ext[ext.length - 1].toLowerCase();
        var arrayExtensions = ["pdf", "doc", "docx", "ppt", "pptx", "PDF", "DOC", "DOCX", "PPT", "PPTX", "jpg", "JPG", "jpeg", "JPEG", "gif", "GIF", "png", "PNG", "mp4", "MP4"];

        if (fileObj[0].files[0].size > globalSize) {
            var fileDivId = fileObj.attr('id');
            $("#" + fileDivId + "Error").remove();
            fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> File size exceeds 10 MB  </div>");

            return false;
        }
        if (arrayExtensions.lastIndexOf(ext) == -1) {
            var fileDivId = fileObj.attr('id');
            $("#" + fileDivId + "Error").remove();
            fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> Acceptable file pdf, doc, docx, ppt, pptx, jpeg, gif, png, mp4 only.</div>");
            return false;
        }

        if ($('#attachlistError').length) {
            return false;
        }
        return true;
    }
    
    function validateVideoFiles(fileObj, fileData) {
        var file = fileObj.val();
        var ext = file.split(".");
        ext = ext[ext.length - 1].toLowerCase();
        var arrayExtensions = ["mp4", "mpeg"];

        if (fileObj[0].files[0].size > globalSize) {
            var fileDivId = fileObj.attr('id');
            $("#" + fileDivId + "Error").remove();
            fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> File size exceeds 10 MB  </div>");

            return false;
        }
        if (arrayExtensions.lastIndexOf(ext) == -1) {
            var fileDivId = fileObj.attr('id');
            $("#" + fileDivId + "Error").remove();
            fileObj.after("<div id='" + fileDivId + "Error' class='redDiv'> Acceptable file pdf, doc, docx, ppt, pptx only.  </div>");
            return false;
        }

        if ($('#attachlistError').length) {
            return false;
        }
        return true;
    }
    
    

//    /****** End ***********

    /******fetch Department based on selected Institute ****/

    $("#institute_list").on('change', function (e) {
        var defaultoption1 = '<option value="">Select Department</option>';
        $('#department_id').html(defaultoption1);
        var id = this.value;
        if (id == '') {
            return false;
        }
        $.get(webURL + "Departments/fetch/" + id, function (data) {
            if (data) {
                $('#department_id').html(data);
            } else {
                console.log("Error...");
            }
        });
    });
    /****** End  *****/


    /******fetch Student based on name and not usiggned to batch ****/
    $("#all").on('click', function () {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function () {
                this.checked = true;
            });
        }
    });

    /******** Start Batch User ajax calls *************/

    $("#searchUsers").on('click', function (e) {

        var name = $('#search_name').val();
        
        if (batch_id == '' || batch_id == 0) {
            //return false;
        }
        if (name != '') {
            $.get(webURL + "batches/searchUsers", {batch_id: batch_id, semester_id: semester_id, name: name, role: roleId}, function (data) {
                if (data) {
//                 console.log(data);
                    $('#user_list_table_div').html(data);
                    // $("#loader").hide(); 
                } else
                {
                    console.log("Error...");
                }
            });
        } else {
            if ($('#flashMessage').html()) {
                $('#flashMessage').html('Please fil out the field to search');
            } else {
                $('#flashMsg').append('<div id="flashMessage">Please fil out the field to search</div>');
            }
            $('.successMessage').show();
        }
    });

    $("body").on('click', '.assign_single_user', function (e) {
        e.preventDefault();
        var users = new Array();
        users.push($(this).attr('id'));
        var action = 'assignUserBatch';
		if(assignOnSem==1){
			action = 'assignUser';
		}
			
        $.post(webURL + "batches/" + action, {semester_id: semester_id, batch_id: batch_id, users: users, type: assignmentType}, function (data) {
            if (data) {
                window.location.href = '';
            } else {
                $("#assignusermsg").text('Error! unable to assign users into batch');
                console.log("Error...");
            }
        });
        return false;
    });

    /***** Bulk assignment of student *************/
    $("body").on('click', '#assignStudentBtn', function (e) {
        var users = new Array();
        users = pre_checked_users_list;
        if (users.length == 0) {
            $("#assignusermsg").text('Error! Please select a user to assign into batch');
            return false
        }
        var action = 'assignUser';
		if(batch_id!=''){
			var action = 'assignUserBatch';
		}
        $.post(webURL + "Batches/" + action, {semester_id: semester_id, batch_id: batch_id,users: users, type: assignmentType}, function (data) {
            if (data) {
				console.log(data);
                $("#assignusermsg").text('success! selected users assigned into batch')
                window.location.href = '';
            } else {
                $("#assignusermsg").text('Error! unable to assign users into batch');
                console.log("Error...");
            }
        });
    });


    /***** End bulk assignment of student to batch *************/


    /********* ajax pagination for search Users ********/

    $("#user_list_table_div").on('click', '.column-title a, li a', function (e) {
        e.preventDefault();
        if (this.href == '') {
            return;
        }
        $.get(this.href, function (data) {
            if (data) {
                $('#user_list_table_div').html(data);
                populate_pre_selected(pre_checked_users_list);
            } else
            {
                console.log("Error...");
            }
        });
    });

    /***********End of ajax pagination  ************/

});

