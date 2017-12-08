
<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Agent Name</th>                
                <th>Customer Code</th>
                <th>Expense</th> 
                <th>Date</th>
            </tr>
        </thead>        
        <tbody>
        	<?php if(count($expenseInfo) > 0){ ?>
	        	<?php foreach ($expenseInfo as $key => $value) { ?>
		            <tr>
                        <td><?php echo $value['User']['fname']; ?></td>
		                <td><?php echo $value['Code']['name']; ?></td>
		                <td><?php echo $value['Expense']['expense']; ?></td>
		                <td><?php echo date('d-m-Y H:m', strtotime($value['Expense']['created'])); ?></td>
		            </tr>
		        <?php } ?>
            <?php }else{ ?>
	            <tr>
	                <td colspan="4" align="center">No Recond Found</td>	                
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
    