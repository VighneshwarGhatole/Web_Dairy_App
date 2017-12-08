<?php
$showSRMSection = 0;
$arrBatches = $this->Session->read('Batches.STUDENT');
if (!empty ($arrBatches)) {
	$arrAllSRM = $this->Common->prepairConnectSRMData($arrBatches);
	if (!empty ($arrAllSRM)) $showSRMSection = 1;
}

if ($showSRMSection) {	
  $actualImg = ( $arrAllSRM[0]['pic'] == '') ? $params['imgURL'].'/img.jpg' : ASSETS_BASE_URL.$arrAllSRM[0]['pic'];
  $actualImg = $this->Common->showImage($actualImg, '300');			 
			 
?>
<!-- SRM LINK -->
<div class="srm-pnl">               
   <img class="srm-pic" src="<?php echo $actualImg;?>" alt="Counselor">
	<div class="name"><?php echo $arrAllSRM[0]['name'];?></div>
	<div class="profile">your academic counselor</div>
	 <div class="connect"><a href="javascript:void(0)" onclick="loadExternalCompose(<?php echo $arrAllSRM[0]['batch_id'].",".$arrAllSRM[0]['id'];?>);"><img src="<?php echo $params['imgURL']; ?>/connect.png"/>Connect</a>   </div>               
</div>

<?php } ?>
