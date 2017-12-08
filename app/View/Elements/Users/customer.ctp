
<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Customer Code</th>                
                <th>Cutomer Name</th>
                <th>Agent Name</th>
                <th>Type</th>
                <th>Phone No</th>
                <th>Date</th>
            </tr>
        </thead>        
        <tbody>
        	<?php if(count($customerInfo) > 0){ ?>
	        	<?php foreach ($customerInfo as $key => $value) { ?>
		            <tr>
		                <td><?php echo $value['Code']['id']; ?></td>
		                <td><?php echo $value['Code']['name']; ?></td>
		                <td><?php echo $value['User']['fname']; ?></td>
		                <td><?php echo (($value['Code']['type']==0) ? 'Cow' : 'Bufallow'); ?></td>
		                <td><?php echo $value['Code']['mobile_no']; ?></td>
		                <td><?php echo date('d-m-Y H:m', strtotime($value['Code']['created'])); ?></td>
		            </tr>
		        <?php } ?>
            <?php }else{ ?>
	            <tr>
	                <td colspan="6" align="center">No Recond Found</td>	                
	            </tr>
	        <?php } ?>           
            
        </tbody>
    </table>
<?php //echo $this->Html->script(array('jquery.dataTables.min.js')); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });

</script>
    