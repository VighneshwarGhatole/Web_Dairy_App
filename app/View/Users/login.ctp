<div class="lgnform">
    <div class="lgn-ttl"><img src="<?php echo $params['imgURL']; ?>/lgn-ttl.jpg"/></div>
        <div class="lgn-frm">
            <?php echo $this->Form->create('User', array('class' => 'form-signin new-form')); ?>
            <?php //echo $this->Flash->render(); ?>
            <?php if($error) {?>
            <div class="lgn-error"><?php echo $error;?></div>  
			<?php } ?>
                <div class="form-group">                            
                  <!-- <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email Id"> -->
                  <?php echo $this->Form->input('username', array('type'=>'text', 'id'=>'exampleInputEmail1', 'class'=>'form-control', 'label'=>false,
                          'placeholder'=>'Username', 'required'=>false)); ?>
                </div>
                <div class="form-group">                            
<!--                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">-->
                  <?php echo $this->Form->input('password', array('type' => 'password', 'id'=>'exampleInputPassword1', 'class'=>'form-control', 'label' => false,
                          'placeholder' => 'Password','required'=>false)); ?>
                </div>
                <div class="fgtpwd"><a class="pull-right" data-toggle="modal" data-target=".bs-example-modal-fgtpwd" href="#">Forgot Password</a></div>
                <div class="sbmtbtn"><button type="submit" class="btn btn-default">Sign In</button></div>
            <?php echo $this->Form->end(); ?>
        </div>
</div>
<script>
if (typeof(Storage) !== "undefined") {
localStorage.clear();
}
</script>
