<div role="tabpanel" class="tab-pane fade active in" id="Tab_Modules" aria-labelledby="home-tab">
	<div class="col-xs-12 col-sm-8 col-md-7">
		<div class="student-profile">
			<?php if(isset($socialProfileDetails->publicProfileUrl) && !empty($socialProfileDetails->publicProfileUrl)) {
                            $dataVanity = explode('/', $socialProfileDetails->publicProfileUrl);
                            $dataVanity = end($dataVanity);
                        echo '<script type="text/javascript" src="https://platform.linkedin.com/badges/js/profile.js" async defer></script>';
			echo '<div class="LI-profile-badge"  data-version="v1" data-size="medium" data-locale="en_US" data-type="horizontal" data-theme="light" data-vanity="'.$dataVanity.'"><a class="LI-simple-link" href="'.$socialProfileDetails->publicProfileUrl.'">'.$socialProfileDetails->firstName.' '.$socialProfileDetails->lastName.'</a></div>';
//				echo '<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>';
//				echo '<script type="IN/MemberProfile" data-id="'.$socialProfileDetails->publicProfileUrl.'" data-format="inline" data-related="false"></script>';
			} else {?>
			<div class="std-profile">
				<div class="com-pic">
					<a href="javascript:;">
						
						<img src="<?php echo (!empty($userDetails['User']['pic'])) ? $this->Common->showImage(ASSETS_BASE_URL.$userDetails['User']['pic'], '150') : $params['imgURL'].'/img.jpg'; ?>" alt="">
					</a>
				</div>
			</div>
			<div class="std-info">
				<div class="std-name"><?php echo $userDetails['User']['fname'].' '.$userDetails['User']['lname']?></div>
				<div><?php echo $roleOfBatch;?></div>
				<?php 
				if(!empty($userDetails['UserExperience'])){
					$current=0;
					foreach($userDetails['UserExperience'] as $exp){
						if($exp['current']==1){
						echo '<div><span>Company:</span> '.$exp['company'].'</div>';
						$current = 1;
					 }
					 
					 }
					 echo (!$current)?'<div><span>Previous:</span> '.$userDetails['UserExperience'][0]['company'].'</div>':'';
				 }
					 ?>
				<div><span>Education:</span>  
				<?php 
				$edu = '';
				foreach($userDetails['UserEducation'] as $exp){$edu = $edu.' '.$exp['degree'].',';}
				echo rtrim($edu,',');
				?></div>
			</div>  
			<?php }?>                                                             
			<div class="col-xs-12 col-md-12">
				<div class="ln_solid"></div>                                    
			</div>
			<div class="col-xs-12 col-md-12">
				<div class="summary">
					<div class="summery-ttl">SUMMARY</div>
					<div class="summery-desc"><?php echo $userDetails['User']['profile_summary'];?></div>
				</div>                                    
			</div>
			<div class="col-xs-12 col-md-12">
				<div class="Experience-sec">
					<div class="Experience-sec-ttl">EXPERIENCE</div>
					<?php foreach($userDetails['UserExperience'] as $exp){?>
					<div class="Experience-sec-list">
						<div class="exp-ttl"><?php echo $exp['designation'];?></div>
						<div class="exp-profile"><?php echo $exp['company'];?></div>
						<div class="exp-year"><?php echo $this->Common->showDateAndTime(strtotime($exp['start_date'])).' - ';
						echo ($exp['current']) ?' Present' :  $this->Common->showDateAndTime(strtotime($exp['end_date']));?> </div>
						
					</div>
				   <?php }?>
				</div>                                    
			</div>
			<!--<div class="col-xs-12 col-md-12">
				<div class="Experience-sec">
					<div class="Experience-sec-ttl">SKILLS</div>
					<div class="Experience-sec-list">
						<div class="exp-ttl">Top Skills</div>
						<div class="skills-list">
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
							<div class="list">Team Management</div>
							<div class="list">E-Learning</div>
						</div>
					</div>                                            
					<a href="#" class="more">More</a>
				</div>                                    
			</div>-->
			<div class="col-xs-12 col-md-12">
				<div class="Experience-sec">
					<div class="Experience-sec-ttl">EDUCATION</div>
					 <?php foreach($userDetails['UserEducation'] as $exp){?>
					<div class="Experience-sec-list">
						<div class="exp-ttl"><?php echo $exp['university'];?></div>
						<div class="exp-profile"><?php echo $exp['degree'];?></div>
						<div class="exp-year"><?php echo $this->Common->showDateAndTime(strtotime($exp['start_date'])).' - '.$this->Common->showDateAndTime(strtotime($exp['end_date']));?></div>                                                
					</div>
					<?php }?>
				   
				</div>                                    
			</div>
		</div> 
	</div>
	<div class="col-xs-12 col-sm-4 col-md-5">
		<div class="student-performance">
			<a href="<?php echo $this->Html->url($backurl);?>" class="pull-right"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a>
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<div class="pull-left">
					<?php if($batchId && $allowCoursePerformanceBlock){?>
					<div class="course-perform">COURSE PERFORMANCE</div>
					<div class="course-desc"><span><?php 
					$courseData = $this->Common->getBatchCourseAndInstituteName($batchId);
					echo ucfirst($courseData[0]['i']['institute_name']);?> </span> - <?php echo ucfirst($courseData[0]['c']['course_name']);?></div>
					<?php }?>
				</div>
				<?php if($this->Session->read('UserDetails.id') != $userId && ($this->Session->read('UserDetails.roleId') == Configure::read('UserRoles.EXTERNAL') || $this->Session->read('UserDetails.roleId') == Configure::read('UserRoles.SRM')))
					{?>
				<div class="pull-right"><a class="send-msg" href="javascript:void(0)" onclick="loadExternalCompose(<?php echo $batchId.",".$userId;?>);">SEND MESSAGE</a></div>
				<?php }else{?>
				<div class="pull-right"><a class="send-msg" href="<?php echo $this->Html->url('/Users/edit/'.$userId);?>" >EDIT PROFILE</a></div>	
				<?php }?>
			</div>
		</div>
		<?php if($batchId && $allowCoursePerformanceBlock){?>
		<div class="row graph">
			<div class="col-xs-12 col-md-12">
					<div class="sec-bdy student">
					<div class="row">                                       
						<div class="col-xs-12 col-md-4">
							<div class="sec-pnl"> 
								<?php $totalPercentageOfAttendance = (isset($batchUsersLiveClassPercentage['resultData'][$userId]['totalPercentage']) && isset($batchUsersLiveClassPercentage['resultData'][$userId]['totalCount'])) ? str_replace('.0', '', number_format(($batchUsersLiveClassPercentage['resultData'][$userId]['totalPercentage'] / $batchUsersLiveClassPercentage['resultData'][$userId]['totalCount']), 1)) : 0; ?>                                            
								<div class="c100 p<?php echo (int)$totalPercentageOfAttendance;?> small">
									<span><?php echo $totalPercentageOfAttendance;?>%</span>
									<div class="slice">
										<div class="bar"></div>
										<div class="fill"></div>
									</div>
								</div>
								<div class="txt">ATTENDANCE</div>
							</div>
						</div><?php //pr($batchUsersLiveClassPercentage);?>
						<div class="col-xs-12 col-md-4">
							<div class="sec-pnl">                                                        
								<div class="cmn-sec">
								   <img src="<?php echo $params['imgURL']; ?>/sub_dash.png"/>
								   <div class="number"><?php 
								 $cnt = empty($batchUsersAssignmentCounts['resultData'][$userId]['totalCount'])?0:$batchUsersAssignmentCounts['resultData'][$userId]['totalCount'];
								 $att = empty($batchUsersAssignmentCounts['resultData'][$userId]['attempted'])?0:$batchUsersAssignmentCounts['resultData'][$userId]['attempted'];
								   
								 echo $att.'/'.$cnt; 
								   ?></div>
								</div>

								<div class="txt">ASSIGNMENT COMPLETED</div>
							</div>
						</div>
						
						<div class="col-xs-12 col-md-4">
							<div class="sec-pnl">
								<div class="cmn-sec">
								   <img style="height: 43px;" src="<?php echo $params['imgURL']; ?>/Assessment_dash.png"/>
								   <div class="number" style="margin-left: -20px;"><?php
									$assAtt = (!empty($batchUsersAssessmentCounts['resultData'][$userId]['attempted'])) ? $batchUsersAssessmentCounts['resultData'][$userId]['attempted'] : 0;
									$assCnt = (!empty($batchUsersAssessmentCounts['resultData'][$userId]['totalCount'])) ? $batchUsersAssessmentCounts['resultData'][$userId]['totalCount'] : 0;
									echo $assAtt.'/'.$assCnt; 
									?></div>
								</div>
								<div class="txt">ASSESSMENT COMPLETED</div>
							</div>
						</div>
						<div class="col-xs-12 col-md-4">
					<div class="sec-pnl">
						<?php //pr($batchUsersModuleCompletion['resultData']);?>
						<div class="txt drft-stg">Pending <span><?php echo (!empty($batchUsersModuleCompletion['resultData'][$userId])) ? (100 - $batchUsersModuleCompletion['resultData'][$userId]) : 0; ?>%</span></div>
						<div class="mid-cen">
							<div class="pie-wrapper"> 
							<div class="arc" data-value="<?php echo (!empty($batchUsersModuleCompletion['resultData'][$userId]))? $batchUsersModuleCompletion['resultData'][$userId] : 0; ?>"></div>
							<span class="score"><?php echo (!empty($batchUsersModuleCompletion['resultData'][$userId]))? $batchUsersModuleCompletion['resultData'][$userId] : 0; ?>%</span>
							</div>
						</div>                                                 
						<div class="txt">MODULE COMPLETED</div>
					</div>
				</div>
					</div>

				</div>
			</div>
		</div>
		<?php }?>
		</div>
	</div>                          
</div>
                     
