<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of upload
 *
 * @author Rohit Roy
 */
class BulkUpload extends AppModel {
    //put your code here
    public $useTable = 'csv_upload_info';
    public $primaryKey = 'id';
    
    /* Declaring properties of getcsv methods */
    private $counter;
    private $handler;
    private $length;
    private $file;
    Private $seprator;
    private $csvData = array();

    /* method to get csvfile to array */
    function getCsv($file, $length = 1000, $seprator = ',') {
       try {
        //$file = "files/user.csv";
        $this->counter = 0;
        $this->length = $length;
        $this->file = $file;
        $this->seprator = $seprator;
        $this->handler = fopen($this->file, "r");
        $getCsvArr = array();
        $csvDataArr = array();
        while (($data = fgetcsv($this->handler, $this->length, $this->seprator)) != FALSE) {
            $num = count($data);
            $getCsvArr[$this->counter] = $data;
            $this->counter++;
        }
        if (count($getCsvArr) > 0) {
            $csvDataArr = array_shift($getCsvArr);
            $counter = 0;
            foreach ($getCsvArr as $csvValue) {
                $totalRec = count($csvValue);
                for ($i = 0; $i < $totalRec; $i++) {
                    $this->csvData[$counter][$csvDataArr[$i]] = $csvValue[$i];
                }
                $counter++;
            }
        }
        fclose($this->handler);
       
		} catch (Exception $e) {
			//echo $e->getMessage();
		}	 
	  return $this->csvData;
	  
    }
    
