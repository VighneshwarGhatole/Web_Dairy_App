
<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Agent Name</th>                
                <th>Customer Code</th>
                <th>CRL</th>
                <th>FAT</th>
                <th>SNF</th>
                <th>LTR</th>
                <th>Price</th>
                <th>More</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>        
        <tbody>
        	<?php if(count($entryInfo) > 0){ ?>
	        	<?php foreach ($entryInfo as $key => $value) { ?>
		            <tr>
                        <td><?php echo $value['User']['fname']; ?></td>
		                <td><?php echo $value['Code']['name']; ?></td>
		                <td><?php echo $value['Entry']['crl']; ?></td>
                        <td><?php echo $value['Entry']['fat']; ?></td>
                        <td><?php echo $value['Entry']['snf']; ?></td>
                        <td><?php echo $value['Entry']['ltr']; ?></td>
		                <td><?php echo $value['Entry']['price']; ?></td>
		                <td><?php echo $value['Entry']['more']; ?></td>
                        <td><?php echo $value['Entry']['total']; ?></td>
		                <td><span class="<?php echo (($value['Entry']['status']==1) ? 'active-status' : 'in-active-status'); ?>"><?php echo (($value['Entry']['status']==1) ? 'Active' : 'In-Active'); ?></span></td>
		                <td><?php echo date('d-m-Y H:m', strtotime($value['Entry']['created'])); ?></td>
		            </tr>
		        <?php } ?>
            <?php }else{ ?>
	            <tr>
	                <td colspan="11" align="center">No Recond Found</td>	                
	            </tr>
	        <?php } ?>           
            
        </tbody>
    </table>

<?php //echo $this->Html->script('jquery.dataTables.min'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });

</script>
    