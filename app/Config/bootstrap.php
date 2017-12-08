<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default',  array(
    'engine' => 'File',
    'duration' => '+1 week',
    //'probability' => 100,
    //'path' => CACHE . 'long' . DS,
));

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

Configure::write('UserRoles', array(
  'ADMIN' => 1
));

// CakePlugin::load('DebugKit');
// CakePlugin::load('PdfViewer');

if (!defined('ICON_PATH')) {
  define('ICON_PATH', APP . 'webroot/icons/');
}

if (!defined('FILE_PATH')) {
  define('FILE_PATH', APP . 'webroot/');
}

if (!defined('FOLDER_PATH')) {
  define('FOLDER_PATH', APP . 'webroot/common_folder/');
}

if (!defined('BULK_UPLOAD_USER_PATH')) {
  define('BULK_UPLOAD_USER_PATH', 'files/BulkUploadUser/');
}

if (!defined('ASSETS_BASE_URL')) {
  define('ASSETS_BASE_URL', $protocol.$_SERVER['HTTP_HOST'].'/dairy/');//'http://localhost/ULS/');
}


$callBackURLOFLC = 1;	
$internalLcConnetBaseUrl = $protocol.$_SERVER['HTTP_HOST'].'/index.php';
define('ADOBE_VIRTUAL_CLASS_FOLDER_ID', 1237820339);
	
/*if (strstr(ABSOLUTE_URL, $protocol.'dairy.cosmeatiles.com/dairy')) {
	$callBackURLOFLC = 1;	
	$internalLcConnetBaseUrl = $protocol.$_SERVER['HTTP_HOST'].'/index.php';
	define('ADOBE_VIRTUAL_CLASS_FOLDER_ID', 1237820339);
}else if (strstr(ABSOLUTE_URL, $protocol.'staging.talentedge.in/LMS')) {
	$callBackURLOFLC = 2;
	$internalLcConnetBaseUrl = $protocol.$_SERVER['HTTP_HOST'].'/lcqa.php';
	define('ADOBE_VIRTUAL_CLASS_FOLDER_ID', 1237820339);
}else if (strstr(ABSOLUTE_URL, $protocol.'sliq.talentedgenxt.in')) {
	$callBackURLOFLC = 3;
	$internalLcConnetBaseUrl = $protocol.$_SERVER['HTTP_HOST'].'/lcqa.php';
	define('ADOBE_VIRTUAL_CLASS_FOLDER_ID', 1237839114);
} else {
	$callBackURLOFLC = 0;
	$internalLcConnetBaseUrl = $protocol.$_SERVER['HTTP_HOST'].'/index.php';
	define('ADOBE_VIRTUAL_CLASS_FOLDER_ID', 1237820339);
}*/
Configure::write('GlobalSettings', ['FILE_UPLOAD_SIZE_MB' => 100,
    'MAX_NUMBER_OF_FILE_UPLOAD' => 3,
    'ADOBE_CONNECT_LMS_FOLDER_ID'=>1237810448,
    'ADOBE_CONNECT_BASE_URL' => 'http://arrinaeducationservices.adobeconnect.com' ,
    'START_LIVE_CLASS_BEFORE_MINUTES' => 30,
    'INTERNAL_LC_CONNECT_BASE_URL' =>  $internalLcConnetBaseUrl,
    'INTERNAL_LC_CALLBACK_URL' => $callBackURLOFLC ,   //0=> localhost, 1=>staging.talentedge.in/dev, 2=>staging.talentedge.in/LMS, 3=>Production LMS URL
]);

define('REPO_ABSOLUTE_PATH',  ABSOLUTE_URL);
define('REPO_DIR_PATH',APP.'webroot/');
//define('REPO_ABSOLUTE_PATH',  ABSOLUTE_URL.'/trunk/');
//define('REPO_DIR_PATH','C:/wamp/www/ULS/trunk/app/webroot/');

spl_autoload_register(function ($class) {
    foreach (App::path('Vendor') as $base) {
        $path = $base . str_replace('\\', DS, $class) . '.php';
        if (file_exists($path)) {
            include $path;
            return;
        }
    }
});


define('BULK_IMPORT',REPO_DIR_PATH.'question_bulkimport/');
define('BULK_IMPORT_LOG',REPO_DIR_PATH.'question_bulkimport/log/');
define('BULK_IMPORT_IMAGES',REPO_DIR_PATH.'question_bulkimport/images/');