 /**
 * save uploaded files to table
 * @params:
 *		$formData = 
					 Array
					(
						[fileName] => Array
							(
								[name] => Upload-User-Excel.xlsx
								[type] => application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
								[tmp_name] => /tmp/phpij1VEX
								[error] => 0
								[size] => 4793
							)

					)
 *		will return an array with the success or failure
 * @author:	Rohit Roy
 * @date : 17-Sep-16
 */     
    public function saveUploadedFiles($formData) {
		$arrResultSet = array();
		$fileName = '';
		try {
			if (!empty($formData['file']['name'])) {
				$fileName = preg_replace('/\s+/', '_', $formData['file']['name']);						
			}
			$uploadResponse = $this->uploadFiles(BULK_UPLOAD_USER_PATH, $formData, $fileName);
			if (isset($uploadResponse['urls'])) {						
				//Save data in table
				$uploadedFile = array();
				$uploadedFile['BulkUpload']['name'] = $uploadResponse['urls']['fileName'];//$fileName;
				$uploadedFile['BulkUpload']['path'] = BULK_UPLOAD_USER_PATH;
				$uploadedFile['BulkUpload']['type'] = $formData['file']['type'];
				$uploadedFile['BulkUpload']['size'] = $formData['file']['size'];
				$uploadedFile['BulkUpload']['file_type'] = 'BulkUploadUser';
				$uploadedFile['BulkUpload']['created'] = date("Y-m-d h:i:s");
				$uploadedFile['BulkUpload']['modified'] = date("Y-m-d h:i:s");
				$uploadedFile['BulkUpload']['created_by'] = AuthComponent::user('id');//$this->Auth->User('uid');
				if ($this->save($uploadedFile)) {					
					$arrResultSet['status'] = 1;
					$arrResultSet['message'] = 'File has been uploaded.';						
				} else {
					//$this->Flash->set(__('Unable to save file.'));
					$arrResultSet['status'] = 0;
					$arrResultSet['message'] = 'Unable to save file.';
				}
				
			} else {
				$errors = '';
				foreach ($uploadResponse['errors'] as $errorIndex => $errorName){
					$errors .= $errorName. ',';
				}
				$errors = ($errors != '' ) ? substr($errors, 0, -1) : $errors;
				
				$arrResultSet['status'] = 0;
				$arrResultSet['message'] = $errors;
			}
					
		} catch (Exception $e) {
			$arrResultSet['status'] = 0;
			$arrResultSet['message'] = $e->getMessage();
		}
		
		return $arrResultSet;
		
	}
    
    
/**
 * ******************************************************MOVE this function to some common places**********************************
 * uploads files to the server
 * @params:
 *		$folderName = the folder to upload the files e.g. 'img/files'
 *		$formData 	= the array containing the form files
 * 		$fileName	= Custom filename of uploaded files
 *		$uploadType = whether to upload it to Local server or AWS
 * @return:
 *		will return an array with the success of each file upload
 * @author:	Rohit Roy
 * @date : 15-Sep-16
 */
function uploadFiles($folderName, $formData, $fileName = '', $uploadType = 'local') {
			
	try {		
		if ( $uploadType == 'local') {
			// setup dir names absolute and relative
			$folder_url = WWW_ROOT. $folderName;
			$rel_url = $folderName;
			
			// create the folder if it does not exist
			if(!is_dir($folder_url)) {
//                            App::uses('Folder', 'Utility');
//                            $dir = new Folder($folder_url, true, 0755);
				mkdir($folder_url, 0777);
			}
			
			// list of permitted file types, this is only images but documents can be added
			$permitted = array('application/octet-stream','image/gif','image/jpeg','image/jpg','image/png', 'text/csv', 
			'application/pdf', 'text/pdf', 'application/msword', 'application/doc', 'application/docx', 'application/x-pdf','applications/vnd.pdf', 
			'text/x-pdf', 'application/acrobat', 
			'application/vnd.openxmlformats-officedocument.presentationml.presentation', 
			'csv.application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'video/mp4','application/force-download', 'application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/vnd.ms-powerpoint',
                        'application/zip','application/x-zip-compressed'    
                            );
	
			// loop through and deal with the files
			foreach($formData as $file) {
				
                                /*******  code to handle multiple dots in file name [ujjwal (10/01/2017 )]*******/
                                
                                    $basename = basename($file['name']);

                                    $filename = pathinfo($basename,PATHINFO_FILENAME);
                                    $ext=pathinfo($basename,PATHINFO_EXTENSION);
                                    //replace all these characters with an hyphen
                                    $repair = array(".",","," ",";","'","\\","\"","/","(",")","?");

                                    $repairedfilename=str_replace($repair,"_",$filename);
                                    $cleanfilename = $repairedfilename.".".strtolower($ext);
                                    $filename =  $cleanfilename;

                                /**************** End ***********/
                                    
                                // replace spaces with underscores
//				$filename = ($fileName == '' ) ? str_replace(array(' ',','), array('_','_'), $file['name']) : $fileName;
				$path_parts = pathinfo($filename);	
				$path_parts['extension'] = strtolower($path_parts['extension']);			
				// assume filetype is false
				$typeOK = false;
				// check filetype is ok
				//echo $file['type'];exit;
				foreach($permitted as $type) {
					if($type == $file['type']) {
						$typeOK = true;
						break;
					}
				}
		
				// if file type ok upload the file
				if($typeOK) {
					// switch based on error code
					switch($file['error']) {
						case 0:
							// check filename already exists
							if(!file_exists($folder_url.'/'.$filename)) {
								// create full filename
								$full_url = $folder_url.'/'.$filename;
								$url = $rel_url.'/'.$filename;
								// upload the file
								$success = move_uploaded_file($file['tmp_name'], $url);
								chmod($url, 0777);
							} else {
								// create unique filename and upload file
								$now = date('Y-m-d-His');
								$full_url = $folder_url.'/'.$now.$filename;
								$url = $rel_url.'/'.$now.$filename;
								$success = move_uploaded_file($file['tmp_name'], $url);
								$filename = $now.$filename;
								chmod($url, 0777);
							}
							
							//Create Thumb Nail
							if ($success && ($path_parts['extension'] == 'gif' || $path_parts['extension'] == 'pjpeg' || $path_parts['extension'] == 'jpeg' || $path_parts['extension'] == 'jpg' || $path_parts['extension'] == 'png')) {
								//cut the main image to required size
                                $iconSizeArr = array(
									'50x50' => array('50', '50'),
                                    //'100x100' => array('100', '100'),
                                    '150x150' => array('150', '150'),
                                    //'250x250' => array('250', '250'),
                                    '300x300' => array('300', '300'),
                                    //'400x400' => array('400', '400'),
                                );
                                $sourcePath = $url;
                                list($w, $h, $imageType) = getimagesize($url);
                                //image array irritate
                                $dirPath = '';
                                foreach ($iconSizeArr as $key => $value) {
                                    $dirPath = $rel_url.'/'.$key.'_'.$filename;
                                    $thumbWidth = $value[0];
                                    $thumbHeight = $value[1];
                                    //Scale the image to the thumb_width set above
                                    $thumbImageLocation = $dirPath;
                                    $scale = $thumbWidth / $w;
                                    $this->resizeThumbnailImage($thumbImageLocation, $sourcePath, $w, $h, 0, 0, $scale, '');
                                }
								
							}						
							//Create Thumb Nail End
							
							
							
							// if upload was successful
							if($success) {
								// save the url of the file
								$result['urls'][] = $url;
								$result['urls']['fileName'] = $filename;
							} else {
								$result['errors'][] = "Error uploaded $filename. Please try again.";
							}
						break;
						case 3:
							// an error occured
							$result['errors'][] = "Error uploading $filename. Please try again.";
							break;
						default:
							// an error occured
							$result['errors'][] = "System error uploading $filename. Contact Administrator.";
							break;
					}
				} elseif($file['error'] == 4) {
					// no file was selected for upload
					$result['errors'][] = "No file Selected";
				} else {
					// unacceptable file type
					$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png, csv.";
			}//End of if-else if file type Okay
		}//End of Loop of formdata
			
	  } else {//AWS logic goes here
		  
		  
		  
		  
			
	  }//End of if-else Upload type is local or AWS
		
	} catch(Exception $e){
		$result['errors'] [] = 'Something went wrong!!! '.$e->getMessage();
	}
	return $result;
 }
 
/**
 * Function to convert files
 */ 
function convertFile($contentId, $folderName, $fileName, $moduleName = '', $uploadType = 'local')
{
    try
    {	
        $result = array();	
        if($uploadType == 'local')
        {
            $folderUrl = WWW_ROOT. $folderName;
            // echo $folderUrl."<br>".$fileName."<br>"; // /var/www/html/demo/app/webroot/files/notes     multipage.docx
            list($baseName, $ext) = explode(".", $fileName);
            if(strtolower($ext) != 'pdf')	
            {
                $flag = shell_exec('export HOME='.$folderUrl.'/ && libreoffice --headless --convert-to pdf --outdir '.$folderUrl.'/ '.$folderUrl.'/'.$fileName);
                if($flag)
                {
                    //  unlink($folderUrl.'/'.$fileName);	
                    $fileName = $baseName.'.pdf';
                }
            }
            if(isset($flag) || strtolower($ext) == 'pdf')
            {
                $im = new Imagick();
                $im->readImage($folderUrl.'/'.$fileName); 
                $numPages = $im->getNumberImages();
                $strSlash = '/'; $strHyphenNum = '-%d'; $strExt = '.jpg';
                if(isset($flag))$strFlag = 1; else $strFlag = '';
                // $convertFile = shell_exec("nohup convert -density 150 $folderUrl$strSlash$fileName -quality 100 $folderUrl$strSlash$baseName$strHyphenNum$strExt &");
                
                if($moduleName == 'LiveClassContent'){
                    shell_exec(APP."Console/cake UploadData convertLiveClassPdfToImage $contentId $folderUrl$strSlash$fileName $folderUrl$strSlash$baseName$strHyphenNum$strExt $strFlag >/dev/null 2>/dev/null &");
                }else{
                    shell_exec(APP."Console/cake UploadData convertPdfToImage $contentId $folderUrl$strSlash$fileName $folderUrl$strSlash$baseName$strHyphenNum$strExt $strFlag >/dev/null 2>/dev/null &");
                }
                for($i = 1; $i<=$numPages; $i++)
                {
                    $newFileName = $baseName.'-'.$i;
                    $result[] = $folderName.'/'.$newFileName.'.jpg';
                }
                $im->clear();
                $im->destroy();
            }
            else
            { 
                $result['errors'][] = "Error in converting $fileName. Please try again.";
            }
        }
        else
        {//AWS logic goes here

        }//End of if-else Upload type is local or AWS
    }
    catch(Exception $e)
    {
        $result['errors'][] = 'Something went wrong!!! '.$e->getMessage();
    }
    return $result;
} 
 
/**
 * Read uploaded files and set status to Intermediate i.e. 5 in table and send the file to execute as a background process
 * @params: fileID
 * @return	will return an array with the success or failure
 * @author:	Rohit Roy
 * @date : 19-Sep-16
 */     
    public function processUploadedFile($fileID) {
		$arrResultSet = array();		
		try {
			$arrUploadData = array();
			if ($fileID >= 1) {
				$arrUploadData = $this->find('first', array('conditions' => array('BulkUpload.status' => '0', 'BulkUpload.id' => $fileID), 'fields' => array('BulkUpload.path', 'BulkUpload.id', 'BulkUpload.name')));
			}
			if (!empty($arrUploadData)) {
				$uid = AuthComponent::user('id');
				$filePath = WWW_ROOT.$arrUploadData['BulkUpload']['path'];
				$fileName = $arrUploadData['BulkUpload']['name'];
				$fileID = $arrUploadData['BulkUpload']['id'];
				$arrData = $this->getCsv($filePath.$fileName, '2000', ',');
				if (!isset($arrData[0]['firstName'])) {
					$arrResultSet['status'] = 0;
					$arrResultSet['message'] = 'Data not saved. Please check your file.';
				} else {						
					//For intermediate process
					$this->id = $fileID;
					$this->saveField('status', 5);					
					//Call the method in console
					shell_exec(APP."Console/cake UploadData csvDataSave $uid $fileID $fileName $filePath>/dev/null 2>/dev/null &");
					$arrResultSet['status'] = 1;
					$arrResultSet['message'] = 'Data is being processed! Please refresh your page after some time.';				
				}//End of if-else CSV data is in proper format
				
			} else {				
				$arrResultSet['status'] = 0;
				$arrResultSet['message'] = 'Not a valid File.';
			}
					
		} catch (Exception $e) {
			$arrResultSet['status'] = 0;
			$arrResultSet['message'] = $e->getMessage();
		}
		
		return $arrResultSet;
		
	}
	
