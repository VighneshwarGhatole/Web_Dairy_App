<?php
/**
 * Common Helper.
 *
 * Used as Common method to be used in View
 */
App::uses('Helper', 'View');
class CommonHelper extends AppHelper {
	
	var $tab = "  ";
    var $helpers = array('Session');
    
    public function getUser($quesId){
       App::import('model', 'User');
       $User = new User();
       $userInfo = $User->find('first', array('conditions'=>array('id'=>$quesId),'fields'=>array('User.fname', 'User.lname'),'recursive'=>-1));
       return $userInfo;
    }
    
    public function getTypeName($id){
        App::import('model', 'MasterTypeValue');
        $MasterTypeValue = new MasterTypeValue();
        $res = $MasterTypeValue->getName($id);
        $str = '';
        foreach($res as $r){
            $str .= $r['MasterTypeValue']['m_name'].', ';
        }
        return substr($str, 0, -2);
    }
	/**
	 * TO do =>
			* Return total users count to be synced on Adobe
	 * 
	 * @param 
		*batchId 
	 * @return 
		* Total count of users to be synced 
	 */
	public function getUsersToBeSyncedOnAdobe($batchId) {
        $arrResultSet = array();
        $BatchUserMapping = ClassRegistry::init('UserBatchMapping');
        try {
            $arrResult = $BatchUserMapping->query("SELECT count(id) as totalCount FROM user_batch_mappings WHERE batch_id=$batchId AND synced_on_adobe=0"); //  AND role_id !=".Configure::Read('UserRoles.SRM')
			if (isset($arrResult[0][0]['totalCount'])){
				return $arrResult[0][0]['totalCount'];
			} else {
				return 0;
			}
        } catch (Exception $e) {
			echo $e->getMessage();
           return 0;
        }
        return $arrResultSet;
    }
    
    public function showDateAndTime($timeStamp, $timeRequired = ''){
		if ($timeRequired == '') {
			return date('M d, Y', $timeStamp);
		}else {
			return date('M d, Y h:i A', $timeStamp);
		}
	}
        
    public function convertToHoursMins($time, $format = '%02d:%02d') // %01d Hours %01d Minutes
    {
        if ($time < 1)return 0;
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }    
	
	public function showImage($fullImageURL, $dimension){
		$arrImg = explode('/', $fullImageURL);
		$totalIndex = count ($arrImg);
		if ($arrImg[$totalIndex-1] == 'img.jpg' || $arrImg[$totalIndex-1] == 'default_batch.png') {
			return $fullImageURL;
		} else {
			$newImage = $dimension.'x'.$dimension.'_'.$arrImg[$totalIndex-1];//Need to check file exist as well
			$fullImageURL = str_replace($arrImg[$totalIndex-1], $newImage, $fullImageURL);
			return $fullImageURL;
		}
		return $fullImageURL;
	}
	
	 public function countStudentAssignment($contentId,$userId=null,$marks=0){
        App::import('model', 'StudentAssignmentSubmission');
        $StudentAssignmentSubmission = new StudentAssignmentSubmission();
        $res = $StudentAssignmentSubmission->countSubmitAssignment($contentId,$userId,$marks=0);
        return $res;
    }
    
    public function countStudentAssesment($contentId,$userId=null){
        App::import('model', 'StudentTestAttempt');
        $StudentTestAttempt = new StudentTestAttempt();
        $res = $StudentTestAttempt->countSubmitAssesment($contentId,$userId);
        return $res;
    }
    
    public function getBatchDetails($batchId){
	   App::import('model', 'Batch');
       $Batch = new Batch();
       $batchInfo = $Batch->find('first', array('conditions'=>array('id'=>$batchId),'fields'=>array('Batch.start_date', 'Batch.end_date', 'logo', 'name'),'recursive'=>-1));
       return $batchInfo;
	
	}
	public function ratingValidate($batchId,$userId){
		
        $Rating = ClassRegistry::init('Rating');
        $ratingInfo = $Rating->find('first', array('conditions' => array('Rating.user_id'=>$userId,'Rating.asset_id' => $batchId,'Rating.asset_type'=>1)));
        return $ratingInfo; 
	}
	public function ratingAverage($content_id,$type_id){
	
		$Rating = ClassRegistry::init('Rating');
        $ratingInfo = $Rating->averageRating($content_id,$type_id);
        if($ratingInfo['status']){
        return round($ratingInfo['resultData'][0]['average']); 
		}
	}
	
