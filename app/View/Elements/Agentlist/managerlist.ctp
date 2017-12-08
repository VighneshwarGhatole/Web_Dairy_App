<style>
    .tooltips {position: relative;display: inline-block;}

    .tooltips .tooltiptext {visibility: hidden;
        width: 300px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;        
        transition: opacity 1s;
    }

    .tooltips .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .tooltips:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>
<table id="example" class="table table-striped table-bordered" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>#</th>
        <th>Manager Name</th>       
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

    <?php if(count($Users) > 0) { $counter=1; ?>
        <?php  foreach ($Users as $key => $user) { ?>
                <tr>
                    <td><?php echo $counter; ?></td>
                    <td>
                        <div class="tooltips">
                            <?php echo ucwords($user['User']['fname']); ?> [
                            <?php 
                                if(count($user['ChildGroup']) > 0){ 
                                    echo '<span class="active-status">'.count($user['ChildGroup']).'</span>'; 
                                }else{ 
                                    echo '<span class="in-active-status">0</span>';
                                }
                            ?>]
                            <?php if(!empty($user['ChildGroup'])){ ?>                            
                                <span class="tooltiptext" style="padding-left: 10px;">                                
                                    <table>
                                        <tr>
                                            <th>#</th>
                                            <th>Agent List</th>
                                        </tr>
                                        <?php foreach ($user['ChildGroup'] as $key => $value) { ?>
                                            <tr>
                                                <td>*</td>
                                                <td><?php echo ucwords($value['fname']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </span>
                            <?php } ?>
                        </div>
                    </td>      
                    <td><?php echo base64_decode($user['User']['agentId']); ?></td>
                    <td><?php echo base64_decode($user['User']['password']); ?></td>
                    <td><?php echo $user['User']['email']; ?></td>
                    <td><?php echo $user['User']['mobile_no']; ?></td>
                    <td class="change-status" id="<?php echo $user['User']['id']; ?>">
                        <?php echo (($user['User']['status']==1) ? '<span class="active-status">Active</span>' : '<span class="in-active-status">In-active</span>'); ?>                     
                    </td>
                    <td>
                        <a href="<?php echo $this->webroot; ?>Users/edit/id:<?php echo $user['User']['id']; ?>" title="Edit">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a href="<?php echo $this->webroot; ?>Users/delete/id:<?php echo $user['User']['id']; ?>" title="Delete">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                    </td>
                </tr>
            
        <?php $counter++; } ?>
    <?php   }else{ ?>
        <tr>
            <td colspan="5" align="center">No Record Found</td>
        </tr>
     <?php } ?>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });
    $('.change-status').on('click', function(e){
        var uId = this.id;
        if(confirm('Do you want to modify status?')){
            $("#loaderImg").show();
            $.get(webURL + "Users/updatemanagerstatus/uId:" + uId, function (data) {
                var jdata = JSON.parse(data);               
                if (jdata.status==1) {
                    if(jdata.message==='Active'){
                        $('#'+uId).html("<span class='active-status'>Active</span>");                       
                    }else{
                        $('#'+uId).html("<span class='in-active-status'>In-active</span>");  
                    }        
                } else{
                    alert('Sorry!! Not Authorized');
                }
                $("#loaderImg").hide();
            });
        }
    });

</script>

