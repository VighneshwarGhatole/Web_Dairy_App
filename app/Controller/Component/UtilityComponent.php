<?php
class UtilityComponent extends Component {

    var $controller;

    public function encKey($userId) {
        App::import('Vendor','EncDec/AEC');
        $userId = $this->Auth->user('id');
        $obj = new EncryptDecrypt(ENCRYPTION_SALT_FOR_TEST);
        $len = strlen($userId);
        if($len==1){
            $string = 000;
        } else if($len==2){
            $string = 00;
        } else if ($len==3) {
            $string = 0;
        } else {
            $string = '';
        }
        $string .= $userId;
        $string = substr($string,0,4);
        $encrypted = $obj->encrypt(trim($string));
    }
    
    public function makeBaseImage($text,$lastInsertId){
	/*need to relplace */
	$quesStmtImg = preg_match_all('#(<img\s(?>(?!src=)[^>])*?src=")data:image/(gif|png|jpeg);base64,([\w=+/]++)("[^>]*>)#', $text, $matchesStatement);
	
	$newfile = array();
	if(count($matchesStatement[0])>0){
	    for($i=0;$i<count($matchesStatement[0]);$i++){
		sleep(1);
		$new_data = explode("base64,",$matchesStatement[0][$i]);
		$n = str_replace(array('>','/>','" />','/\s+/'),array('','','','+'),trim($new_data[1]));
		$new[$i] = base64_decode($n);
		$newfilename = $lastInsertId.'_'. time() . '.png';
		$file = REPO_DIR_PATH.'img/question_image/questionBank_PS_images/'.$newfilename;
		file_put_contents($file, $new[$i]);
		$newfile[$i] = REPO_ABSOLUTE_PATH.'img/question_image/questionBank_PS_images/'.$newfilename;
		unset($new_data[1]);
		unset($new[$i]);
	    }
	}
	
	$text =  preg_replace_callback('#(<img\s(?>(?!src=)[^>])*?src=")data:image/(gif|png|jpeg);base64,([\w=+/]++)("[^>]*>)#', function($match) use ($newfile) { 
	    static $cnt = 0;
	    $replacedSrc = $newfile[$cnt];
	    $cnt++;
	    return (('<img src="'.$replacedSrc.'" />')); 
	}, $text);
	
	return $text;
    }
    
    public function copyImage($text,$lastInsertId){
	//** Images in Question Content[START]
	$text = $this->makeBaseImage($text,$lastInsertId);
	$quesStmtImg = preg_match_all('/<img\s.*?\bsrc="(.*?)".*?>/si', $text, $matchesStatement);
	if(count($matchesStatement[1])>0){
	    $imagesName = array();
	    $newSrc = array();
	    for($totImg=0;$totImg<count($matchesStatement[1]);$totImg++){
                $mg = str_replace("\\", "/", $matchesStatement[1][$totImg]);
		$imgName = explode('/',$mg);//** Extract Image Name
		$lastIndexVal = count($imgName)-1;//** Pick image name, exclude path
		//unset($imgName[0]);unset($imgName[1]);unset($imgName[2]);unset($imgName[3]);unset($imgName[4]);unset($imgName[5]);
		$new = explode('_',$imgName[$lastIndexVal]);
		if($lastInsertId !='' && $new[0] !=$lastInsertId){
		    //$newImgName = $lastInsertId.'_'.$imgName[$lastIndexVal];
		    $newImgName = $lastInsertId.'_'.preg_replace('/[^A-Za-z0-9\.-_]/','',$imgName[$lastIndexVal]);
		}else{
		    //$newImgName = $imgName[$lastIndexVal];
		    $newImgName = preg_replace('/[^A-Za-z0-9\.-_]/','',$imgName[$lastIndexVal]);
		}
		$imagesName[] = $newImgName;
		
		$destinationImg = REPO_DIR_PATH.'img/question_image/questionBank_PS_images/'.$newImgName;
                //unset($imgName[6],$imgName[7]);
		$sourceImg = REPO_DIR_PATH.'js/'.implode('/',$imgName);
		$sourceImg1 = REPO_DIR_PATH.'img/question_image/questionBank_PS_images/'.implode('/',$imgName);
		if(file_exists($sourceImg)){ //** File exists copy source to destination
		    copy($sourceImg,$destinationImg);
		} else if(file_exists($sourceImg1)){
		    copy($sourceImg1,$destinationImg);
		}
	    }
	    for($cnt=0;$cnt<count($imagesName);$cnt++){
		$newSrc[] = REPO_ABSOLUTE_PATH.'img/question_image/questionBank_PS_images/'.$imagesName[$cnt];
	    }
	    //$re = "/width:(?<width> \\d+).*height:(?<height> \\d+)/";
	    //preg_match_all($re, $text, $matches);
	    
	    $regex = '#src="(([^"/]*/?[^".]*\.[^"]*)"([^>]*)>)#';
	    $quesText = preg_replace_callback(
	    $regex,
	    function($match) use ($newSrc) { 
		static $cnt = 0;
		$replacedSrc = $newSrc[$cnt];
		$cnt++;
		return (('src="'.$replacedSrc.'">')); 
	    },
		$text
	    );
	}else{
	    $quesText = $text;
	}
	return $quesText;
	//** Images in Question Content[END]
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
    
    function sendPushMessage($data, $regId) {
        if(!is_array($regId)){
            $regId = array($regId);
        }
        $apiKey = '';
        $url = 'https://android.googleapis.com/gcm/send';
        $post = array(
            'registration_ids' => $regId,
            'data' => $data,
        );
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'GCM error: ' . curl_error($ch);
        } else {
		return  $result;
	}
        curl_close($ch);
    }
    
    public function sendSms($mobile,$text){
        if(!empty($mobile)){
            $text = urlencode($text);
            $mobiles=explode(',',$mobile);
            foreach($mobiles as $m){
                $url = "";
                $result = file_get_contents($url);
            }
            return $result;
        }else
            return false;
    }
    
    function sendMail($toMail, $msgSubject, $msgBody, $template, $attachments){                                             
        if($toMail != ''){
            //=============Send Mail===========                
            App::uses('CakeEmail', 'Network/Email');
            $email = new CakeEmail('fast1');
            $email->addHeaders(array('X-APIHEADER' => $template,'X-TAGS'=>$msgSubject.','.$template));
            $email->viewVars(array( 'message' => $msgBody));
            $email->template($template)->emailFormat('html')->to(addslashes($toMail));
            $email->subject(addslashes($msgSubject));
            $email->attachments($attachments);
            try{
                $flag = $email->send();
                $flag = 'Mail Sent.';
            }catch(Exception $e){
                $flag = $e->getMessage();
            }
            $email->reset();
        } else {
            $flag = "Blank Mail Id.";
        }
        return $flag;
    }
}

