<?php if ($this->Session->read('UserDetails.roleId') == 1) {?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <div class="logo">
            <a><img class="fullmenuLogo" src="<?php echo $params['imgURL']; ?>/logo.png"/><img class="smallmenuLogo" src="<?php echo $params['imgURL']; ?>/logo-icn.png"/></a>
			<a class="cross-sign"><i class="fa fa-times" aria-hidden="true"></i>
        </div>  
        <ul class="nav side-menu">  
            <li class="<?php echo (($tabList==='mList') ? 'current-page' : ''); ?>">
                <a href="<?php echo $this->webroot;?>Users/managerlist" title="Manager List"><i class="fa fa-graduation-cap"></i> <span class="lable">Manager List</span></a>
            </li>

            <li class="<?php echo (($tabList==='aList') ? 'current-page' : ''); ?>">
                <a href="<?php echo $this->webroot;?>Users/agentlist" title="Agent List"><i class="fa fa-th-large"></i> <span class="lable">Agent List</span></a>
            </li>

            <li class="<?php echo (($tabList==='cList') ? 'current-page' : ''); ?>" >
                <a href="<?php echo $this->webroot;?>Users/customer" title="Customer List"><i class="fa fa-users"></i> <span class="lable">Customers</span></a>
            </li>

            <li class="<?php echo (($tabList==='pList') ? 'current-page' : ''); ?>" >
                <a href="<?php echo $this->webroot;?>Users/price" title="Price List"><i class="fa fa-database"></i> <span class="lable">Price</span></a>
            </li>

            <li class="<?php echo (($tabList==='eList') ? 'current-page' : ''); ?>" >
                <a href="<?php echo $this->webroot;?>Users/entry" title="Entry"><i class="fa fa-pencil-square-o"></i> <span class="lable">Entry</span></a>
            </li>

            <li class="<?php echo (($tabList==='exList') ? 'current-page' : ''); ?>" >
                <a href="<?php echo $this->webroot;?>Users/expense" title="Expenses"><i class="fa fa-university"></i> <span class="lable">Expenses</span></a>
            </li>
            
        </ul>
    </div>
</div>
<?php } ?>