	public function showStar($givenRating) {
		$totalGivenStart = $givenRating;
		 $toalEmptyStart = 5 - $givenRating;
		  if ($totalGivenStart) {
			  for($i=1;$i<=$totalGivenStart;$i++) {
				  echo '<span class="glyphicon .glyphicon-star-empty glyphicon-star"></span>';
			  }
		  }
		  if ($toalEmptyStart) {
			  for($i=1;$i<=$toalEmptyStart;$i++) {
				  echo '<span class="glyphicon .glyphicon-star-empty glyphicon-star-empty"></span>';
			  }
		  }
	}
        
        public function trimDescription($text, $length =169) {
            if (strlen($text) > $length) {
                //word wrap to cut string from word
                $text = wordwrap($text,$length-4, '~');
                return substr($text, 0, strpos($text, '~')) . ' ...';
            } 
            return $text;
            
        }
        function getBatchCourseAndInstituteName($batchId){
            App::import('model', 'UserBatchMapping');
            $UserBatchMapping = new UserBatchMapping();
		$arrQueryResultSet = $UserBatchMapping->getBatchCourseAndInstituteName($batchId);
		return $arrQueryResultSet;
	}
        
         public function getQuestion($qId){
            App::import('model', 'Question');
            $Question = new Question();
            $resQues = $Question->find('first',array('conditions'=>array('Question.id'=>$qId),'fields'=>array('Question.statement','Question.question_type_id','Question.is_practice','Question.difficulty_level_id','Question.marks','Question.batch_id','Question.module_id')));
            return $resQues;
        }
        
        public function getSelectedOption($qId,$userId,$cId){
            App::import('model', 'StudentTestAttempt');
            $StudentTestAttempt = new StudentTestAttempt();
            $conditions = array('StudentTestAttempt.content_id'=>$cId, 'StudentTestAttempt.student_id'=>$userId,'StudentTestAttempt.is_latest'=>1);
            $StudentTestAttempt->hasMany['StudentTestQuestion']['conditions']['question_id'] = $qId;
            $ansDetils = $StudentTestAttempt->find('first',array('conditions'=>$conditions,'fields'=>array('StudentTestAttempt.id','StudentTestAttempt.duration')));
            if(!empty($ansDetils)){
                if(!empty($ansDetils['StudentTestQuestion'])){
                    if($ansDetils['StudentTestQuestion'][0]['is_attempt']=='N'){
                        $ansDetils['StudentTestQuestion'][0]['selected_option_id'] = '';
                    }
                }
            }
            return $ansDetils;
        }
        
        public function getCityNameById($cityId)
        {
            $cityName = Cache::read($cityId . 'cityName');
            if($cityName != false) return $cityName;
            else
            {
                // store data in cache
                App::import('model', 'City');
                $City = new City();
                $cityName = $City->field('name', array('id' => $cityId));
                Cache::write($cityId . 'cityName', $cityName);
                return $cityName;
            }
        }
        
        public function getCountryNameById($countryId)
        {
            $countryName = Cache::read($countryId . 'countryName');
            if($countryName != false) return $countryName;
            else
            {
                // store data in cache
                App::import('model', 'Country');
                $Country = new Country();
                $countryName = $Country->field('name', array('id' => $countryId));
                Cache::write($countryId . 'countryName', $countryName);
                return $countryName;
            }
        }
        
     /*
	 * Take array of Group ids and retrieve SRM details of each group.
	 * Return SRM Details in array
	 * I/P =>
	 * Array
			(
				[0] => 7
				[1] => 9
				[2] => 16
			)
	 */ 
       public function prepairConnectSRMData($arrBatches) {
		   $objApp = ClassRegistry::init('AppModel');
           return $resultSet = $objApp->prepairConnectSRMData($arrBatches);		   
	   }
	   
