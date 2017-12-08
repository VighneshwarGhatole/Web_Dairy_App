$("#AssignmentAddForm").validate({
	highlight: function(element, errorClass) {
		$(element).css({ 'border': '1px solid #900' });
	},
	unhighlight: function(element, errorClass) {
		$(element).css({ 'border': '1px solid #CCC' });
	},
	submitHandler: function(form){
	      if(finalSaveAll()){
		$("#submit").prop('disabled',true);
		//$("#loader_submit").show();
		form.submit();
	      }
	}
});
$("#AssignmentEditForm").validate({
	highlight: function(element, errorClass) {
		$(element).css({ 'border': '1px solid #900' });
	},
	unhighlight: function(element, errorClass) {
		$(element).css({ 'border': '1px solid #CCC' });
	},
	submitHandler: function(form){
	      if(finalSaveAll()){
		$("#submit").prop('disabled',true);
		//$("#loader_submit").show();
		form.submit();
	      }
	}
});
$("#AssignmentSettingsForm").validate({
	highlight: function(element, errorClass) {
		$(element).css({ 'border': '1px solid #900' });
	},
	unhighlight: function(element, errorClass) {
		$(element).css({ 'border': '1px solid #CCC' });
	},
	submitHandler: function(form){
	      if(finalSaveAll()){
		$("#submit").prop('disabled',true);
		//$("#loader_submit").show();
		form.submit();
	      }
	}
});