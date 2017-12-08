<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">View Question</h4>
    </div>
    <div class="modal-body">
      <div class="table-responsive mar-b">
	<table data-toggle="table" width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-bordered jambo_table">
	  <thead>
	    <tr>
	      <th>Batch Id</th>
	      <th>Batch Name</th>
	      <th>Module Id</th>
	      <th>Module Name</th>
	    </tr>
	  </thead>
	  <tbody class="ajaxHead">
	      <tr>
		  <td><?php echo $resQuestion['QuestionTemp']['batch_id']; ?></td>
		  <td><?php echo $resQuestion['Batch']['name'] ?></td>
		  <td><?php echo $resQuestion['QuestionTemp']['module_id']; ?></td>
		  <td><?php echo $resQuestion['Module']['name']; ?></td>
	      </tr>
	  </tbody>
	</table>
	<br />
	<table data-toggle="table" width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-bordered jambo_table">
	  <thead>
	    <tr>
	      <th>Question Type (Id)</th>
	      <th>Difficulty</th>
	      <th>Practice Test</th>
	      <th>Marks</th>
	      <th>Update status</th>
	    </tr>
	  </thead>
	  <tbody class="ajaxHead">
	      <tr>
		  <td><?php echo $resQuestion['QuestionType']['type'].' ('.$resQuestion['QuestionType']['id'].')'; ?></td>
		  <td>
		    <?php $arrOld = array(1=>'Easy',2=>'Medium',3=>'Hard',4=>'Low',5=>'High');?>
		    <?php echo $arrOld[$resQuestion['QuestionTemp']['difficulty_level_id']];?>
		  </td>
                  <td><?php echo ($resQuestion['QuestionTemp']['is_practice'])?'Yes':'No'; ?></td>
		  <td><?php echo $resQuestion['QuestionTemp']['marks']; ?></td>
		  <td>
		    <?php
		    $typ = $resQuestion['QuestionTemp']['updated_data'];
		    switch($typ){
			case 0:
			    echo "Default Question";
			    break;
			case 1:
			    echo "Replaced Question";
			    break;
			case 2:
			    echo "New Question";
			    break;
			case 3:
			    echo "Associated with test";
			    break;
			case 5:
			    echo "Non Mobile Ready";
			    break;
			case 9:
			    echo "Not associated with test.";
			    break;
		    }
		   ?>
		   <?php echo "( ".$typ." ) "; ?>
		  </td>
	      </tr>
	  </tbody>
	</table>
	<br>
	<table data-toggle="table" width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-bordered jambo_table">
            <thead>
              <tr>
                <th>Id</th>
                <th>Statement</th>
              </tr>
            </thead>
            <tbody class="ajaxContent">
		<tr>
		    <td><?php echo $resQuestion['QuestionTemp']['id']; ?></td>
		    <td class="latex"><?php echo $resQuestion['QuestionTemp']['statement']; ?></td>
		</tr>
            </tbody>
        </table>
	<br />
	<table data-toggle="table" width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-bordered jambo_table">
            <thead>
              <tr>
                <th>Id</th>
                <th>Statement</th>
                <th>Explanation</th>
		<th>Correct Option</th>
              </tr>
            </thead>
	    <tbody class="ajaxContent">
		<?php $i=1; foreach($resQuestion['QuestionOptionTemp'] as $key=>$value):?>
		<tr>
		    <td><?php echo $value['id']; ?></td>
		    <td class="latex"><?php echo $value['option_statement']; ?></td>
		    <td class="latex"><?php echo $value['explanation']; ?></td>
		    <td><?php echo ($value['is_correct_option'])?'Yes':'No';?></td>
		</tr>
		<?php unset($value);$i++; endforeach;?>
            </tbody>
        </table>
	<div class="clearfix"></div>
      </div>
      <div class="clear"></div>
    </div>
    <br/>
    <!--subject pop up start-->
  </div>
  <!-- /.modal-content -->
</div>