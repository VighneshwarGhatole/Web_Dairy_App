<?php

/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author		  Hedayatullah Anwar
 */
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class CloneClassShell extends AppShell {

    var $uses = array('Batch','BatchStructure','Module', 'Content', 'Note','Test','TestQuestion','Weightage','UserSemesterMapping','UserBatchMapping','Assignment','UserContentMapping');
    private $RegEx_CharactersOnly = '/^[a-zA-Z\s]*$/';
    private $RegEx_Email = '/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{2,4}$/';

    /**
     * Function for cloninging subject(Batch)
     * @return boolean
     */
     function cloneSubject(){
		$newSubId = ''; 
		$dataSource = $this->Module->getDataSource();
        $dataSource->begin();
		 try{
			 $subjectId = $this->args[0];
			 $semesterId = $this->args[1];
			 $userId = isset($this->args[2])?$this->args[2]:0;
			 $newSubId = $this->args[3];
			 $oldnewmodule = $oldnewcontent = [];
			 
			 if(!empty($newSubId)){
					$sem = $this->Batch->query('select * from semesters where id ='.$semesterId);
					/****Structer cloning *****/
					$this->BatchStructure->recursive = -1;
					$structerclone = $this->BatchStructure->find('all',
					array(
						'conditions'=>array('BatchStructure.batch_id'=>$subjectId),
						'fields'=>array('BatchStructure.id','BatchStructure.batch_id','BatchStructure.name','BatchStructure.parent_id')
						)
					);
					
					//$structCloneToSave = [];
					$stid = 0;
					foreach($structerclone as $key=>$val){
						$this->Module->recursive = -1;
						$moduleclone = $this->Module->find('all',
						array(
							'conditions'=>array('Module.batch_id'=>$subjectId,'Module.structure_id'=>$val['BatchStructure']['id']),
							'fields'=>array('Module.id','Module.batch_id','Module.structure_id','Module.name','Module.parent_id','Module.is_batchlevel','Module.status')
							)
						);
						
						
						$strData = [];
						$strData['name'] = $val['BatchStructure']['name'];
						$strData['batch_id'] = $newSubId;
						$strData['parent_id'] = $stid;
						
						
						$this->BatchStructure->create();
						if($this->BatchStructure->save($strData,false)){
							$stid = $this->BatchStructure->id;
							/****Structer cloning done *****/
							
							/****Module cloning *****/
							$modelData = [];
							
							foreach($moduleclone as $k=>$mdata){
								$modelData['batch_id'] = $newSubId;
								$modelData['structure_id'] = $stid;
								$modelData['name'] = $mdata['Module']['name'];
								$modelData['parent_id'] = 0;	
								$modelData['is_batchlevel'] = $mdata['Module']['is_batchlevel'];
								$modelData['status'] = $mdata['Module']['status'];
								//pr($mdata);
								$this->Module->create();
								$this->Module->save($modelData,false);
								//var_dump($this->Module->id);
								
								$oldnewmodule[$mdata['Module']['id']] = $this->Module->id;
								
							}
							/****Module cloning done *****/
							
							//var_dump($this->Module->validationErrors);
							
						}
						
						
						
					}
					
					
				
					
					/**Content Clone**/
					$this->Content->recursive = -1;
					$contentToClone = $this->Content->find('all',
					array(
						'conditions'=>array('Content.batch_id'=>$subjectId,'Content.is_deleted'=>0),
						//'fields'=>array('Content.id','Content.batch_id','Content.module_id','Content.title','Content.description','Content.content_type_id','Content.ref_type','Content.content_path','Content.content_duration','allow_download','Content.parent_id')
						)
					);
					$filespath = [];
					foreach($contentToClone as $key=>$cont){
						
						if($cont['Content']['content_type_id'] != Configure::read('ContentTypes.LIVE_CLASS') && $cont['Content']['content_type_id'] != Configure::read('ContentTypes.DISCUSSION')){
						
						$contId= $cont['Content']['id'];
						$cont['Content']['id']='';
						$cont['Content']['batch_id']=$newSubId;
						$cont['Content']['module_id']=$oldnewmodule[$cont['Content']['module_id']];
						//$cont['Content']['step_completed']='';
						$cont['Content']['modified_by']='';
						$cont['Content']['created_by']=$userId;
						$cont['Content']['avg_completion_percentage']='';
						$cont['Content']['start_date']=$sem[0]['semesters']['start_date'];
						$cont['Content']['end_date']=$sem[0]['semesters']['end_date'];
						//$cont['Content']['status']=0;
						
				
						if($cont['Content']['parent_id'] != 0){
							$cont['Content']['parent_id'] = $oldnewcontent[$cont['Content']['parent_id']];
						}
						
						$this->Content->create();
						$this->Content->save($cont['Content'],false);
						$oldnewcontent[$contId]=$this->Content->id;
						
						//user_content_mappings
						$this->userContentMappingClone($contId,$this->Content->id);
						//pr($cont);
						if($cont['Content']['content_type_id'] == Configure::read('ContentTypes.NOTES')){
							$this->filesClone($cont['Content']['content_path'],$this->Content->id);
							$this->notesClone($contId,$this->Content->id);
							
						}else if($cont['Content']['content_type_id'] == Configure::read('ContentTypes.VIDEO')){
							$this->filesClone($cont['Content']['content_path'],$this->Content->id);
						}else if($cont['Content']['content_type_id'] == Configure::read('ContentTypes.ASSIGNMENT')){
							//$this->filesClone($cont['Content']['content_path'],$this->Content->id);
							$this->assignmentClone($cont['Content']['content_path'],$this->Content->id,$contId);
							
						}else if($cont['Content']['content_type_id'] == Configure::read('ContentTypes.ASSESSMENT')){
							$this->testClone($contId,$this->Content->id);
						}
						
					}
					}
					
					
					
					/***Call weightage clone***/
					$allWeightage = $this->Weightage->find('all',array('conditions'=>array('Weightage.batch_id'=>$subjectId)));
					$data = [];
					foreach($allWeightage as $key=>$oneWeight){
						
						if($oneWeight['Weightage']['parent_id']==0){
						$data['parent_id']=$oneWeight['Weightage']['parent_id'];
						$data['module_id']=$oldnewmodule[$oneWeight['Weightage']['module_id']];
						$data['no_weightage']=$oneWeight['Weightage']['no_weightage'];
						$data['batch_id']=$newSubId;
						
						}else{
						$data['parent_id']=$oldnewmodule[$oneWeight['Weightage']['module_id']];
						$data['content_id']=$oldnewcontent[$oneWeight['Weightage']['content_id']];
						$data['no_weightage']=$oneWeight['Weightage']['no_weightage'];
						$data['batch_id']=$newSubId;
						}
						//pr($data);
						$wtadd = $this->Weightage->saveWeightage($data);
						//pr($wtadd);
					}
					
					/***sink user to batch**/
					$this->Batch->sinkUser($semesterId,$newSubId);
					$dataSource->commit();
					$this->Batch->id = $newSubId;
					$this->Batch->saveField('clone_status',1);
					//$this->weightageClone($subjectId,$newSubId,$oldnewmodule,$oldnewcontent);
				
			}
			//pr($oldnewcontent);
			//pr($oldnewmodule);
			/*pr($batchclone['Batch']);
			
			$this->Batch->set($batchclone['Batch']);
			var_dump($this->Batch->save());
			var_dump($this->Batch->validationErrors);*/
			
			
			
			
		}
		catch (Exception $e) {
				//pr($e);
		
			$dataSource->rollback();
            $this->Batch->id = $newSubId;
            $this->Batch->saveField('clone_status', 2);
            $this->Batch->saveField('clone_failed_reason', $e->getMessage());
		}	
		
		
		
		//pr($this->Batch);
		//pr($lead_id);
		return TRUE;
	 }
	 
	 public function userContentMappingClone($contId,$newContId){
		//$contId = $this->args[0];
		//$newContId = $this->args[1];
		$this->UserContentMapping->recursive=-1;
		$notesToClone = $this->UserContentMapping->find('all',array('conditions'=>array('UserContentMapping.content_id'=>$contId)));
		foreach($notesToClone as $get){
			$get['UserContentMapping']['id'] = '';
			$get['UserContentMapping']['content_id'] = $newContId;
			$get['UserContentMapping']['completion_percentage'] = 0;
			
			$this->UserContentMapping->create();
			$this->UserContentMapping->save($get['UserContentMapping'],false);
			
		}
		//$log = $this->UserContentMapping->getDataSource()->getLog(false, false);
		//debug($log);
	 }
	 
	 /* 
	  * call to assign all semester's user to new batch(subject)
	  * */
	 public function sinkUser($semId,$newSubId){
		//$semId = $this->args[0];
		//$newSubId = $this->args[1];
		if(!empty($semId) && !empty($newSubId)){
		 $this->UserSemesterMapping->recursive=-1;
		 $semUserList = $this->UserSemesterMapping->find('all', array('conditions' => array('UserSemesterMapping.semester_id' => $semId)));
		 $formData = [];
		 $i=0;
		 
		 foreach ($semUserList as $val) {
                $formData[$i]['batch_id'] = $newSubId;
                $formData[$i]['user_id'] = $val['UserSemesterMapping']['user_id'];
                $formData[$i]['role_id'] = $val['UserSemesterMapping']['role_id'];
                $i++;
			}
			$outputArray = $this->UserBatchMapping->saveData($formData);
			
		}
	 }
	 
	 function notesClone($contId,$newContId){
			//$contId = $this->args[0];
		    //$newContId = $this->args[1];
			$notesToClone = $this->Note->find('all',array('conditions'=>array('Note.content_id'=>$contId)));
			foreach($notesToClone as $get){
				
				$name = explode('.',$get['Note']['content_path']);
				$newasset = $name[0].'_'.$newContId.'.'.$name[1];
				if(copy(FILE_PATH.$get['Note']['content_path'],FILE_PATH.$newasset)){
				chmod(FILE_PATH.$newasset,0777);
				
				$get['Note']['id'] = '';
				$get['Note']['content_id'] = $newContId;
				$get['Note']['content_path'] = $newasset;
				$this->Note->create();
				$this->Note->save($get['Note'],false);
				
				}
				
			}
	 }
	 
	 function filesClone($asset,$newContId){
		 //$asset = 'files/notes/233369594_kqdtoitfpujelvcqmxkxkqwb.pdf';
		 
		 $isfile = strpos($asset, 'http');
		 if($isfile === false && !empty($newContId)){
			 $name = explode('.',$asset);
			 $newasset = $name[0].'_'.$newContId.'.'.$name[1];
			 if(copy(FILE_PATH.$asset,FILE_PATH.$newasset)){
			 chmod(FILE_PATH.$newasset,0777);
			 $this->Content->query("update contents set content_path = '".$newasset."' where id = $newContId");
			}
		 }
		 
	 }
	 /**
	  * assignmentClone Method
	  * @param string $asset, int $newContId, int $oldContId
	  * */
	 function assignmentClone($asset,$newContId,$oldContId){
		
		  //$asset = $this->args[0];
		  //$newContId = $this->args[1];
		  //$oldContId = $this->args[2];
			$isfile = strpos($asset, 'http');
			if($isfile === false && !empty($newContId)){
			 $name = explode('.',$asset);
			 $newasset = $name[0].'_'.$newContId.'.'.$name[1];
			 if(copy(FILE_PATH.$asset,FILE_PATH.$newasset)){
				chmod(FILE_PATH.$newasset,0777);
				$this->Content->query("update contents set content_path = '".$newasset."' where id = $newContId");
				}
			}
			$allAssignment = $this->Assignment->find('first',array('conditions'=>array('Assignment.content_id'=>$oldContId)));
			$allAssignment['Assignment']['id']='';
			$allAssignment['Assignment']['created_on']=date('Y-m-d H:i:s');
			$allAssignment['Assignment']['content_id']=$newContId;
			$allAssignment['Assignment']['uploaded_path']=$newasset;
			//pr($allAssignment);
			$this->Assignment->save($allAssignment,false);
			/*$log = $this->Assignment->getDataSource()->getLog(false, false);
			debug($log);
			debug($this->Assignment->validationErrors);*/
			
			
			
		
	 }
	 function testClone($contId,$newContId){
		 //$contId = $this->args[0];
		 //$newContId = $this->args[1];
		 if(!empty($contId) && !empty($newContId)){
						 
			 $allTest = $this->Test->find('first',array('conditions'=>array('Test.content_id'=>$contId)));
			 $allTest['Test']['id']='';
			 $allTest['Test']['created_on']=date('Y-m-d H:i:s');
			 $allTest['Test']['modified_on']=date('Y-m-d H:i:s');
			 $allTest['Test']['content_id']=$newContId;
			 
			 $this->Test->save($allTest,false);
			 //debug($this->Test->validationErrors);
			 $testQ = $this->TestQuestion->find('all',array('conditions'=>array('TestQuestion.content_id'=>$contId)));
			 
			 
			 $testcont = [];
			 foreach($testQ as $k=>$tval){
			 $testcont[$k]['id']='';
			 $testcont[$k]['question_id']=$tval['TestQuestion']['question_id'];
			 $testcont[$k]['created_on']=date('Y-m-d H:i:s');
			 $testcont[$k]['content_id']=$newContId;
			 $testcont[$k]['test_id']=$this->Test->id;
			 }
			 $this->TestQuestion->saveAll($testcont);
			 //pr($testcont); 
			 //$log = $this->Test->getDataSource()->getLog(false, false);
			 //debug($log);
			 
		 }
	 }
	 
	 
  
}
