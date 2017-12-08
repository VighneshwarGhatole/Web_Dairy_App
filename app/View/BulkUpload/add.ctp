<div class="page-header">
	<div class="page-title nt-tab">
	  <div class="title_left">
		  <div class="com-profile">
				<div class="com-pic sm-spc">
					<div class="icn-circle"><i class="fa fa-upload" aria-hidden="true"></i></div>
				</div>
				<div class="com-detail">
					<h3><a href="#"><span>Bulk Upload</span></a></h3>
					<ol class="breadcrumb">
						<li><a href="<?php echo $this->webroot;?>Admin/dashboard">Home</a></li>
						<li class="active">Bulk upload user</li>
					</ol>
				</div>                        
			</div>		
	  </div>	 
	  <!--<div class="title_right">
		<div class="col-md-12 col-sm-12 col-xs-12 form-group pull-right top_search">
		  <div class="input-group">
			<input type="text" class="form-control" placeholder="Search for...">
			<span class="input-group-btn">
			  <button class="btn btn-default" type="button">Go!</button>
			</span>
		  </div>
		</div>
	  </div> -->
	</div><!-- End page-title-->
	<div class="clearfix"></div>	
	<div class="row">
       <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel flt-left">
            
            <div class="x_content">
              <br />
              <!--<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">-->
				<?php echo $this->Form->create('BulkUpload', array('type' => 'file','class' => 'form-horizontal form-label-left'));?>			
                 <div class="form-group">
                   <label class="control-label col-md-3 col-sm-3 col-xs-12">Upload File <span class="required">*</span>
                   </label>
                   <div class="col-md-6 col-sm-6 col-xs-12">
					    <?php  echo $this->Form->file('file');?>
					    <span>Supported file type is .CSV</span>
                   </div>
                </div>    
                      
                <div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
					<button type="reset" class="btn btn-primary">Cancel</button>
					<button type="submit" class="btn btn-success" id="sbmt">Submit</button>
					</div>
				</div>
			<?php echo $this->Form->end;?>
			<div class="ln_solid"></div>                
				
			<!-- LISTING-->
		<div class="col-xs-12 col-md-12">
          <div class="tbl-list">
			<div class="table-responsive">
			  <table class="table table-striped table-bordered jambo_table ">
				<thead>
				  <tr class="headings">
					<th class="column-title"><?php echo $this->Paginator->sort('name', 'File Name&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?></th>
					<!--<th class="column-title">Type </th>-->
					<th class="column-title"><?php echo $this->Paginator->sort('created', 'Upload Date&nbsp;<i class="fa fa-sort" aria-hidden="true"></i>', ['escape' => false]); ?></th>
					<th class="column-title">Status</th>                           
				  </tr>
				</thead>

				<tbody>
					<?php if (!empty($uploadFileData)) {
							$i = 1;
							foreach ($uploadFileData as $key => $uploadedDataArray) {
								$className = ($i%2 == 0) ? "even pointer" : "odd pointer";
					?>
									<tr class="<?php echo $className;?>">
										<td><a href="<?php echo $this->webroot?>BulkUpload/downloadFile/<?php echo $uploadedDataArray['BulkUpload']['id'];?>"><?php echo $uploadedDataArray['BulkUpload']['name'];?></a></td>
										<!--<td><?php //echo $uploadedDataArray['BulkUpload']['type'];?></td>-->
										<td><?php echo date('d-m-Y', strtotime($uploadedDataArray['BulkUpload']['created']));?></td>
										<td class="last">
											<div class="btn-group pnl-setting">
												<a data-target="#" href="#" class="btn btn-default btn-xs" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
													<span class="glyphicon glyphicon-cog"></span>
												</a>
												<ul class="dropdown-menu" aria-labelledby="dLabel">
													<?php 
														if ($uploadedDataArray['BulkUpload']['status'] == 0) {
															echo 'Not Processed '. '<li>'.$this->Html->link(__('Process it'), array('action' => 'processUploadedFile', $uploadedDataArray['BulkUpload']['id'])).'</li>';														
														} else if ($uploadedDataArray['BulkUpload']['status'] == 1) {
															echo '<b>Processed</b>';
															echo '<li>'.$this->Html->link(__('Download Sheet'), array('action' => 'downloadFile', $uploadedDataArray['BulkUpload']['id'], 1)).'</li>';
														} else if ($uploadedDataArray['BulkUpload']['status'] == 5) {
															echo '<b>Intermediate</b>';
														} else if ($uploadedDataArray['BulkUpload']['status'] == 2) {
															echo '<b>Error found during processing!</b>';
														}
													?>
												</ul>
                                             </div>
									</div>
					<?php
								$i++;
							}
						} else {
							echo "<tr><td colspan='4'>No record founds.</td></tr>";
						}
					?>                                                 
				</tbody>
			  </table>
			</div>
			<!--/LISTING-->	
			 <?php echo $this->element('pagination'); ?>
			</div>
			</div>		
          </div>
        </div>
      </div>
    </div> <!-- /row -->
    
</div><!-- /class -->
<script>
$(document).ready(function(){
	$('#sbmt').click(function (){
		fileName = $('#BulkUploadFile').val();
		if (fileName != '') {
			var ext = fileName.split('.').pop()
			if (ext == 'csv' || ext == '.csv') {
				return true;
			} else {
				alert('Not a valid file fomat, Please upload .CSV file.');
				return false;
			}
		} else {
			alert('Please select file.');
			return false;
		}
		return false;
		
	});
});
</script>