	  public function getCourseDtl($sessionId){
			App::import('model', 'AcademicSession');
			$AcademicSession = new AcademicSession();
			App::import('model', 'Semester');
			$Semester = new Semester();
			App::import('model', 'UserSemesterMapping');
			$UserSemesterMapping = new UserSemesterMapping();
			App::import('model', 'Institute');
			$Institute = new Institute();
			
			$SemesterList = $Semester->find('list',array('conditions'=>array('Semester.session_id'=>$sessionId),'fields'=>array('Semester.id'),'recursive'=>-1));
			
			$data['SemesterStudentCount'] = $UserSemesterMapping->find('count',array('conditions'=>array('UserSemesterMapping.semester_id'=>$SemesterList,'UserSemesterMapping.role_id'=>5),'fields' => 'DISTINCT UserSemesterMapping.user_id','recursive'=>-1));
			
			//$data['UserSemester'] = $Course->query("select COUNT(*) user_semester_count from user_semester_mappings as USM where USM.session_id = ".$courseId." and  Semester.is_deleted = 0");
			
			$data['SemesterCount'] = $Semester->find('count',array('conditions'=>array('Semester.session_id'=>$sessionId)));
			
			$cInfo = $AcademicSession->find('first', array('conditions'=>array('AcademicSession.id'=>$sessionId),'fields'=>array('AcademicSession.name','AcademicSession.start_date','AcademicSession.end_date','AcademicSession.logo','AcademicSession.institute_id','Course.id','Course.name', 'Course.description'),'recursive'=>0));
			
			$instInfo = $Institute->find('first', array('conditions'=>array('Institute.id'=>$cInfo['AcademicSession']['institute_id']),'fields'=>array('Institute.logo','Institute.name', 'Institute.description'),'recursive'=>-1));
			//pr($cInfo);
			//pr($instInfo);exit;
			return $instInfo+$cInfo+$data;
	  }
          
        public function getModuleDetails($moduleId){
            if(!empty($moduleId)){
                App::import('model', 'Module');
                $Module = new Module();
                $moduleInfo = $Module->find('first', array('conditions'=>array('id'=>$moduleId),'fields'=>array('Module.name'),'recursive'=>-1));
            } else {
                $moduleInfo = '';
            }
            return $moduleInfo;
	
	}

	// get grade from marks.
        public function getGradeFromMarks($marks = "")
        {
            if($marks > 90 && $marks <= 100)$grade = 'A1';
            elseif($marks > 80 && $marks <= 90)$grade = 'A2';
            elseif($marks > 70 && $marks <= 80)$grade = 'B1';
            elseif($marks > 60 && $marks <= 70)$grade = 'B2';
            elseif($marks > 50 && $marks <= 60)$grade = 'C1';
            elseif($marks > 40 && $marks <= 50)$grade = 'C2';
            elseif($marks > 32 && $marks <= 40)$grade = 'D';
            elseif($marks > 20 && $marks <= 32)$grade = 'E';
            elseif($marks >= 0 && $marks <= 20)$grade = 'F';
            else $grade = '';
            return $grade;
        } 
        
        //Call method updateLastLogin() of USERS model and update logout time in user_login_log table
		public function updateUserLoginLogoutTime(){
		   App::import('model', 'User');
		   $User = new User();
		   $userInfo = $User->updateLastLogin(0, array(), session_id());//Update is based on session_id() so no USER ID Required here
		}
        
        public function returnTableData($tableName,$filename,$type){		
            $db = ConnectionManager::getDataSource('default');
            $query= "SELECT new.*, b.name, m.name FROM $tableName as new
            JOIN batches as b ON new.batch_id=b.id
            LEFT JOIN modules as m ON new.module_id=m.id
            where new.filename='$filename' AND new.question_type='$type' ";
            $resultSet= $db->query($query);
            return $resultSet;
        }
        
        public function getText($statement){
	$quesStmtImg = preg_match_all('/<img\s.*?\bsrc="(.*?)".*?>/si', $statement,$matchesStatement);
	if(count($matchesStatement[1])>0){
	    $imagesName = array();
	    $newSrc = array();
	    for($totImg=0;$totImg<count($matchesStatement[1]);$totImg++){
		$imgName = explode('\\',$matchesStatement[1][$totImg]);//** Extract Image Name
		$lastIndexVal = count($imgName)-1;//** Pick image name, exclude path
		$newImgName = $imgName[$lastIndexVal];
		$imagesName[] = $newImgName;
	    }
	    for($cnt=0;$cnt<count($imagesName);$cnt++){
		$newSrc[] = REPO_ABSOLUTE_PATH.'question_bulkimport/images/'.$imagesName[$cnt];
	    }
	    
	    $regex = '#src="(([^"/]*/?[^".]*\.[^"]*)"([^>]*)>)#';
	    $quesStatement = preg_replace_callback(
	    $regex,
	    function($match) use ($newSrc) { 
		static $cnt = 0;
		$replacedSrc = $newSrc[$cnt];
		$cnt++;
		return (('src="'.$replacedSrc.'">'));
	    },
		$statement
	    );
	} else {
	    $quesStatement = $statement;
	}
	return $quesStatement;
    }
}
?>
