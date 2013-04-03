<?php

define('MODEL_DIRECTORY', 'model');

// FE Constant Variable
define('HTML_TITLE', '');
define('FACEBOOK_APP_ID', '');
define('FACEBOOK_SECRET', ''); 
define('FACEBOOK_URL', ''); 
define('URL_PATH', 'http://localhost'); 

// BE Constant Variable
define('ADMIN_HTML_TITLE', 'Web Administration');
define('MERCHANT_HTML_TITLE', 'Web Administration');	

define('IMAGE_URL_STARTUP', '../uploads/');

// Others
define ('IMAGE_URL', "http://".$_SERVER['SERVER_NAME']."/images/");
/*define ('UPLOAD_URL', "http://".$_SERVER['SERVER_NAME']."/upload/");*/
define ('UPLOAD_URL', "../upload/");
define ('UPLOAD_URL_OTHER', "upload/");
define ('FB_SHARED_DIR', "");
define ('PHPTHUMB_PATH', "inc/phpThumb/PHPThumb.php");
define ('IMG_UPLOAD_SIZE', 1024);

//Messages
define ('MSG_IMAGE_NOT_FOUND', "- No Image -");


?>