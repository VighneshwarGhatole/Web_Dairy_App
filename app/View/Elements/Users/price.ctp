
<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Agent Name</th>                
                <th>LFAT</th>
                <th>HFAT</th>
                <th>LSNF</th>
                <th>HSNF</th>
                <th>Start Price</th>
                <th>Intetval</th>
                <th>Type</th>
                <th>Time</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>        
        <tbody>
        	<?php if(count($priceInfo) > 0){ ?>
	        	<?php foreach ($priceInfo as $key => $value) { ?>
		            <tr>
                        <td><?php echo $value['User']['fname']; ?></td>
		                <td><?php echo $value['Price']['lfat']; ?></td>
		                <td><?php echo $value['Price']['hfat']; ?></td>
                        <td><?php echo $value['Price']['lsnf']; ?></td>
                        <td><?php echo $value['Price']['hsnf']; ?></td>
                        <td><?php echo $value['Price']['start_price']; ?></td>
		                <td><?php echo $value['Price']['intetval']; ?></td>
		                <td><?php echo (($value['Price']['type']==0) ? 'Cow' : 'Bufallow'); ?></td>
                        <td><?php echo (($value['Price']['time']==0) ? 'Morning' : 'Evening'); ?></td>
		                <td><span class="<?php echo (($value['Price']['status']==1) ? 'active-status' : 'in-active-status'); ?>"><?php echo (($value['Price']['status']==1) ? 'Active' : 'In-Active'); ?></span></td>
		                <td><?php echo date('d-m-Y H:m', strtotime($value['Price']['created'])); ?></td>
		            </tr>
		        <?php } ?>
            <?php }else{ ?>
	            <tr>
	                <td colspan="6" align="center">No Recond Found</td>	                
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
    