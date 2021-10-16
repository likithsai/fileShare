<?php
    define('APP_NAME', 'fileshare');
    define('USER', 'root');
    define('PASSWORD', '');
    define('HOST', 'localhost');
    define('DATABASE', 'fileshare');
    define('SEC_KEY', '4t7w!z%C*F-JaNdRgUkXp2s5u8x/A?D(');
    define('DEBUG', true);
    define('FILE_UPLOAD_PATH', 'uploads/');
    define('TIMEZONE', 'UTC');

    date_default_timezone_set(TIMEZONE); 

    if ( DEBUG === true ) {
        ini_set('display_errors', 'on');
        ini_set('error_reporting', 'E_ALL');
        ini_set('display_startup_errors', 'On');
        error_reporting(E_ALL);
    }
    else {
        error_reporting(0);
    }

    define('GLOBAL_TIME_LIMIT', 240*60);
    define('UPLOAD_TIME_LIMIT', 120*60);
    @set_time_limit(GLOBAL_TIME_LIMIT);
?>