<?php

define('DB_HOST', 'sneakersadd-9893.mysql.b.osc-fr1.scalingo-dbs.com');
define('DB_PORT', '36091');
define('DB_NAME', 'sneakersadd_9893');
define('DB_USER', 'sneakersadd_9893');
define('DB_PASSWORD', 'AJNA94M9ogcIPMkhigr9FW6APIdK7z6KHLNngODp2M8qqALF85CcUCVsdRssEMBT'); 

define('SITE_NAME', 'SneakersAddict');
define('SITE_URL', 'https://sneakersaddict.osc-fr1.scalingo.io/');
define('ADMIN_EMAIL', 'admin@sneakersaddict.com');

define('SESSION_NAME', 'SNEAKERSADDICT_SESSION');
define('CSRF_TOKEN_NAME', 'csrf_token');

define('UPLOAD_DIR', 'assets/images/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); 

date_default_timezone_set('Europe/Paris');

define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
} 