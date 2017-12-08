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
 */
//App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
App::uses('AdobeConnectClient', 'Lib');
require_once(APP.'Lib/adobe_auth.php');
class LiveClassShell extends AppShell {

    var $uses = array('LiveClass', 'UserBatchMapping', 'UsersAdobeConnect', 'User', 'UserContentView', 'UserContentMapping');
    private $adobeUserName = ADOBEUSER;
    private $adobePassword = ADOBEPASS;
    
    /**
	 * TO DO 
		* Create Users on Adobe and send credentials in related tables
		* Get List of user details who is not synced from table `user_batch_mapping` and `users` table
		* Check sco_id from table user_adobe_connect, If sco_id not found then create a new User otherwise update field "synced_on_adobe" to 1 in `user_batch_mapping` table
			* Generate password
			* Call Create User API of Adobe Connect
			* If user created successfully store credentials in table `users_adobe_connect` and update status in table `user_batch_mapping`
	 * @param 
		* batchId
		* courseId
	 * @return void
	 * @author Rohit Roy
	 * @date 23-Oct-16
	 */
    public function syncUser() {

        try {
            //Get file info
            $createdById = $this->args[0];
            $batchId = $this->args[1]; 
            if ($batchId != '') {
				$client = new AdobeConnectClient(Configure::read('GlobalSettings.ADOBE_CONNECT_BASE_URL'), $this->adobeUserName, $this->adobePassword);
				if($client->is_authorized) {
					$arrUsersTobeSynced = $this->UserBatchMapping->getUsersToBeSyncedOnAdobe($batchId);
					if (isset($arrUsersTobeSynced['resultData'])) {
						foreach ($arrUsersTobeSynced['resultData'] as $resultDatakey => $usersToBeSynced) {
							if ($usersToBeSynced['sco_id'] == '') {
								$password = $this->User->encryptMyKey($usersToBeSynced['username'], strtotime($usersToBeSynced['created']));
								//echo $this->User->decryptMyKeyIt($password);exit;
								$decryptPassword = $this->User->decryptMyKeyIt($password);
								$name = trim($usersToBeSynced['fname'].' '.$usersToBeSynced['lname']);
								$type = 'user';
								$createResponse = $client->createUser($usersToBeSynced['email'], $decryptPassword, $name, $type, true);
								$newCreatedId = '';
								if (isset($createResponse['principal']['@attributes']['principal-id'])) {
									$newCreatedId = $createResponse['principal']['@attributes']['principal-id'];
								} else {
									if (isset($createResponse['status']['invalid']['@attributes']['subcode']) && $createResponse['status']['invalid']['@attributes']['subcode'] == 'duplicate') {
										$newCreatedId = $this->getDetailsOfAlreadyExistEmailOnAdobe($client, $usersToBeSynced['email'], $decryptPassword);
									}
								}
								if ($newCreatedId) {//isset($createResponse['principal']['@attributes']['principal-id'])) {
									//Create record in table users_adobe_connect
									$data = array();
									$data['UsersAdobeConnect']['user_id'] = $usersToBeSynced['user_id'];
									$data['UsersAdobeConnect']['login_id'] = $usersToBeSynced['email'];
									$data['UsersAdobeConnect']['password'] = $password;
									$data['UsersAdobeConnect']['sco_id'] = $newCreatedId;//$createResponse['principal']['@attributes']['principal-id'];
									$this->UsersAdobeConnect->create();
									if ($this->UsersAdobeConnect->save($data)) {
										
										//Update sco_id in table `user_batch_mapping`
										$this->UsersAdobeConnect->query("UPDATE user_batch_mappings SET synced_on_adobe=1 WHERE batch_id=$batchId AND user_id=".$usersToBeSynced['user_id']);
										
									}//Users record inserted in users_adobe_connect table									
									
								}//User created on Adobe
							} else {
								//Update sco_id in table `user_batch_mapping`
								$this->UsersAdobeConnect->query("UPDATE user_batch_mappings SET synced_on_adobe=1 WHERE batch_id=$batchId AND user_id=".$usersToBeSynced['user_id']);
										
							}
					
						}//Actual users to be synced
					}
				}//If adobe connected
			}//If batchId found          

	   }catch (Exception $e) {
		   
	   }
	 }
	 
