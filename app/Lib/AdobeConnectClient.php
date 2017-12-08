<?php
/*
 * AdobeConnect 9 api client
 * @see https://github.com/sc0rp10/AdobeConnect-php-api-client
 * @see http://help.adobe.com/en_US/connect/9.0/webservices/index.html
 * @version 0.1a
 *
 * Copyright 2012, sc0rp10
 * https://weblab.pro
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 *
 */
/**
 * Class AdobeConnectClient
 */
require_once('adobe_auth.php');
class AdobeConnectClient {
	const PERMISSION_VIEW = 'view';
	const PERMISSION_HOST = 'host';
	protected $curl;
	public $is_authorized = false;
	public $breezecookie = null;
	protected $login;
	protected $password;
	protected $root_folder;
	protected $template_folder;
	protected $host;
	public $principal_id;
	/**
	 * @param $host 
		* -> Default login is set for Faculty who will create HOST Meeting, Otherwise pass login and password inline
		* -> Right now Two login credentials is used
			* -> From ADMIN panel where inline credentials is passed and used for USER CREATION only
			* -> From Faculty panel where credentials is being initialized from Constructor 
	 * @param $login
	 * @param $password
	 * @param $root_folder
	 * @param $template_folder
	 */
	public function __construct ($host, $login = ADOBEUSER, $password = ADOBEPASS, $root_folder = null, $template_folder = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_REFERER, $host);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$this->curl = $ch;
		$this->login = $login;
		$this->password = $password;
		$this->root_folder = $root_folder;
		$this->template_folder = $template_folder;
		$this->host = $host;
		if ($login == ADOBEUSER) {
			$this->principal_id = 954649211;
		}
		/*if(!isset($_SESSION['Brezz'])) {
			//echo 'session not avail.';
			$this->makeAuth($this->login, $this->password);
			//$_SESSION['Brezz'] = $this;	
			//pr($_SESSION['Brezz']);		
		} else {
			$breezSession = $_SESSION['Brezz'];
			$this->breezecookie = $breezSession->breezecookie;
			//echo "BreezeC=".$this->breezecookie;
		}*/
		$this->makeAuth($this->login, $this->password);
		//echo "BreezeC=".$this->breezecookie;
	}
	/**
	 *
	 * @param null $login
	 * @param null $password
	 *
	 * @return AdobeConnectClient
	 */
	public function makeAuth ($login = null, $password = null) {
		$this->login = $login ?: $this->login;
		$this->password = $password ?: $this->password;
		$result = null;
		if (!$this->breezecookie) {
			try {
				$arrResponse = $this->makeRequest('login', [
					'login' => $this->login,
					'password' => $this->password
				]);
			} catch (Exception $e) {
				//$e = new Exception(sprintf('Cannot auth with credentials: [%s:%s@%s]', $this->login, $this->password, $this->host), 0, $e);
				//$e->setHost($this->host);
				//$e->setLogin($this->login);
				//$e->setPassword($this->password);
				//$e->getMessage();
				//throw $e;
				$arrResponse['error'] = 'Cannot auth with given credentials.';
			}
		};
		if (isset($arrResponse['error'])) {
			$this->is_authorized = false;		
		} else {
			$this->is_authorized = true;		
		}
		return $this;
	}
	/**
	 * get common info about current user
	 *
	 * @return array
	 */
	public function getCommonInfo () {
		return $this->makeRequest('common-info');
	}
	/**
	 * create user
	 *
	 * @param string $email
	 * @param string $password
	 * @param string $name
	 * @param string $type
	 *
	 * @return array
	 */
	public function createUser ($email, $password, $name, $type = 'user', $fullReturn = false) {
		$result = $this->makeRequest('principal-update', [
			'name' => $name,
			'email' => $email,
			'password' => $password,
			'type' => $type,
			'has-children' => 0,
		]);
		if ($fullReturn) {
			return $result;
		} else {
			return $result['principal']['@attributes']['principal-id'];
		}
	}
	
	public function createUserBulk ($arrUsers, $fullReturn = false) {
		try {
			$result = $this->makeRequest('principal-update', $arrUsers);
			if (isset($result['error'])) {
				return $result;
			} else {
				if ($fullReturn) {
					return $result['principal'];
				} else {
					return $result['principal']['@attributes']['principal-id'];
				}
			}
		} catch (Exception $e) {
			return '';
		}
	}
	
	/**
	 * @return null
	 */
	public function getBreezeCookie () {
		return $this->breezecookie;
	}
	/**
	 * @param string $email
	 * @param bool $only_id
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 *
	 */
	public function getUserByEmail ($email, $only_id = false) {
		$result = $this->makeRequest('principal-list', [
			'filter-email' => $email
		]);
		if (isset($result['error'])) {
			return $result;
		} else {
			if (!empty($result['principal-list'])) {
				return $result['principal-list'];
			} else {
				return array();
			}
		}
		/*
		if (empty($result['principal-list'])) {
			throw new Exception(sprintf('Cannot find user [%s]', $email));
		}
		if ($only_id) {
			return $result['principal-list']['principal']['@attributes']['principal-id'];
		}
		return $result;
		*/
	}
	/**
	 * update user fields
	 *
	 * @param string $email
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function updateUser ($email, array $data = []) {
		$principal_id = $this->getUserByEmail($email, true);
		$data['principal-id'] = $principal_id;
		return $this->makeRequest('principal-update', $data);
	}
	/**
	 * get all users list
	 *
	 * @return array
	 */
	public function getUsersList () {
		$users = $this->makeRequest('principal-list');
		$result = [];
		foreach ($users['principal-list']['principal'] as $key => $value) {
			$result[$key] = $value['@attributes'] + $value;
		};
		return $result;
	}
	/**
	 * get all meetings
	 *
	 * @return array
	 */
	public function getAllMeetings () {
		return $this->makeRequest('report-my-meetings');
	}
	/**
	 * get shortcuts
	 *
	 * @return array
	 */
	public function getShortcuts () {
		return $this->makeRequest('sco-shortcuts')['shortcuts']['sco'];
	}
	/**
	 * get shortcuts
	 *
	 * @param $sco_id
	 *
	 * @return array
	 */
	public function getScoInfo ($sco_id) {
		return $this->makeRequest('sco-info', ['sco-id' => $sco_id]);
	}
	/**
	 * create meeting-folder
	 *
	 * @param string $name
	 * @param string $url
	 *
	 * @return array
	 */
	public function createFolder ($name, $url, $folder_id = '') {
		return $result = $this->makeRequest('sco-update', [
			'type' => 'folder',
			'name' => $name,
			'folder-id' => ($folder_id == '') ? $this->root_folder : $folder_id,
			'depth' => 1,
			//'url-path' => $url
		]);
		//return $result['sco']['@attributes']['sco-id'];
	}
	/**
	 *
	 * @return mixed
	 */
	public function getTemplates () {
		return $this->makeRequest('sco-contents', [
			'sco-id' => $this->template_folder
		])['scos']['sco'];
	}
	/**
	 * create meeting
	 *
	 * @param int $folder_id
	 * @param string $name
	 * @param DateTime $date_begin
	 * @param DateTime $date_end
	 * @param string $url
	 * @param null $template_sco_id
	 *
	 * @return int
	 */
	public function createMeeting (
		$folder_id,
		$name,
		$date_begin,
		$date_end,
		$url,
		$template_sco_id = null,
		$fullData = false,
		$isMeeting = 1
	) {
		$date_begin = new DateTime($date_begin);
		$date_end = new DateTime($date_end);
		if ($isMeeting == 2) {
			$data = [
			'type' => 'meeting',
			'name' => $name,
			'folder-id' => $folder_id,
			'date-begin' => $date_begin->format(\DateTime::ISO8601),
			'date-end' => $date_end->format(DateTime::ISO8601),
			'url-path' => $url,
			'principal-id'=>'public-access',
			'permission-id'=>'denied',
			'icon' => 'virtual-classroom'
			];
			
		} else {
			$data = [
				'type' => 'meeting',
				'name' => $name,
				'folder-id' => $folder_id,
				'date-begin' => $date_begin->format(\DateTime::ISO8601),
				'date-end' => $date_end->format(DateTime::ISO8601),
				'url-path' => $url,
				'principal-id'=>'public-access',
				'permission-id'=>'denied'
			];
		}
		//pr($data);exit;
		if ($template_sco_id) {
			$data['source-sco-id'] = $template_sco_id;
		}
		$result = $this->makeRequest('sco-update', $data);
		if (isset($result['error'])) {
			if(isset($result['status']['@attributes']['code'])) {
				//pr($result);exit;
				if ($result['status']['@attributes']['code'] == 'invalid') {
					$fieldName = $result['status']['invalid']['@attributes']['field'];
					$subCode = $result['status']['invalid']['@attributes']['subcode'];
					
					if ($fieldName == 'url-path' && $subCode == 'format') {
						$result['error'] = 'Invalid Custom URL';
					} else if ($fieldName == 'url-path' && $subCode == 'duplicate') {
						$result['error'] = 'Given Custom URL already exist';
					} else if ($fieldName == 'url-path' && $subCode == 'missing') {
						$result['error'] = 'Missing Custom URL';
					} 
					
					else if ($fieldName == 'name' && $subCode == 'format') {
						$result['error'] = 'Invalid Live class name';
					}else if ($fieldName == 'name' && $subCode == 'duplicate') {
						$result['error'] = 'Given Live class name already exist.'; 
					} else if ($fieldName == 'name' && $subCode == 'missing') {
						$result['error'] = 'Invalid Live class name';
					} 
					
					else if ($fieldName == 'date-begin' && $subCode == 'format') {
						$result['error'] = 'Invalid Live class start date';
					}else if ($fieldName == 'date-begin' && $subCode == 'duplicate') {
						$result['error'] = 'Live class already scheduled on this date'; 
					} else if ($fieldName == 'date-begin' && $subCode == 'missing') {
						$result['error'] = 'Invalid Live class start date';
					}  
					
					else if ($fieldName == 'date-end' && $subCode == 'format') {
						$result['error'] = 'Invalid Live class end date';
					}else if ($fieldName == 'date-begin' && $subCode == 'missing') {
						$result['error'] = 'Invalid Live class end date';
					}
				}//If error code is Invalid
			} 
			//pr($result);exit;
			return $result;
		} else {
			if($fullData) {
				$udateMeetingPermission = $this->makeRequest('permissions-update', [
							'acl-id' => $result['sco']['@attributes']['sco-id'],
							'principal-id' => 'public-access',
							'permission-id' => 'denied'
					]);
				return $result;
			} else {
				return $result['sco']['@attributes']['sco-id'];
			}
		}
	}
	/**
	 * get info about meeting
	 * @param $meeting_id
	 *
	 * @return array
	 */
	public function getMeetingInfo ($meeting_id) {
		$result = $this->makeRequest('sco-info', [
			'sco-id' => $meeting_id
		]);
		return $result['sco'];
	}
	/**
	 * get meeting archives
	 *
	 * @param int $meeting_id
	 * @param DateTime $from
	 * @param DateTime $to
	 *
	 * @return array
	 */
	public function getMeetingArchive (
		$meeting_id,
		DateTime $from = null,
		DateTime $to = null
	) {
		$request = [
			'sco-id' => $meeting_id,
			'filter-icon' => 'archive'
		];
		if ($from) {
			$request['filter-gte-date-end'] = $from->format(DateTime::ISO8601);
		}
		if ($to) {
			$request['filter-lt-date-end'] = $to->format(DateTime::ISO8601);
		}
		return $this->makeRequest('sco-contents', $request);
	}
	/**
	 * @return mixed
	 */
	public function getTemplateFolder () {
		return $this->template_folder;
	}
	/**
	 * @return mixed
	 */
	public function getRootFolder () {
		return $this->root_folder;
	}
	/**
	 * @return mixed
	 */
	public function getHost () {
		return $this->host;
	}
	/**
	 * @param $principal_id
	 * @param $group_id
	 * @return mixed
	 */
	public function addUserToGroup ($principal_id, $group_id) {
		return $this->makeRequest('group-membership-update', [
			'group-id' => $group_id,
			'principal-id' => $principal_id,
			'is-member' => true,
		]);
	}
	/**
	 * @param $principal_id
	 * @param $group_id
	 * @return mixed
	 */
	public function removeUserFromGroup ($principal_id, $group_id) {
		return $this->makeRequest('group-membership-update', [
			'group-id' => $group_id,
			'principal-id' => $principal_id,
			'is-member' => false,
		]);
	}
	/**
	 * invite user to meeting
	 *
	 * @param int $meeting_id
	 * @param string $email
	 *
	 * @param string $permission
	 * @return mixed
	 */
	public function inviteUserToMeeting ($meeting_id, $email, $permission = self::PERMISSION_VIEW) {
		$user_id = $this->getUserByEmail($email, true);
		$result = $this->makeRequest('permissions-update', [
			'principal-id' => $user_id,
			'acl-id' => $meeting_id,
			'permission-id' => $permission,
		]);
		return $result;
	}
	/**
	 * invite user to meeting
	 *
	 * @param int $meeting_id
	 * @param $group_id
	 * @param string $permission
	 *
	 * @return mixed
	 */
	public function inviteGroupToMeeting (
		$meeting_id,
		$group_id,
		$permission = self::PERMISSION_VIEW
	) {
		$result = $this->makeRequest('permissions-update', [
			'principal-id' => $group_id,
			'acl-id' => $meeting_id,
			'permission-id' => $permission
		]);
		return $result;
	}
	/**
	 *
	 */
	public function __destruct () {
		if($this->curl > 0)
		curl_close($this->curl);
	}
	/**
	 * @param       $action
	 * @param array $params
	 * @return mixed
	 * @throws Exception
	 * @throws Exception
	 */
	protected function makeRequest ($action, array $params = [], $customBuildQuery = '') {
		try {
			if ($this->breezecookie) {
				$params['session'] = $this->breezecookie;
			};
			$url = $this->host;
			$url .= '/api/xml?action=' . $action;
			if ($customBuildQuery == '') {
				$url .= '&' . http_build_query($params);
			} else {
				$post_url = '';					
				foreach ($params as $reqArrKey=>$reqVal) {
					if (!empty($reqVal) && is_array($reqVal)) {
						foreach ($reqVal as $key => $value) {
							$post_url .= $key.'='.$value.'&';
						}
					}					
				}
				$post_url = rtrim($post_url, '&');
				$url .= '&' . $post_url.'&session='.$params['session'];
			}
			curl_setopt($this->curl, CURLOPT_URL, $url);
			curl_setopt($this->curl, CURLOPT_HEADER, 1);
			$response = curl_exec($this->curl);
			//echo $response;exit;
			if ($response === false) {
				throw new Exception('Coulnd\'t perform the action: ' . $action . ' with [empty result]; ' . $url);
			}
			preg_match('/BREEZESESSION=(\w+);/', $response, $m);
			if (isset($m[1])) {
				$this->breezecookie = $m[1];
			}
			$temp = explode("\r\n\r\n", $response);
			$result = '';
			if (isset($temp[1])) {
				$result = $temp[1];
			}
			libxml_use_internal_errors();
			$xml = simplexml_load_string($result);
			//pr($xml);
			$errors = libxml_get_errors();
			$json = json_encode($xml);
			$data = json_decode($json, true);
			//pr($data);
			if (
				count($errors) > 0
					||
				!isset($data['status']['@attributes']['code'])
					||
				$data['status']['@attributes']['code'] !== 'ok'
			) {
				//throw new Exception('Coulnd\'t perform the action: ' . $action . ' with [ ' . var_export(trim($response), true) . ' ]; ' . $url);
				$data['error'] = isset($data['status']['@attributes']['code']) ? $data['status']['@attributes']['code'] : 'Request could not be processed, Please try after sometime.';
			};
		} catch (Exception $e) {
			$data['error'] = $e->getMessage();
		}
		return $data;
	}
	
	/**
	 * invite user to meeting
	 *
	 * @param int $meeting_id
	 * @param string $email
	 *
	 * @param string $permission
	 * @return mixed
	 */
	public function AddUserToMeeting ($meeting_id, $user_id, $permission = 'participants') {
		$result = $this->makeRequest('permissions-update', [
			'principal-id' => $user_id,
			'acl-id' => $meeting_id,
			'permission-id' => $permission,
		]);
		return $result;
	}
	
	/**
	 * delete Meeting 
	 *
	 * @param int $sco_id
	 */
	public function deleteMeeting ($sco_id) {
		$result = $this->makeRequest('sco-delete', [
			'sco-id' => $sco_id
		]);
		return $result;
	}
	
	/**
	 * create meeting
	 *
	 * @param int $sco_id
	 * @param string $name
	 * @param DateTime $date_begin
	 * @param DateTime $date_end
	 * @param string $url
	 * @param null $template_sco_id
	 *
	 * @return int
	 */
	public function updateMeeting (
		$sco_id,
		$name,
		$date_begin,
		$date_end,
		$url,
		$template_sco_id = null,
		$fullData = false
	) {
		$date_begin = new DateTime($date_begin);
		$date_end = new DateTime($date_end);
		$data = [
			'type' => 'meeting',
			'name' => $name,
			'sco-id' => $sco_id,
			'date-begin' => $date_begin->format(\DateTime::ISO8601),
			'date-end' => $date_end->format(DateTime::ISO8601),
			//'url-path' => $url
		];
		//pr($data);exit;
		if ($template_sco_id) {
			$data['source-sco-id'] = $template_sco_id;
		}
		$result = $this->makeRequest('sco-update', $data);
		//pr($result);
		if (isset($result['error'])) {
			if(isset($result['status']['@attributes']['code'])) {
				//pr($result);exit;
				if ($result['status']['@attributes']['code'] == 'invalid') {
					$fieldName = $result['status']['invalid']['@attributes']['field'];
					$subCode = $result['status']['invalid']['@attributes']['subcode'];
					
					if ($fieldName == 'url-path' && $subCode == 'format') {
						$result['error'] = 'Invalid Custom URL';
					} else if ($fieldName == 'url-path' && $subCode == 'duplicate') {
						$result['error'] = 'Given Custom URL already exist';
					} else if ($fieldName == 'url-path' && $subCode == 'missing') {
						$result['error'] = 'Missing Custom URL';
					} 
					
					else if ($fieldName == 'name' && $subCode == 'format') {
						$result['error'] = 'Invalid Live class name';
					}else if ($fieldName == 'name' && $subCode == 'duplicate') {
						$result['error'] = 'Given Live class name already exist.'; 
					} else if ($fieldName == 'name' && $subCode == 'missing') {
						$result['error'] = 'Invalid Live class name';
					} 
					
					else if ($fieldName == 'date-begin' && $subCode == 'format') {
						$result['error'] = 'Invalid Live class start date';
					}else if ($fieldName == 'date-begin' && $subCode == 'duplicate') {
						$result['error'] = 'Live class already scheduled on this date'; 
					} else if ($fieldName == 'date-begin' && $subCode == 'missing') {
						$result['error'] = 'Invalid Live class start date';
					}  
					
					else if ($fieldName == 'date-end' && $subCode == 'format') {
						$result['error'] = 'Invalid Live class end date';
					}else if ($fieldName == 'date-begin' && $subCode == 'missing') {
						$result['error'] = 'Invalid Live class end date';
					}
				}//If error code is Invalid
			} 
			//pr($result);exit;
			return $result;
		} else {
			if($fullData) {
				$result['sco']['@attributes']['sco-id'] = $sco_id;
				/*$udateMeetingPermission = $this->makeRequest('permissions-update', [
							'acl-id' => $result['sco']['@attributes']['sco-id'],
							'principal-id' => 'public-access',
							'permission-id' => 'denied'
					]);*/
				return $result;
			} else {
				return $result['sco']['@attributes']['sco-id'];
			}
		}
	}
	
	public function AssignBulkUserToMeeting ($arrRequestData) {
		$result = $this->makeRequest('permissions-update', $arrRequestData, 'customHttpBuildQuery');
		return $result;
	}
	
	public function UnAssignBulkUserFromMeeting ($arrRequestData) {
		$result = $this->makeRequest('permissions-update', $arrRequestData, 'customHttpBuildQuery');
		return $result;
	}
	
	
	public function getMeetingDetails ($sco_id) {		
		$arrResponse = $this->makeRequest('sco-contents', [
			'filter-icon' => 'archive',
			'sco-id' => $sco_id
		]);
		return $arrResponse;
	}
	
	public function reportMeetingAttendanceOfLiveClass ($sco_id) {		
		$arrResponse = $this->makeRequest('report-meeting-attendance', [
			'sco-id' => $sco_id
		]);
		return $arrResponse;
	}
	
	public function reportMeetingAttendanceOfRecordedClass ($sco_id) {		
		$arrResponse = $this->makeRequest('report-recording-views', [
			'sco-id' => $sco_id
		]);
		return $arrResponse;
		//'report-event-participants-complete-information'
	}
	
	public function reportMeetingSummary ($sco_id) {		
		$arrResponse = $this->makeRequest('report-meeting-summary', [
			'sco-id' => $sco_id
		]);
		return $arrResponse;
	}
	
	public function updatePassword ($user_id, $password, $password_old = '') {
		$result = $this->makeRequest('user-update-pwd', [
			'user-id' => $user_id,
			'password' => $password,
			'password-verify' => $password,			
		]);
		return $result;
	}
}
