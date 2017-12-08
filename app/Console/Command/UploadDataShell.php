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
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class UploadDataShell extends AppShell {

    var $uses = array('BulkUpload', 'Content', 'Note');
    private $RegEx_CharactersOnly = '/^[a-zA-Z\s]*$/';
    private $RegEx_Email = '/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{2,4}$/';

    /**
     * Function for converting pdf to image(s)
     * @return boolean
     */
    public function convertPdfToImage()
    {
        $contentId = $this->args[0];
        $inputFile = $this->args[1];
        $outputFile = $this->args[2];
        $flag = $this->args[3];
        $convertFile = shell_exec("gs -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r100 -sOutputFile=$outputFile $inputFile"); //  >> /home/virendra/test.txt
        if($flag)unlink($inputFile);
        if($convertFile)
        {    
            $this->Content->query("UPDATE contents SET is_notes_converted = 1 WHERE id = ".$contentId);
            return TRUE;
        }
        else 
        {   
            $this->Note->query("DELETE FROM notes WHERE content_id = ".$contentId);
            $this->Content->query("UPDATE contents SET is_notes_converted = 2 WHERE id = ".$contentId);
            return FALSE;
        }
    }
    
    /**
     * Function for converting LiveClass pdf to image(s)
     * @return boolean
     */
    public function convertLiveClassPdfToImage()
    {
        $lcContentId = $this->args[0];
        $inputFile = $this->args[1];
        $outputFile = $this->args[2];
        $flag = $this->args[3];
        $convertFile = shell_exec("gs -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r100 -sOutputFile=$outputFile $inputFile"); //  >> /home/virendra/test.txt
        if($flag)unlink($inputFile);
        if($convertFile)
        {    
            $this->Content->query("UPDATE lc_contents SET is_notes_converted = 1 WHERE id = ".$lcContentId);
            return TRUE;
        }
        else 
        {   
            $this->Note->query("DELETE FROM lc_notes WHERE lc_content_id = ".$lcContentId);
            $this->Content->query("UPDATE lc_contents SET is_notes_converted = 2 WHERE id = ".$lcContentId);
            return FALSE;
        }
    }
    
    
    
    /**
     * @method      csvDataSave
     * @desc        This function is being used to save user data and their related inforamtion in db from the csv file that is 
      uploded during bulk upload
     * @access      public
     * @param       None
     * @author      Rohit
     * @return      void
     */
    public function csvDataSave() {

        $arrResultSet = array();
        $dataSource = $this->BulkUpload->getDataSource();
        $dataSource->begin();
        try {
            //Get file info
            $uid = $this->args[0];
            $fileID = $this->args[1];
            $fileName = $this->args[2];
            $filePath = $this->args[3] . $fileName;
            //Get csv file data
            $arrData = $this->BulkUpload->getCsv($filePath, '2000', ',');
            //print_r($arrData);

            if (isset($arrData[0]['firstName'])) {
                $i = 0;
                foreach ($arrData as $dataKey => $arrDataSet) {
                    $excelFieldValidationStatus = 1;
                    $excelFieldError = '';
                    $countryId = '';
                    $sateId = '';
                    $cityId = '';
                    $saveUserResponse = array();
                    /* 	
                      //========Validate all excel fields first (Required fields, Unique fields and valid entry; If its okay then go for save data

                      //First name validation
                      if (isset($arrDataSet['firstName']) && $arrDataSet['firstName'] != '') {

                      if (!preg_match($this->RegEx_CharactersOnly,$arrDataSet['firstName'])) {
                      $excelFieldError .= 'Not a valid First name, ';
                      }

                      } else {
                      $excelFieldError .= 'First name is empty, ';
                      }


                      //Last name validation
                      if (isset($arrDataSet['LastName']) && $arrDataSet['LastName'] != '') {

                      if (!preg_match($this->RegEx_CharactersOnly,$arrDataSet['LastName'])) {
                      $excelFieldError .= 'Not a valid Last name, ';
                      }

                      }


                      //Country validation
                      if (isset($arrDataSet['Country']) && $arrDataSet['Country'] != '') {

                      if (!preg_match($this->RegEx_CharactersOnly,$arrDataSet['Country'])) {
                      $excelFieldError .= 'Not a valid Country, ';
                      } else {
                      //Check country name exist in table
                      $countryId = '';//$this->Country->checkCountryByName ($arrDataSet['Country']);
                      if ($countryId == '') {
                      $excelFieldError .= 'Not a valid country, ';
                      }
                      }

                      } else {
                      $excelFieldError .= 'Country is empty, ';
                      }


                      //State validation
                      if (isset($arrDataSet['State']) && $arrDataSet['State'] != '') {

                      if (!preg_match($this->RegEx_CharactersOnly,$arrDataSet['State'])) {
                      $excelFieldError .= 'Not a valid State, ';
                      } else {
                      //Check state name exist in table and belongs to countryId found based on countryName
                      $sateId = '';//$this->State->checkStateByNameAndCountryId ($arrDataSet['State'], $countryId);
                      if ($sateId == '') {
                      $excelFieldError .= 'Not a valid State, ';
                      }
                      }

                      } else {
                      $excelFieldError .= 'State is empty, ';
                      }

                      //City validation
                      if (isset($arrDataSet['City']) && $arrDataSet['City'] != '') {

                      if (!preg_match($this->RegEx_CharactersOnly,$arrDataSet['City'])) {
                      $excelFieldError .= 'Not a valid City, ';
                      } else {
                      //Check city name exist in table and belongs to stateId found based on stateName
                      $cityId = '';//$this->City->checkCityByNameAndStateId ($arrDataSet['City'], $stateId);
                      if ($cityId == '') {
                      $excelFieldError .= 'Not a valid City, ';
                      }
                      }

                      } else {
                      $excelFieldError .= 'City is empty, ';
                      }

                      //Gender validation
                      if (isset($arrDataSet['Gender']) && $arrDataSet['Gender'] != '') {

                      if (strtolower($arrDataSet['Gender']) != 'm' && strtolower($arrDataSet['Gender']) != 'f') {
                      $excelFieldError .= 'Not a valid value. Please use "M" or "F" , ';
                      }

                      } else {
                      $excelFieldError .= 'Gender is empty, ';
                      }

                      //DOB validation
                      if (isset($arrDataSet['DOB']) && $arrDataSet['DOB'] != '') {

                      //validate DOB format here

                      }

                      //Username validation
                      if (isset($arrDataSet['Username']) && $arrDataSet['Username'] != '') {

                      //Check username is unique
                      $userName = '';//$this->User->checkUserNameExistance ($arrDataSet['Username']);
                      if ($userName != '') {
                      $excelFieldError .= 'Username already exists, ';
                      }

                      } else {
                      $excelFieldError .= 'Username is empty, ';
                      }

                      //Email validation
                      if (isset($arrDataSet['Email']) && $arrDataSet['Email'] != '') {

                      if (!preg_match($this->RegEx_Email,$arrDataSet['Email'])) {
                      $excelFieldError .= 'Not a valid Email, ';
                      } else {
                      //Check Email is unique
                      $email = '';//$this->User->checkEmailExistance ($arrDataSet['Email']);
                      if ($email != '') {
                      $excelFieldError .= 'Email already exists, ';
                      }
                      }

                      } else {
                      $excelFieldError .= 'Email is empty, ';
                      }

                      //UserType validation
                      if (isset($arrDataSet['UserType']) && $arrDataSet['UserType'] != '') {

                      if($arrDataSet['UserType'] < 1 && $arrDataSet['UserType'] > 5) {
                      $excelFieldError .= 'Not a valid UserType, ';
                      }

                      } else {
                      $excelFieldError .= 'UserType is empty, ';
                      }

                      //======== [END] Validate all excel fields first (Required fields, Unique fields and valid entry; If its okay then go for

                      if ($excelFieldError == '') {//Excel Field validation is okay, Now go for SAVE

                      $saveUserResponse = array('status' => 0, 'message' => 'Save Failed');//$this->User->SaveUserData($arrData[$i]);

                      if ($saveUserResponse['status'] == 1) {
                      $arrData[$i]['status'] = 'SUCCESS';
                      $arrData[$i]['message'] = $saveUserResponse['message'];

                      } else {
                      $arrData[$i]['status'] = 'FAIL';
                      $arrData[$i]['message'] = $saveUserResponse['message'];
                      }

                      } else {
                      $arrData[$i]['status'] = 'FAIL';
                      $arrData[$i]['message'] = substr($excelFieldError, 0, -1);
                      }
                     */
                    $arrExcelData['User']['fname'] = $arrData[$i]['firstName'];
                    $arrExcelData['User']['lname'] = $arrData[$i]['LastName'];
                    $arrExcelData['User']['address'] = $arrData[$i]['Address'];
                    if(isset($arrData[$i]['Country'])){
                        $arrExcelData['User']['country_id'] = $arrData[$i]['Country'];
                    }
                    if(isset($arrData[$i]['State'])){
                        $arrExcelData['User']['state_id'] = $arrData[$i]['State'];
                    }
                    if(isset($arrData[$i]['City'])){
                        $arrExcelData['User']['city_id'] = $arrData[$i]['City'];
                    }
                    $arrExcelData['User']['pincode'] = $arrData[$i]['Pincode'];
                    $arrExcelData['User']['gender'] = $arrData[$i]['Gender'];
                    $arrExcelData['User']['dob'] = $arrData[$i]['DOB'];
                    $arrExcelData['User']['username'] = $arrData[$i]['Username'];
                    $arrExcelData['UserRoleMapping']['role_id'] = $arrData[$i]['UserType'];
                    $arrExcelData['User']['email'] = $arrData[$i]['Email'];
                    $arrExcelData['User']['mobile_no'] = $arrData[$i]['MobileNo'];
                    $arrExcelData['User']['qualification'] = $arrData[$i]['Qualification'];
                    $arrExcelData['User']['status'] = (isset($arrData[$i]['Status'])? $arrData[$i]['Status'] : 1 )  ;

                    $this->loadModel('User');
                    $saveUserResponse = $this->User->createUser($arrExcelData, '', 'all', array(), TRUE);

//                    var_dump($saveUserResponse);
//                    die();
//                    $saveUserResponse = array('status' => 0, 'message' => 'Save Failed with error message'); //$this->User->SaveUserData($arrExcelData]);
                    if ($saveUserResponse['status'] == 1) {
                        $arrData[$i]['status'] = 'SUCCESS';
                        $arrData[$i]['message'] = $saveUserResponse['message'];
                    } else {
                        $arrData[$i]['status'] = 'FAIL';
                        $arrData[$i]['message'] = $saveUserResponse['message'];
                    }

                    $i++;
                }//End of loop of CSV array
                //=============Update status and save response file to table				
                $file_name_with_path = $this->args[3] . 'Response_' . $fileName;
                $this->BulkUpload->id = $fileID;
                $this->BulkUpload->saveField('status', 1);
                $this->BulkUpload->saveField('processed_file_path', 'Response_' . $fileName);

                if ($this->BulkUpload->writeCsv($file_name_with_path, $arrData)) {
                    //COMMIT
                    $dataSource->commit();
                } else {
                    //ROLLBACK
                    $dataSource->rollback();
                    $this->BulkUpload->id = $fileID;
                    $this->BulkUpload->saveField('status', 2);
                    $this->BulkUpload->saveField('process_error_message', 'Unable to generate Response file.');
                }
            } else {
                //ROLLBACK
                $dataSource->rollback();
                $arrResultSet['status'] = 0;
                $arrResultSet['message'] = 'Not a valid CSV file found.';
                $this->BulkUpload->id = $fileID;
                $this->BulkUpload->saveField('status', 2);
                $this->BulkUpload->saveField('process_error_message', $arrResultSet['message']);
            }
        } catch (Exception $e) {
            //If any fatal-error exception or Not a valid CSV file found then fail the complete excel			
            //ROLLBACK
            $dataSource->rollback();
            $arrResultSet['status'] = 0;
            $arrResultSet['message'] = $e->getMessage();
            $this->BulkUpload->id = $fileID;
            $this->BulkUpload->saveField('status', 2);
            $this->BulkUpload->saveField('process_error_message', $arrResultSet['message']);
        }
    }

}