	 /* Description 
		* Read all liveclasses whose status is publihsed, end date is expired and recorded session URL is empty
		* Get recorded session URL and update in field
		* Also get meeting summary e.g how many total_invitees_attended_in_live_class, total_invitees_in_live_class, participation_percentage_in_live_class and update it in content table
		* Calculate Average Attendance of Live CLass in Batch and Average Attendacne of Recorded Class in Bath for which attendance is allowed
			
		* Set this script to CRON and Run it at every 5 minutes
		* **************************************************Need to implement Live class Type conditon whether Adobe or other
	 * @author Rohit Roy
	 * @date 23-Oct-16
	 */
    public function updateRecordedSessionURL() {
			
      try {
			$arrUsersTobeSynced = $this->LiveClass->query("SELECT live_class_type, id, module_id, live_class_attendance_synced, recorded_session_url_synced, total_invitees_in_live_class, sco_id,batch_id from contents WHERE status=1 AND content_type_id=".Configure::read('ContentTypes.LIVE_CLASS')."  AND DATE_ADD(end_date, INTERVAL 20 MINUTE) < NOW() AND (ISNULL(recorded_session_url) OR recorded_session_url = '' OR recorded_session_url = 'completed')");//AND recorded_session_url_synced=0
                        //$UserContentMapping = ClassRegistry::init('UserContentMapping');
                        
			if (!empty($arrUsersTobeSynced)) {
				$arrSyncedBatches = [];
				$client = new AdobeConnectClient(Configure::read('GlobalSettings.ADOBE_CONNECT_BASE_URL'), $this->adobeUserName, $this->adobePassword);
				if($client->is_authorized) {
					$tmp = 0;
					foreach ($arrUsersTobeSynced as $key => $arrLiveClasses) {
						$arrSyncedBatches[$arrLiveClasses['contents']['batch_id']] = $arrLiveClasses['contents']['batch_id'];								
						if ($arrLiveClasses['contents']['live_class_type'] == 1) {//IF LIVE CLASS IS ADOBE
								if ($arrLiveClasses['contents']['sco_id'] != '') {
									$arrBatchRelatedInstituteAndCourseIds = $this->UserBatchMapping->getBatchCourseAndInstituteName($arrLiveClasses['contents']['batch_id']);
									$institueID = isset($arrBatchRelatedInstituteAndCourseIds[0]['i']['institute_id']) ? $arrBatchRelatedInstituteAndCourseIds[0]['i']['institute_id'] : 0;
									$courseID = isset($arrBatchRelatedInstituteAndCourseIds[0]['c']['course_id']) ? $arrBatchRelatedInstituteAndCourseIds[0]['c']['course_id'] : 0;
									$meetingDetails = $client->getMeetingDetails($arrLiveClasses['contents']['sco_id']);//Get Meeting Details along with Recorded Session
									$recordedSessionScoId = 0;
									$recordedSessionFolderScoId = 0;
									$recordedSessionURL = '';
									$totalInviteesAttendedInLiveClass = '';
									$totalInviteesInLiveClass = $arrLiveClasses['contents']['total_invitees_in_live_class'];
									$participationPercentageInLiveClass = '';
									if (!isset($meetingDetails['error'])) {
										$recordedSessionScoId = isset($meetingDetails['scos']['sco']['@attributes']['sco-id']) ? $meetingDetails['scos']['sco']['@attributes']['sco-id'] : 0;
										$recordedSessionFolderScoId = isset($meetingDetails['scos']['sco']['@attributes']['folder-id']) ? $meetingDetails['scos']['sco']['@attributes']['folder-id'] : 0;
										$recordedSessionURL = isset($meetingDetails['scos']['sco']['url-path']) ? $meetingDetails['scos']['sco']['url-path'] : '';
										$recordedSessionUrlSynced = 1;//$recordedSessionURL == '' ? 0 : 1;
										
										//Get Live Class Attendance Report (Run this only if live_class_attendance_synced=0)
										$live_class_attendance_synced = 1;
										$errorInAttendacneSynce = 0;
										if ($arrLiveClasses['contents']['live_class_attendance_synced'] == 0) {
											$reportMeetingAttendanceOfLiveClass = $client->reportMeetingAttendanceOfLiveClass($arrLiveClasses['contents']['sco_id']);
											//pr($reportMeetingAttendanceOfLiveClass);
											if (!isset($reportMeetingAttendanceOfLiveClass['error'])) {
												$reportMeetingAttendanceRow = isset($reportMeetingAttendanceOfLiveClass['report-meeting-attendance']['row']) ? $reportMeetingAttendanceOfLiveClass['report-meeting-attendance']['row'] : array();
												if (count($reportMeetingAttendanceRow) > 0) {
													$arrUniqueAttendee = array();
													$insertUserContentViewLogs = array();
													$attendeeLoop = 0;
													foreach ($reportMeetingAttendanceOfLiveClass['report-meeting-attendance']['row'] as $attendeeKey => $attendeeDtls) {
														$userIdOfAdobe = $attendeeDtls['@attributes']['principal-id'];
														$userIdOfLMS = $this->UsersAdobeConnect->getUserIdBasedOnScoId($userIdOfAdobe, $arrLiveClasses['contents']['batch_id']);
														if ($userIdOfLMS){//If Adobe userid/scoid found in user adobe connect table it means it's student
															$arrUniqueAttendee[$userIdOfLMS] = $userIdOfLMS; 
															$insertUserContentViewLogs[$attendeeLoop]['user_id'] = $userIdOfLMS;
															$insertUserContentViewLogs[$attendeeLoop]['institute_id'] = $institueID;
															$insertUserContentViewLogs[$attendeeLoop]['course_id'] = $courseID;
															$insertUserContentViewLogs[$attendeeLoop]['batch_id'] = $arrLiveClasses['contents']['batch_id'];
															$insertUserContentViewLogs[$attendeeLoop]['module_id'] = $arrLiveClasses['contents']['module_id'];
															$insertUserContentViewLogs[$attendeeLoop]['content_id'] = $arrLiveClasses['contents']['id'];
															$insertUserContentViewLogs[$attendeeLoop]['content_type_id'] = Configure::read('ContentTypes.LIVE_CLASS');
															$insertUserContentViewLogs[$attendeeLoop]['is_completed'] = 1;
															$insertUserContentViewLogs[$attendeeLoop]['created'] = date('Y-m-d H:i:s', strtotime($attendeeDtls['date-created']));
															$insertUserContentViewLogs[$attendeeLoop]['end_date'] = date('Y-m-d H:i:s', strtotime($attendeeDtls['date-end']));
															$insertUserContentViewLogs[$attendeeLoop]['transcript_id'] = $attendeeDtls['@attributes']['transcript-id'];
															$insertUserContentViewLogs[$attendeeLoop]['asset_id'] = $attendeeDtls['@attributes']['asset-id'];
															$insertUserContentViewLogs[$attendeeLoop]['answered_survey'] = $attendeeDtls['@attributes']['answered-survey'];
                                                                                                                        $insertUserContentViewLogs[$attendeeLoop]['source'] = 0;
															$attendeeLoop++;
														}//If User found then insert in log table											
													}//End of foreach of Total Attendted Invitee
													if (count($insertUserContentViewLogs)) {
														$this->UserContentView->saveMany($insertUserContentViewLogs);
														//update user_content_mappings and set completion_percentage=100
														foreach($arrUniqueAttendee as $uKey => $uid){													
															$this->LiveClass->query("UPDATE user_content_mappings SET completion_percentage=100
															WHERE user_id=".$uid." AND content_id=".$arrLiveClasses['contents']['id']);
														}
														
													}
													$totalUniqueAttendee = count($arrUniqueAttendee);
													$totalInviteesAttendedInLiveClass = ",total_invitees_attended_in_live_class=".$totalUniqueAttendee;
													if ($totalUniqueAttendee) {
														$participationPercentageInLiveClass = ",participation_percentage_in_live_class=".round (($totalUniqueAttendee/$totalInviteesInLiveClass)*100, 2);									
													} else {
														$participationPercentageInLiveClass = ",participation_percentage_in_live_class=0";
													}
												} else {
													$totalInviteesAttendedInLiveClass = ",total_invitees_attended_in_live_class=0";
													$participationPercentageInLiveClass = ",participation_percentage_in_live_class=0";
												}
											} else {
												$live_class_attendance_synced = 0;
												$errorInAttendacneSynce = 1;
											}
										}//Live Class Attendacne Already synced
										//Lve Class Attendacne Report End
										//echo $live_class_attendance_synced."=".$totalInviteesAttendedInLiveClass." = ".$participationPercentageInLiveClass;
										if (!$errorInAttendacneSynce) {
											$this->LiveClass->query("UPDATE contents SET 
											recorded_session_url='".$recordedSessionURL."',
											recorded_session_sco_id=".$recordedSessionScoId.",
											recorded_session_folder_id=".$recordedSessionFolderScoId.",
											live_class_attendance_synced=".$live_class_attendance_synced.",
											recorded_session_url_synced=".$recordedSessionUrlSynced.$totalInviteesAttendedInLiveClass.$participationPercentageInLiveClass
											." WHERE id=".$arrLiveClasses['contents']['id']);
										}		
									}//END of Meeting Details found	
									
									//update reporting and content table 
									$reportData = ['batch_id'=>$arrLiveClasses['contents']['batch_id'],'institute_id'=>$institueID,'course_id'=>$courseID,'module_id'=>$arrLiveClasses['contents']['module_id'],'content_id'=>$arrLiveClasses['contents']['id'],'content_type_id'=>Configure::read('ContentTypes.LIVE_CLASS')];
									
									$this->UserContentMapping->updateAvgCompToContent($arrLiveClasses['contents']['id']);
									$this->UserContentMapping->updateReportingData($reportData);			
								}//End of if SCO-ID found for given Live ClASS
							}// END of if LIVE CLASS ADOBE						
							else {
                                                            $recordedSessionScoId = 0;
                                                            $recordedSessionFolderScoId = 0;
                                                            $recordedSessionURL = NULL;
                                                            if($arrLiveClasses['contents']['recorded_session_url'] == 'completed' ){
                                                                $recordedSessionURL = 'content_path';
                                                            }
                                                            $totalInviteesAttendedInLiveClass = '';
                                                            $totalInviteesInLiveClass = $arrLiveClasses['contents']['total_invitees_in_live_class'];
                                                            $participationPercentageInLiveClass = '';
                                                            $live_class_attendance_synced = 1;

                                                            $recordedSessionUrlSynced = 1;
                                                            $errorInAttendacneSynce = 0;

                                                            if ($arrLiveClasses['contents']['live_class_attendance_synced'] == 0) {
                                                                $arrUniqueAttendee = array();
                                                                $arrUniqueAttendeeDetails = $this->LiveClass->query('select GROUP_CONCAT(DISTINCT user_id) as user_id, institute_id, course_id from user_content_views where content_id ="' . $arrLiveClasses['contents']['id'] . '"');
                                                                if (!empty($arrUniqueAttendeeDetails) && isset($arrUniqueAttendeeDetails[0][0]['user_id'])) {
                                                                    $institueID = $arrUniqueAttendeeDetails[0]['user_content_views']['institute_id'];
                                                                    $courseID = $arrUniqueAttendeeDetails[0]['user_content_views']['course_id'];
                                                                    //now comma seprated data
                                                                    $arrUniqueAttendeeDetails = $arrUniqueAttendeeDetails[0][0]['user_id'];
                                                                    $arrUniqueAttendee = explode(',', $arrUniqueAttendeeDetails);
                                                                    $completionPercentageUpdate = $this->LiveClass->query("UPDATE user_content_mappings 
                                                                                                                             SET completion_percentage=100
                                                                                                                             WHERE content_id=" . $arrLiveClasses['contents']['id'] . " "
                                                                            . "AND user_id  IN (" . $arrUniqueAttendeeDetails . ")");
                                                                    $totalUniqueAttendee = count($arrUniqueAttendee);
                                                                    $totalInviteesAttendedInLiveClass = ",total_invitees_attended_in_live_class=" . $totalUniqueAttendee;
                                                                    if ($totalUniqueAttendee) {
                                                                        $participationPercentageInLiveClass = ",participation_percentage_in_live_class=" . round(($totalUniqueAttendee / $totalInviteesInLiveClass) * 100, 2);
                                                                    } else {
                                                                        $participationPercentageInLiveClass = ",participation_percentage_in_live_class=0";
                                                                    }
                                                                } else {
                                                                    $totalInviteesAttendedInLiveClass = ",total_invitees_attended_in_live_class=0";
                                                                    $participationPercentageInLiveClass = ",participation_percentage_in_live_class=0";
                                                                }

                                                                if (!$errorInAttendacneSynce) {
                                                                    $this->LiveClass->query("UPDATE contents SET 
                                                                                            recorded_session_url=".$recordedSessionURL.",
                                                                                            recorded_session_sco_id=" . $recordedSessionScoId . ",
                                                                                            recorded_session_folder_id=" . $recordedSessionFolderScoId . ",
                                                                                            live_class_attendance_synced=" . $live_class_attendance_synced . ",
                                                                                            recorded_session_url_synced=" . $recordedSessionUrlSynced . $totalInviteesAttendedInLiveClass . $participationPercentageInLiveClass
                                                                                          . " WHERE id=" . $arrLiveClasses['contents']['id']);
                                                                }
//                                                              
//                                                              //echo "<======= Begin reporting update ===========>";
                                                                //update reporting and content table 
                                                                
                                                                $reportData = ['batch_id' => $arrLiveClasses['contents']['batch_id'], 'institute_id' => $institueID, 'course_id' => $courseID, 'module_id' => $arrLiveClasses['contents']['module_id'], 'content_id' => $arrLiveClasses['contents']['id'], 'content_type_id' => Configure::read('ContentTypes.LIVE_CLASS')];
                                                                $this->UserContentMapping->updateAvgCompToContent($arrLiveClasses['contents']['id']);
                                                                $this->UserContentMapping->updateReportingData($reportData);
                                                            }
                                                        }//END of if CUSTOM LIVE CLASS
						
						$tmp++;
					}//End of live class LOOP
				}//If adobe connected
				
				//Update batch average class participation percentage (Take only those Live classes which is OVER and not DEMO Class)	
				if (count($arrSyncedBatches)) {
					$batchids = implode($arrSyncedBatches, ',');
					$calculateAverageParticipationPercentage = $this->LiveClass->query("SELECT batch_id, AVG( `participation_percentage_in_live_class` )
					AS class_participation_percentage FROM contents
					WHERE `recorded_session_url_synced`=1
					AND `is_demo_live_class`=0
					AND `is_deleted` =0
					AND `status` =1
					AND `content_type_id` =".Configure::read('ContentTypes.LIVE_CLASS')."
					AND `batch_id` IN (".$batchids.")
					GROUP BY batch_id");
					if (!empty($calculateAverageParticipationPercentage)) {
						foreach ($calculateAverageParticipationPercentage as $percentageKey => $percentageArr) {
							$this->LiveClass->query("UPDATE batches SET class_participation_percentage_in_live_class=".$percentageArr[0]['class_participation_percentage']." WHERE id=".$percentageArr['contents']['batch_id']);
						}
					}
				}
				/*$calculateAverageParticipationPercentage = $this->LiveClass->query("SELECT batch_id, AVG( `participation_percentage_in_live_class` )
				AS class_participation_percentage FROM contents
				WHERE `recorded_session_url_synced`=1
				AND `is_demo_live_class`=0
				AND `is_deleted` =0
				AND `status` =1
				AND `content_type_id` =".Configure::read('ContentTypes.LIVE_CLASS')."
				GROUP BY batch_id");
				if (!empty($calculateAverageParticipationPercentage)) {
					foreach ($calculateAverageParticipationPercentage as $percentageKey => $percentageArr) {
						if (in_array($percentageArr['contents']['batch_id'], $arrSyncedBatches)) {//Update average only for synced batches
							$this->LiveClass->query("UPDATE batches SET class_participation_percentage_in_live_class=".$percentageArr[0]['class_participation_percentage']." WHERE id=".$percentageArr['contents']['batch_id']);
						}
					}//For loop of Calculated average percentage					
				}//If average participation found for batch		
				*/		
			}		
			
	   }catch (Exception $e) {
               echo 'Caught exception'.$e->getMessage();
		   
	   }
	 }
	 
	 /* TO DO 
		* Read all liveclasses whose end date is expired and sync_attendance_of_live_class
		* Set this script to CRON and Run it as every 1 hour (Working on...)
	 * @author Rohit Roy
	 * @date 23-Oct-16
	 */
    public function reportMeetingAttendanceOfLiveClass() {

      try {
			$arrUsersTobeSynced = $this->LiveClass->query("SELECT id, live_class_type, sco_id, recorded_session_sco_id, recorded_session_folder_id from contents WHERE content_type_id=".Configure::read('ContentTypes.LIVE_CLASS')." AND end_date < NOW() AND `sync_attendance_of_live_class`=0");
			if (!empty($arrUsersTobeSynced)) {
				$client = new AdobeConnectClient(Configure::read('GlobalSettings.ADOBE_CONNECT_BASE_URL'), $this->adobeUserName, $this->adobePassword);
				if($client->is_authorized) {			
					foreach ($arrUsersTobeSynced as $key => $arrLiveClasses) {
						if ($arrLiveClasses['contents']['live_class_type'] == 1) {//Adobe connect
							if ($arrLiveClasses['contents']['recorded_session_sco_id'] != '') {
								$recordedSessionAttendanceDetails = $client->reportMeetingAttendanceOfLiveClass($arrLiveClasses['contents']['sco_id']);
								if (!isset($meetingDetails['error'])) {
									pr($recordedSessionAttendanceDetails);
								}
							}							
						} else {
							
						}						
					}//End of Ended live class
				}//If adobe connected					
			}		
			
	   }catch (Exception $e) {
		   
	   }
	 }
	 
	 
	 /* TO DO 
		* Read all liveclasses whose end date is expired and Recorded session is available and sync_attendance_of_recorded_class is empty
		* Set this script to CRON and Run it as every 1 hour (Working on...)
	 * @author Rohit Roy
	 * @date 23-Oct-16
	 */
    public function reportMeetingAttendanceOfRecordedClass() {

      try {
			$arrUsersTobeSynced = $this->LiveClass->query("SELECT id, live_class_type, sco_id, recorded_session_sco_id, recorded_session_folder_id from contents WHERE content_type_id=".Configure::read('ContentTypes.LIVE_CLASS')." AND end_date < NOW()");// AND recorded_session_url != ''");// AND `sync_attendance_of_live_class`=0");
			if (!empty($arrUsersTobeSynced)) {
				$client = new AdobeConnectClient(Configure::read('GlobalSettings.ADOBE_CONNECT_BASE_URL'), $this->adobeUserName, $this->adobePassword);
				if($client->is_authorized) {			
					foreach ($arrUsersTobeSynced as $key => $arrLiveClasses) {
						if ($arrLiveClasses['contents']['live_class_type'] == 1) {//Adobe connect
							if ($arrLiveClasses['contents']['recorded_session_sco_id'] != '') {
								echo "<br/>Recorded Session ID=".$arrLiveClasses['contents']['recorded_session_sco_id'];
								$recordedSessionAttendanceDetails = $client->reportMeetingAttendanceOfRecordedClass($arrLiveClasses['contents']['recorded_session_sco_id']);
								if (!isset($meetingDetails['error'])) {
									pr($recordedSessionAttendanceDetails);
								}
							}							
						} else {
							
						}						
					}//End of Ended live class
				}//If adobe connected					
			}		
			
	   }catch (Exception $e) {
		   
	   }
	 }
	 
	 /* TO DO 
		* Get user details based on Email from Adobe
		* Update Password of this User on Adobe; If Password gets updated successfully then reutn sco_id else return false
	 * @author Rohit Roy
	 * @date 10-Jan-17
	 */
	 public function getDetailsOfAlreadyExistEmailOnAdobe ($client, $email, $password) {
		 try {
			 $response = $client->getUserByEmail($email);
			 if (isset($response['principal']['@attributes']['principal-id'])) {
					$updatePasswordResponse = $client->updatePassword($response['principal']['@attributes']['principal-id'], $password);
					if (isset($updatePasswordResponse['status']['@attributes']['code'])) {
						if (strtolower($updatePasswordResponse['status']['@attributes']['code']) == 'ok') {
							return $response['principal']['@attributes']['principal-id'];
						} else {
							return '';
						}						
					} else {
						return '';
					}
			 } else {
				 return '';
			 }
		 } catch (Exception $e) {
			 return '';
		 }
		 
	 }

}
