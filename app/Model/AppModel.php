<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
   
    public function softdelete($field = 'is_deleted', $value = 1){
        return $this->saveField($field, $value);
    }
    
    public function checkDeleted($id) {
        return $this->find('count',['conditions'=>[$this->alias.'.'.$this->primaryKey => $id, $this->alias.'.is_deleted'=> 0]]);
        
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
    
    public function showImage($fullImageURL, $dimension)
    {
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
	 * * I/P =>
	 * Array
			(
				[0] => 7
				[1] => 9
				[2] => 16
			)
	 */ 
	public function prepairConnectSRMData($arrBatches) {
		try {
			if (!empty($arrBatches)) {
				$arrAllSRM = array();
				$this->UserBatchMapping = ClassRegistry::init('UserBatchMapping');
				foreach ($arrBatches as $key => $batchID) {
					$arrSRM = $this->UserBatchMapping->getBatchUsers($batchID, Configure::Read('UserRoles.SRM'));
					if (isset($arrSRM['resultData']['SRM'][0])) {
						$arrSRM['resultData']['SRM'][0]['batch_id'] = $batchID;
						$arrAllSRM[] = $arrSRM['resultData']['SRM'][0];
					}
				}
				return $arrAllSRM;
				
			} else {
				return array();
			}
		} catch (Exception $e) {
			return array();
		}
		
	}
    protected function __unbindAllAssociations(Model $Model) {
        foreach ($Model->associations() as $assocname) {
            $Model->{$assocname} = array();
        }
        return true;
    }
}
