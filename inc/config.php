<?php

require("inc/dbconnection.php");

// incldue the ActiveRecord library
require_once 'inc/php-activerecord/ActiveRecord.php';
/*$connections = array(
 					'development' => 'mysql://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME.'',
					'production' => 'mysql://username:password@localhost/production',
					'test' => 'mysql://username:password@localhost/test'
					);*/
$connections = array(
 					'development' => AR_DEVELOPMENT,
					'testing' => AR_TESTING,
					'production' => AR_PRODUCTION
					);
					
 ActiveRecord\Config::initialize(function($cfg) use ($connections)
{
   $cfg->set_model_directory('model/');
   ////$cfg->set_connections(array('development' =>'mysql://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME.''));
   $cfg->set_connections($connections);
   $cfg->set_default_connection('development');
 });

//Others
require("inc/variables.php");
require("inc/library.php");
require("inc/upload_function.php");
require("inc/phpuploader/include_phpuploader.php");
require("inc/sentemail.php");
require_once 'inc/phpThumb/ThumbLib.inc.php';

//require_once 'ModelDB/commonFunctions.php';
//require_once 'ModelDB/mainFuncs.php';
?>