	/* method to write array to csvfile */
    function writeCsv($file_name_with_path, $input_array, $delimiter = ',', $enclosure = '"') {
       try {        
			$fp = fopen($file_name_with_path, "w");
			fputcsv($fp, array_keys($input_array['0']));
			foreach($input_array as $values){
				fputcsv($fp, $values);
			}
			fclose($fp);
			return true;
       
		} catch (Exception $e) {
			//echo $e->getMessage();
			return false;
		}
	  
    }
    
    /**
     * To resizeThumbnailImage resize Thumbnail Image
     * @param string $thumbImageName thumb image directory
     * @param string $image large image directory
     * @param int    $width width of image
     * @param string $height height of image
     * @param string $startWidth start width
     * @param int    $startHeight start height
     * @param int    $scale scale ratio
     * @param int    $rotateVal Rotate in degree
     * @access public
     * @return : Void
     * @author : ROhit Roy
     * @date   : 3-Nov-16
     */
    public function resizeThumbnailImage($thumbImageName, $image, $width, $height, $startWidth, $startHeight, $scale, $rotateVal) {
        //$thumbImageName = '';
        try {
			list($imagewidth, $imageheight, $imageType) = getimagesize($image);
			$imageType = image_type_to_mime_type($imageType);

			$newImageWidth = ceil($width * $scale);

			$newImageHeight = ceil($height * $scale);

			$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);

			switch ($imageType) {
				case "image/gif":
					$source = imagecreatefromgif($image);
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$source = imagecreatefromjpeg($image);
					break;
				case "image/png":
				case "image/x-png":
					imagealphablending($newImage, false);
					$background = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
					imagefilledrectangle($newImage, 0, 0, $newImageWidth, $newImageHeight, $background);
					imagealphablending($newImage, true);
					$source = imagecreatefrompng($image);
					break;
			}
			imagecopyresampled($newImage, $source, 0, 0, $startWidth, $startHeight, $newImageWidth, $newImageHeight, $width, $height);
			if ($rotateVal != '') {
				$degree = $rotateVal;
				switch ($degree) {
					case 90:
					case 270:
						$newImage = imagerotate($newImage, $degree - 180, 0);
						break;
					case 180:
						$newImage = imagerotate($newImage, $degree, 0);
						break;
					default:
						$newImage = imagerotate($newImage, $degree, 0);
						break;
				}
			}
			switch ($imageType) {
				case "image/gif":
					imagegif($newImage, $thumbImageName);
					chmod($thumbImageName, 0777);
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					imagejpeg($newImage, $thumbImageName, 90);
					chmod($thumbImageName, 0777);
					break;
				case "image/png":
				case "image/x-png":
					imagealphablending($newImage, true);
					imagesavealpha($newImage, true);
					imagepng($newImage, $thumbImageName);
					chmod($thumbImageName, 0777);
					break;
			}
		} catch (Exception $e) {
			// catch exception here
			//$e->getMessage();
		}
        return $thumbImageName;
    }


}

?>
